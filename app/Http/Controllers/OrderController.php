<?php
namespace App\Http\Controllers;
use App\Http\Requests\InvoiceAddressStoreRequest;
use App\Http\Requests\ShippingAddressStoreRequest;
use App\Http\Requests\PaymentMethodStoreRequest;
use App\Http\Requests\OrderStoreRequest;
use App\Actions\Cart\GetCart;
use App\Actions\Cart\StoreCart;
use App\Actions\Cart\UpdateCart;
use App\Actions\Cart\CalculateShipping;
use App\Actions\Cart\DestroyCart;
use App\Actions\Order\HandleOrder;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
  public function index()
  {
    return view('pages.order.overview', [
      'cart' => (new GetCart())->execute(),
      'order_step' => $this->handleStep(1),
    ]);
  }

  public function invoice()
  {
    return view('pages.order.invoice-address', [
      'cart' => (new GetCart())->execute(),
      'order_step' => $this->handleStep(1),
    ]);
  }

  public function storeInvoice(InvoiceAddressStoreRequest $request)
  {
    $cart = (new UpdateCart())->execute([
      'invoice_address' => $request->only(
        ['salutation', 'company', 'firstname', 'name', 'street', 'street_number', 'zip', 'city', 'country', 'email']
      ),
      'order_step' => $this->handleStep(2),
    ]);
    return redirect()->route('order.shipping-address');
  }

  public function shipping()
  {
    $cart = (new GetCart())->execute();
    $can_use_invoice_address = in_array(
      $cart['invoice_address']['country'],
      config('countries.delivery')
    ) ?? FALSE;

    return view('pages.order.shipping-address', [
      'cart' => (new GetCart())->execute(),
      'order_step' => $this->handleStep(2),
      'can_use_invoice_address' => $can_use_invoice_address,
    ]);
  }

  public function storeShipping(ShippingAddressStoreRequest $request)
  {
    $cart = (new UpdateCart())->execute([
      'shipping_address' => !$request->use_invoice_address ?
        $request->only(['use_invoice_address', 'company', 'firstname', 'name', 'street', 'street_number', 'zip', 'city', 'country']) :
        $request->only(['use_invoice_address']),
      'order_step' => $this->handleStep(3),
    ]);
    return redirect()->route('order.payment');
  }

  public function payment()
  {
    return view('pages.order.payment', [
      'cart' => (new GetCart())->execute(),
      'order_step' => $this->handleStep(4),
    ]);
  }

  public function storePaymentMethod(PaymentMethodStoreRequest $request)
  {
    $cart = (new UpdateCart())->execute([
      'payment_method' => $request->payment_method,
      'order_step' => $this->handleStep(4),
    ]);
    return redirect()->route('order.summary');
  }

  public function summary()
  {
    return view('pages.order.summary', [
      'cart' => (new GetCart())->execute(),
      'order_step' => $this->handleStep(5),
    ]);
  }

  public function finalize(OrderStoreRequest $request)
  {
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    // Create pending order BEFORE redirecting to Stripe
    $order = (new HandleOrder())->createPending();

    // Get cart for email
    $cart = (new GetCart())->execute();

    // Build line items from the order (which used database prices)
    $items = [];
    foreach ($order->orderProducts as $orderProduct)
    {
      $items[] = [
        'price_data' => [
          'currency' => 'chf',
          'unit_amount' => (int) (($orderProduct->price / $orderProduct->quantity) * 100),
          'product_data' => [
            'name' => $orderProduct->title,
            'images' => [config('app.url') . "/img/" . $orderProduct->image],
          ],
        ],
        'quantity' => $orderProduct->quantity,
      ];
    }

    // Calculate shipping from order total vs product prices
    $productTotal = $order->orderProducts->sum('price');
    $shippingCost = $order->total - $productTotal;

    if ($shippingCost > 0) {
      $items[] = [
        'price_data' => [
          'currency' => 'chf',
          'unit_amount' => (int) ($shippingCost * 100),
          'product_data' => [
            'name' => 'Versand',
          ],
        ],
        'quantity' => 1,
      ];
    }

    // Create checkout session with order reference
    $checkout_session = \Stripe\Checkout\Session::create([
      'customer_email' => $cart['invoice_address']['email'],
      'submit_type' => 'pay',
      'payment_method_types' => ['card'],
      'line_items' => $items,
      'mode' => 'payment',
      'locale' => app()->getLocale(),
      'success_url' => route('order.payment.success', ['order' => $order->uuid]),
      'cancel_url' => route('order.payment.cancel', ['order' => $order->uuid]),
      'metadata' => [
        'order_uuid' => $order->uuid,
      ],
    ]);

    // Store the Stripe session ID on the order
    $order->stripe_session_id = $checkout_session->id;
    $order->save();

    // Clear the cart now that order is created
    (new DestroyCart())->execute();

    // Redirect to Stripe
    return redirect()->away($checkout_session->url);
  }

  public function paymentSuccess(Order $order)
  {
    // The webhook will mark the order as paid and send notifications.
    // This page just shows a confirmation to the user.
    // The order might not be marked as paid yet if the webhook hasn't fired.
    return redirect()->route('order.confirmation', $order);
  }

  public function paymentCancel(Order $order)
  {
    // User cancelled payment - the order exists but is unpaid
    // We could delete it or leave it for manual review
    // For now, redirect back to create a new order
    return redirect()->route('order.overview')
      ->with('error', 'Die Zahlung wurde abgebrochen. Bitte versuchen Sie es erneut.');
  }

  public function confirmation(Order $order)
  {
    return view('pages.order.confirmation', [
      'order' => $order,
      'order_step' => 6,
    ]);
  }

  private function handleStep($current)
  {
    $cart = (new GetCart())->execute();
    $step = isset($cart['order_step']) && $cart['order_step'] > $current ? $cart['order_step'] : $current;
    (new UpdateCart())->execute([
      'order_step' => $cart['items'] ? $step : 1,
    ]);
    return $step;
  }
}
