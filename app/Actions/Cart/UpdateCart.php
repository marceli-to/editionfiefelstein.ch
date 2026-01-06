<?php
namespace App\Actions\Cart;
use App\Actions\Cart\GetCart;
use App\Actions\Cart\StoreCart;

class UpdateCart
{
  public function execute($attributes = [])
  {
    $cart = (new GetCart())->execute();
    (new StoreCart())->execute(
      array_merge($cart, $attributes)
    );
    return (new GetCart())->execute();
  }
}