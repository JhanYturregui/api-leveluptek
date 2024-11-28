<?php

namespace App\Http\Controllers;

use App\Models\Local;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\View\View
   */
  public function index()
  {
    $pageTitle = 'Inicio';
    $hideSecondHeader = false;
    return view('dashboard', compact('pageTitle', 'hideSecondHeader'));
  }
}
