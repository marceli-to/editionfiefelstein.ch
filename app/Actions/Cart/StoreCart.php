<?php
namespace App\Actions\Cart;

class StoreCart
{
  public function execute($cart)
  {
    return session()->put('cart', $cart);
  }
}