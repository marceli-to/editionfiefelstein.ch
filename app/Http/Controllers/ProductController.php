<?php
namespace App\Http\Controllers;
use App\Actions\Product\GetProduct;
use App\Models\Product;

class ProductController extends Controller
{
  public function show(Product $product)
  {
    return view('pages.product.show', [
      'product' => (new GetProduct())->execute($product),
    ]);
  }
}
