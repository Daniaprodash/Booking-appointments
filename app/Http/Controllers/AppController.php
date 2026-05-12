<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Activity;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;
use App\Models\DoctorPatientEmail;
class AppController extends Controller
{
  // get data from DB
 public function getIndexData()
 {
    $services = Service::all();
    $doctors = Doctor::with('user')->get();
    $testimonials = Testimonial::all();
    return view('index', compact('services' , 'doctors', 'testimonials'));
 }

  // view user dashboard page
  public function index()
  {
      $doctors = Doctor::all();
      $services = Service::all();
      $user = auth()->user();
      $messages = DoctorPatientEmail::where('user_id', $user->id)
      ->with('doctor.user') // لجلب اسم الطبيب من جدول users
      ->latest()
      ->get();

      $appointments = Appointment::where('user_id', Auth::id())
          ->with(['doctor', 'service'])
          ->orderBy('appointment_date', 'desc')
          ->orderBy('appointment_time', 'desc')
          ->get();
      
       
      return view('userDashboard', compact('doctors', 'services', 'appointments' , 'messages'));
  }

  // view doctor dashboard page
  public function doctorDashboard(Request $request)
  {
    $doctor = Doctor::where('user_id', Auth::id())->first();
    if (!$doctor) {
        return redirect()->route('dashboard')->with('error', 'لم يتم العثور على ملف الطبيب.');
    }

    $doctorId = $doctor->id;
    $query = Appointment::where('doctor_id', $doctorId)
            ->with(['user', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc');

    // عرض آخر 3 مواعيد فقط إذا لم يتم طلب عرض الكل
    $showAll = $request->has('show_all');
    $appointments = $showAll ? $query->get() : $query->limit(3)->get();
    $allAppointmentsCount = Appointment::where('doctor_id', $doctorId)->count();

    // نسبة المواعيد المؤكدة (doctor_id في الجدول هو doctors.id)
    $confirmed = Appointment::where('doctor_id', $doctorId)
        ->where('status', 'confirmed')
        ->count();
    $total = Appointment::where('doctor_id', $doctorId)->count();
    $percentage = $total > 0 ? round(($confirmed / $total) * 100, 2) : 0;

    // calendarEvents
    $calendarEvents = Appointment::where('doctor_id', $doctorId)
        ->with(['user', 'service'])
        ->get()
        ->map(function ($a) {
            $statusColors = [
                'pending'   => '#ffb86b',
                'confirmed' => '#0fb3a1',
                'rejected'  => '#ee5a52',
                'cancelled' => '#8a93ad',
            ];
            $time = \Carbon\Carbon::parse($a->appointment_time)->format('H:i');
            return [
                'id'               => $a->id,
                'title'            => ($a->user?->name ?? 'مريض') . ' - ' . ($a->service?->name ?? 'موعد'),
                'start'            => $a->appointment_date . 'T' . $time,
                'backgroundColor'  => $statusColors[$a->status] ?? '#7c5cff',
                'borderColor'      => $statusColors[$a->status] ?? '#7c5cff',
            ];
        })
        ->values()
        ->toArray();

        $activities = Activity::where('doctor_id', $doctorId)
                     ->latest()
                     ->limit(5)
                     ->get();
    return view('doctorDashboard', compact('activities','appointments', 'doctor', 'showAll', 'allAppointmentsCount', 'percentage', 'calendarEvents'));
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

 // showMedicalRecords — قائمة المرضى الخاصين بالطبيب
  public function showMedicalRecords()
  {
      $doctor = Doctor::where('user_id', Auth::id())->first();
      if (!$doctor) {
          return redirect()->route('dashboard')->with('error', 'لم يتم العثور على ملف الطبيب.');
      }

      $patients = User::whereHas('appointments', function ($q) use ($doctor) {
          $q->where('doctor_id', $doctor->id);
      })->orderBy('name')->get();

      return view('showMedicalRecords', compact('doctor', 'patients'));
  }

//   show payment page
public function showPaymentPage()
{
    return view('payments');
}
//   show settings page
public function showSettingsPage()
{
    return view('settings');
}

}


