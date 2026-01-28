<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Actions\Cart\GetCart;
use App\Actions\Cart\CalculateShipping;

class CartTotal extends Component
{
  public $cart;
  public $total;
  public $shipping;

  public function mount()
  {
    $this->cart = (new GetCart())->execute();
    $this->setTotal();
  }

  #[On('cart-updated')]
  public function setTotal()
  {
    $this->total = 0;
    $this->shipping = 0;
    $this->cart = (new GetCart())->execute();

    if ($this->cart && !empty($this->cart['items']))
    {
      $totalQuantity = 0;
      $hasDeliverableItems = false;

      foreach ($this->cart['items'] as $item)
      {
        $this->total += $item['price'] * $item['quantity'];
        if (isset($item['state']) && $item['state'] === 'deliverable') {
          $totalQuantity += $item['quantity'];
          $hasDeliverableItems = true;
        }
      }

      if ($hasDeliverableItems) {
        $this->shipping = (new CalculateShipping())->execute($totalQuantity);
        $this->total += $this->shipping;
      }
    }
  }

  public function render()
  {
    return view('livewire.cart-total');
  }
}
