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
use Illuminate\Support\Facades\DB;
class AppController extends Controller
{
  // get data from DB
 public function getIndexData()
 {
    $services = Service::all();
    $doctors = Doctor::with('user')->get();
    $testimonials = Testimonial::all();
    return view('public.index', compact('services' , 'doctors', 'testimonials'));
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
       return view('patient.dashboard', compact('doctors', 'services', 'appointments' , 'messages'));
   }


  // عرض الملف الطبي للمريض
  public function patientMedicalRecord()
  {
      $user = Auth::user();
      $medicalRecord = DB::table('medical_records')->where('user_id', $user->id)->first();
      $visits = DB::table('visits')
          ->where('visits.user_id', $user->id)
          ->join('doctors', 'visits.doctor_id', '=', 'doctors.id')
          ->join('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id')
          ->select('visits.*', 'doctor_users.name as doctor_name')
          ->orderBy('visit_date', 'desc')
          ->get();
      return view('patient.medical-record', compact('user', 'medicalRecord', 'visits'));
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
    $patientCount = Appointment::where('doctor_id', $doctorId)->distinct('user_id')->count('user_id');

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
    return view('doctor.dashboard', compact('activities','appointments', 'doctor', 'showAll', 'allAppointmentsCount', 'percentage', 'calendarEvents', 'patientCount'));
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

    return view('public.index', compact('doctors', 'services'));
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

      return view('doctor.medical-records.index', compact('doctor', 'patients'));
  }

 //   show payment page
public function showPaymentPage()
{
    return view('doctor.payments');
}
 //   show settings page
public function showSettingsPage()
{
    return view('doctor.settings');
}

 // عرض إشعارات الطبيب
public function notifications()
{
    $doctor = Doctor::where('user_id', Auth::id())->first();
    if (!$doctor) {
        return redirect()->route('dashboard')->with('error', 'لم يتم العثور على ملف الطبيب.');
    }

    $activities = Activity::where('doctor_id', $doctor->id)
        ->with(['user', 'appointment'])
        ->latest()
        ->paginate(20);

    $unreadCount = Activity::where('doctor_id', $doctor->id)
        ->where('is_read', false)
        ->count();

    return view('doctor.notifications', compact('doctor', 'activities', 'unreadCount'));
}

 // تمييز إشعار كمقروء
public function markNotificationAsRead($id)
{
    $doctor = Doctor::where('user_id', Auth::id())->first();
    if (!$doctor) {
        return redirect()->route('dashboard')->with('error', 'غير مصرح لك بهذه الصفحة.');
    }

    $activity = Activity::where('doctor_id', $doctor->id)->findOrFail($id);
    $activity->update(['is_read' => true]);

    return redirect()->back()->with('success', 'تم تمييز الإشعار كمقروء');
}

 // تمييز كل الإشعارات كمقروءة
public function markAllNotificationsAsRead()
{
    $doctor = Doctor::where('user_id', Auth::id())->first();
    if (!$doctor) {
        return redirect()->route('dashboard')->with('error', 'غير مصرح لك بهذه الصفحة.');
    }

    Activity::where('doctor_id', $doctor->id)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return redirect()->back()->with('success', 'تم تمييز جميع الإشعارات كمقروءة');
}

}


