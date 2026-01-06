<?php
namespace App\Http\Controllers;

class LandingController extends Controller
{
  /**
   * Shows the landing page
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('pages.landing');
  }
}
