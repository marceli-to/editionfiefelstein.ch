<?php
namespace App\Http\Controllers;
use App\Models\Product;

class LandingController extends Controller
{
  /**
   * Shows the landing page
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $product = Product::first();
    return view('pages.landing', compact('product'));
  }
}
