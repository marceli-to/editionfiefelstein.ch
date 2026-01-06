<?php
namespace App\Actions\Cart;

class DestroyCart
{
  public function execute()
  {
    session()->forget('cart');
  }
}