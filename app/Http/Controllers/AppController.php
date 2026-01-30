<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;
class AppController extends Controller
{
  // get data from DB
    public function getIndexData()
  {
    $services = Service::all();
    $doctors = Doctor::all();
    $testimonials = Testimonial::all();
    return view('index', compact('services' , 'doctors', 'testimonials'));
  }

  // view user dashboard page
  public function index()
  {
      $doctors = Doctor::all();
      $services = Service::all();
      $appointments = Appointment::where('user_id', Auth::id())
          ->with(['doctor', 'service'])
          ->orderBy('appointment_date', 'desc')
          ->orderBy('appointment_time', 'desc')
          ->get();

      return view('userDashboard', compact('doctors', 'services', 'appointments'));
  }

  // view doctor dashboard page
  public function doctorDashboard(Request $request){
    $query = Appointment::where('doctor_id', Auth::id())
            ->with(['user', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc');
    
    // عرض آخر 3 مواعيد فقط إذا لم يتم طلب عرض الكل
    $showAll = $request->has('show_all');
    $appointments = $showAll ? $query->get() : $query->limit(3)->get();
    $allAppointmentsCount = Appointment::where('doctor_id', Auth::id())->count();
    $doctor = Doctor::find(Auth::id());

    // rate
    $confirmed = Appointment::where('doctor_id', $doctor)
                           ->where('status', 'confirmed')
                           ->count();

    $total = Appointment::where('doctor_id', $doctor)->count();

    $percentage = $total > 0 ? round(($confirmed / $total) * 100, 2) : 0;
    return view('doctorDashboard', compact('appointments', 'doctor', 'showAll', 'allAppointmentsCount' , 'percentage'));
  }

  // get testimonials
  public function getTestimonials()
  {
    $testimonials = Testimonial::all();
    return view('testimonials', compact('testimonials'));
  }

  //search
  public function appsearch(Request $request)
{
    $keyword = $request->input('keyword');

    // بحث الأطباء (الاسم من users + التخصص من doctors)
    $doctors = Doctor::when($keyword, function ($query, $keyword) {
        $query->whereHas('user', function ($q) use ($keyword) {
            $q->where('name', 'like', "%$keyword%");
        })
        ->orWhere('specialty', 'like', "%$keyword%");
    })->get();

    // بحث الخدمات
    $services = Service::when($keyword, function ($query, $keyword) {
        $query->where('title', 'like', "%$keyword%")
              ->orWhere('description', 'like', "%$keyword%");
    })->get();

    return view('index', compact('doctors', 'services'));
}

}


