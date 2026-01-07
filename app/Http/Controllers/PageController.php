<?php
namespace App\Http\Controllers;
use App\Models\ContactPage;
use App\Models\AboutPage;

class PageController extends Controller
{
  public function contact()
  {
    return view('pages.contact', [
      'data' => ContactPage::first()
    ]);
  }

  public function about()
  {
    return view('pages.about', [
      'data' => AboutPage::first()
    ]);
  }
}