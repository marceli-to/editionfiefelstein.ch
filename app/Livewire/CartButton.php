<?php
namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Collection;
use App\Actions\Product\FindProduct;
use App\Actions\Cart\GetCart;
use App\Actions\Cart\StoreCart;
use App\Actions\Cart\DestroyCart;

class CartButton extends Component
{
  public $productUuid;
  public $itemsInCart;
  public $cart;
  
  public function mount($productUuid)
  {
    $this->productUuid = $productUuid;
    $this->cart = (new GetCart())->execute();
    $product = collect($this->cart['items'])->where('uuid', $this->productUuid)->first();
    $this->itemsInCart = $product['quantity'] ?? 1;
  }

  public function addToCart($quantity)
  {
    $this->cart = (new GetCart())->execute();
    $product   = (new FindProduct())->execute($this->productUuid);
    $cartItems = collect($this->cart['items']);
    $cartItem  = $cartItems->where('uuid', $product->uuid)->first();
    if ($cartItem)
    {
      if ($quantity == 0)
      {
        $this->removeFromCart($product);
      }
      else
      {
        $cartItem['quantity'] = $quantity > $product->stock ? $product->stock : $quantity;
        $this->itemsInCart = $cartItem['quantity'];
        $this->updateCartItem($cartItem);
      }
    } 
    else
    {
      if ($quantity > 0)
      {
        $this->addItemToCart($product, $quantity);
      }
    }

    $this->updateCartTotal();
  }

  public function updateCartTotal()
  {
    $this->cart['total'] = collect($this->cart['items'])->sum(function ($item) {
      return $item['price'] * $item['quantity'];
    });

    (new StoreCart())->execute($this->cart);
    $this->dispatch('cart-updated');
  }

  private function removeFromCart($product)
  {
    $this->cart['items'] = collect($this->cart['items'])
      ->reject(function ($item) use ($product) {
        return $item['uuid'] == $product->uuid;
      })
      ->values()
      ->all();

    $this->cart['quantity']--;
    if ($this->cart['quantity'] == 0)
    {
      (new DestroyCart())->execute();
    }
  }

  private function updateCartItem($cartItem)
  {
    $this->cart['items'] = collect($this->cart['items'])
      ->map(function ($item) use ($cartItem) {
        return $item['uuid'] == $cartItem['uuid'] ? $cartItem : $item;
      })
      ->values()
      ->all();
  }

  private function addItemToCart($product, $quantity)
  {
    $quantity = $quantity > $product->stock ? $product->stock : $quantity;

    $this->cart['items'][] = [
      'uuid' => $product->uuid,
      'title' => $product->title,
      'isbn' => $product->isbn,
      'state' => $product->state->value,
      'price' => $product->price,
      'shipping' => $product->state->value == 'deliverable' ? $product->shipping : NULL,
      'quantity' => $quantity,
      'image' => $product->image,
    ];
    $this->cart['quantity']++;
  }

  public function render()
  {
    return view('livewire.cart-button');
  }
}