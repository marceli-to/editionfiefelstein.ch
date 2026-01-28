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
    // Implement payment process
    \Stripe\Stripe::setApiKey(env('PAYMENT_STRIPE_PRIVATE_KEY'));
    $domain = config('app.url');

    // Get cart and build items array
    $cart = (new GetCart())->execute();
    $items = [];

    $totalDeliverableQuantity = 0;

    foreach ($cart['items'] as $item)
    {
      // Fetch current price from database - never trust session prices
      $product = Product::where('uuid', $item['uuid'])->first();

      if (!$product) {
        // Product no longer exists - skip this item
        continue;
      }

      // Use database price, not session price
      $unit_amount = (int) ($product->price * 100);

      // Count deliverable items for shipping calculation
      if ($product->state->value === 'deliverable') {
        $totalDeliverableQuantity += $item['quantity'];
      }

      $items[] = [
        'price_data' => [
          'currency' => 'chf',
          'unit_amount' => $unit_amount,
          'product_data' => [
            'name' => $product->title,
            'images' => [env('APP_URL') . "/img/small/" . $product->image],
          ],
        ],
        'quantity' => $item['quantity'],
      ];
    }

    // Add shipping as separate line item if there are deliverable items
    if ($totalDeliverableQuantity > 0) {
      $shippingCost = (new CalculateShipping())->execute($totalDeliverableQuantity);
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

    // Create checkout session id
    $checkout_session = \Stripe\Checkout\Session::create([
      'customer_email' => $cart['invoice_address']['email'],
      'submit_type' => 'pay',
      'payment_method_types' => ['card'],
      'line_items' => $items,
      'mode' => 'payment',
      'locale' => app()->getLocale(),
      'success_url' => route('order.payment.success'),
      'cancel_url' => route('order.payment.cancel'),
    ]);

    // Redirect to Stripe
    return redirect()->away($checkout_session->url);
  }

  public function paymentSuccess()
  {
    $cart = (new UpdateCart())->execute([
      'order_step' => $this->handleStep(6),
      'is_paid' => true,
    ]);

    $order = (new HandleOrder())->execute();
    return redirect()->route('order.confirmation', $order);
  }

  public function paymentCancel()
  {
    return redirect()->route('order.summary'); 
  }

  public function confirmation(Order $order)
  {
    return view('pages.order.confirmation', [
      'order' => $order,
      'order_step' => $this->handleStep(6),
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