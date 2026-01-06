<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\On; 
use App\Actions\Cart\GetCart;

class CartIcon extends Component
{
  public $cartItemCount = 0;

  public function mount()
  {
    $this->updateCartItemCount();
  }

  #[On('cart-updated')]
  public function updateCartItemCount()
  {
    $cart = (new GetCart())->execute();
    $this->cartItemCount = $cart['quantity'] ?? 0;
  }

  public function render()
  {
    return view('livewire.cart-icon');
  }
}