<?php
namespace App\Actions\Cart;

class CalculateShipping
{
  public function execute(int $quantity): float
  {
    if ($quantity <= 0) {
      return 0;
    }

    if ($quantity <= 2) {
      return 10.00;
    }

    if ($quantity <= 5) {
      return 20.00;
    }

    if ($quantity <= 8) {
      return 30.00;
    }

    return 40.00;
  }
}
