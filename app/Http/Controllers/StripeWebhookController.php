<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Pdf\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderConfirmationNotification;
use App\Notifications\OrderInformationNotification;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response('Invalid signature', 400);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response('Webhook error', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $this->handleCheckoutSessionCompleted($session);
        }

        return response('OK', 200);
    }

    private function handleCheckoutSessionCompleted($session)
    {
        $stripeSessionId = $session->id;

        $order = Order::where('stripe_session_id', $stripeSessionId)->first();

        if (!$order) {
            Log::error('Stripe webhook: Order not found for session ' . $stripeSessionId);
            return;
        }

        if ($order->payed_at) {
            Log::info('Stripe webhook: Order already marked as paid ' . $order->uuid);
            return;
        }

        $order->payed_at = now();
        $order->save();

        $this->sendNotifications($order);

        Log::info('Stripe webhook: Order marked as paid ' . $order->uuid);
    }

    private function sendNotifications(Order $order)
    {
        $order->load('orderProducts');

        $pdf = (new Pdf())->create([
            'data' => $order,
            'view' => 'invoice',
            'name' => config('invoice.invoice_prefix') . $order->uuid,
        ]);

        try {
            Notification::route('mail', $order->email)
                ->notify(new OrderConfirmationNotification($order, $pdf));
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation: ' . $e->getMessage());
        }

        try {
            Notification::route('mail', env('MAIL_TO'))
                ->notify(new OrderInformationNotification($order, $pdf));
        } catch (\Exception $e) {
            Log::error('Failed to send order information: ' . $e->getMessage());
        }
    }
}
