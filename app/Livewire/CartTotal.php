<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\On; 
use App\Actions\Cart\GetCart;

class CartTotal extends Component
{
  public $cart;
  public $total;

  public function mount()
  {
    $this->cart = (new GetCart())->execute();
    $this->setTotal();
  }

  #[On('cart-updated')]
  public function setTotal()
  {
    $this->total = 0;
    $this->cart = (new GetCart())->execute();

    if ($this->cart)
    {
      foreach ($this->cart['items'] as $item)
      {
        $this->total += ($item['price'] + $item['shipping']) * $item['quantity'];
      }
    }
  }

  public function render()
  {
    return view('livewire.cart-total');
  }
}
