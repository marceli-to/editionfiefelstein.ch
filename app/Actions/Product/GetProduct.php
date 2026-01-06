<?php
namespace App\Actions\Product;
use App\Models\Product;

class GetProduct
{
  public function execute(Product $product)
  {
    return Product::find($product->id);
  }
}