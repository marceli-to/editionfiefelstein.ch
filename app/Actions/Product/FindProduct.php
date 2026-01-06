<?php
namespace App\Actions\Product;
use App\Models\Product;

class FindProduct
{
  public function execute($uuid)
  {
    return Product::where('uuid', $uuid)->first();
  }
}