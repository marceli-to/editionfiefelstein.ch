<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\On; 
use App\Actions\Cart\GetCart;
use App\Actions\Cart\StoreCart;
use App\Actions\Cart\DestroyCart;

class Cart extends Component
{
  public $cart;
  public $showCart = false;

  public function mount()
  {
    $this->cart = (new GetCart())->execute();
  }

  #[On('cart-updated')]
  public function updateCart()
  {
    $this->cart = (new GetCart())->execute();

    if ($this->cart['quantity'] == 0)
    {
      $this->dispatch('hide-updated-cart');
      $this->showCart = false;
      (new DestroyCart())->execute();
    }
    else {
      $this->dispatch('display-updated-cart');
      $this->showCart = true;
    }
  }

  public function removeCartItem($productUuid)
  {
    $this->cart = (new GetCart())->execute();
    $this->cart['items'] = collect($this->cart['items'])
      ->reject(function ($item) use ($productUuid) {
        return $item['uuid'] == $productUuid;
      })
      ->values()
      ->all();
    
    // update cart quantity
    $this->cart['quantity']--;

    // update cart total
    $this->cart['total'] = collect($this->cart['items'])->sum(function ($item) {
      return $item['quantity'] * $item['price'];
    });

    (new StoreCart())->execute($this->cart);
    $this->dispatch('cart-updated');
  }

  public function render()
  {
    return view('livewire.cart');
  }
}
