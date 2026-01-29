<?php
namespace App\Actions\Order;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Actions\Product\FindProduct;
use App\Actions\Cart\GetCart;
use App\Actions\Cart\DestroyCart;
use App\Actions\Cart\CalculateShipping;

class HandleOrder
{
  /**
   * Create a pending order (before payment)
   */
  public function createPending(): Order
  {
    $cart = (new GetCart())->execute();
    return $this->create($cart, false);
  }

  /**
   * Legacy method - creates order and marks as paid immediately
   * Used for non-Stripe payment methods if needed
   */
  public function execute()
  {
    $order = $this->create(
      (new GetCart())->execute(),
      true
    );
    (new DestroyCart())->execute();
    return $order;
  }

  private function create($data, $markAsPaid = false)
  {
    $order = Order::create([
      'uuid' => \Str::uuid(),
      'salutation' => $data['invoice_address']['salutation'] ?? '',
      'company' => $data['invoice_address']['company'] ?? '',
      'firstname' => $data['invoice_address']['firstname'],
      'name' => $data['invoice_address']['name'],
      'street' => $data['invoice_address']['street'],
      'street_number' => $data['invoice_address']['street_number'] ?? '',
      'zip' => $data['invoice_address']['zip'],
      'city' => $data['invoice_address']['city'],
      'country' => $data['invoice_address']['country'],
      'email' => $data['invoice_address']['email'],
      'use_invoice_address' => $data['shipping_address']['use_invoice_address'] ?? 0,
      'shipping_company' => $data['shipping_address']['company'] ?? '',
      'shipping_firstname' => $data['shipping_address']['firstname'] ?? '',
      'shipping_name' => $data['shipping_address']['name'] ?? '',
      'shipping_street' => $data['shipping_address']['street'] ?? '',
      'shipping_street_number' => $data['shipping_address']['street_number'] ?? '',
      'shipping_zip' => $data['shipping_address']['zip'] ?? '',
      'shipping_city' => $data['shipping_address']['city'] ?? '',
      'shipping_country' => $data['shipping_address']['country'] ?? '',
      'payment_method' => $data['payment_method'],
      'payed_at' => $markAsPaid ? now() : null,
    ]);

    // Create order products and calculate total from database prices
    $total = 0;
    $totalDeliverableQuantity = 0;

    foreach ($data['items'] as $item)
    {
      $product = Product::where('uuid', $item['uuid'])->first();

      if (!$product) {
        continue;
      }

      // Use database price, not session price
      $price = $product->price * $item['quantity'];

      // Track deliverable items for shipping calculation
      if ($product->state->value === 'deliverable') {
        $totalDeliverableQuantity += $item['quantity'];
      }

      OrderProduct::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'title' => $product->title,
        'isbn' => $product->isbn,
        'image' => $product->image,
        'quantity' => $item['quantity'],
        'price' => $price,
        'shipping' => 0, // Shipping is now a separate line item
      ]);

      // Update running total
      $total += $price;

      // Update product stock but make sure it doesn't go below 0
      $product->stock = $product->stock - $item['quantity'] > 0 ? $product->stock - $item['quantity'] : 0;

      // If stock is 0, set product state to not available
      if ($product->stock == 0)
      {
        $product->state = 'not_available';
      }

      $product->save();
    }

    // Add shipping cost if there are deliverable items
    if ($totalDeliverableQuantity > 0) {
      $shippingCost = (new CalculateShipping())->execute($totalDeliverableQuantity);
      $total += $shippingCost;
    }

    // Update order total with the calculated total
    $order->total = $total;
    $order->save();

    // Return order with products and order products
    return Order::with('orderProducts')->find($order->id);
  }
}
