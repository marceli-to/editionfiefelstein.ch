<?php
namespace App\Http\Controllers;
use App\Models\ContactPage;

class PageController extends Controller
{
  public function contact()
  {
    return view('pages.contact', [
      'data' => ContactPage::first()
    ]);
  }
}