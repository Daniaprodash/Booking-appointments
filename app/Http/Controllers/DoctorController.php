<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    // get doctors
    public function getAllDoctors()
  {
    $doctors = Doctor::all();
    return view('index', compact('doctors'));
  }
}
