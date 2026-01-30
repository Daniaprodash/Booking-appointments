<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    // get service
   public function getAllServices()
  {
    $services = Service::all();
    return view('index', compact('services'));
  }
}
