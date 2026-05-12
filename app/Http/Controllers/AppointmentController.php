<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorPatientEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Events\AppointmentCancelled;
use App\Events\AppointmentConfirmed;
use App\Events\AppointmentDeleted;
use App\Events\AppointmentRejected;

class AppointmentController extends Controller
{
    // store appointment (by user)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ], [
            'doctor_id.required' => 'يجب اختيار الطبيب',
            'doctor_id.exists' => 'الطبيب المحدد غير موجود',
            'service_id.required' => 'يجب اختيار الخدمة',
            'service_id.exists' => 'الخدمة المحددة غير موجودة',
            'appointment_date.required' => 'يجب تحديد تاريخ الموعد',
            'appointment_date.after_or_equal' => 'لا يمكن حجز موعد في تاريخ سابق',
            'appointment_time.required' => 'يجب تحديد وقت الموعد',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Appointment::create([
            'user_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'تم حجز الموعد بنجاح! سيتم تأكيده قريباً.');
    }

    // update appointment (by user)
    public function update(Request $request, $id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($appointment->status === 'cancelled') {
            return redirect()->route('dashboard')
                ->with('error', 'لا يمكن تعديل موعد ملغي.');
        }

        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ], [
            'doctor_id.required' => 'يجب اختيار الطبيب',
            'doctor_id.exists' => 'الطبيب المحدد غير موجود',
            'service_id.required' => 'يجب اختيار الخدمة',
            'service_id.exists' => 'الخدمة المحددة غير موجودة',
            'appointment_date.required' => 'يجب تحديد تاريخ الموعد',
            'appointment_date.after_or_equal' => 'لا يمكن اختيار تاريخ سابق',
            'appointment_time.required' => 'يجب تحديد وقت الموعد',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $appointment->update([
            'doctor_id' => $request->doctor_id,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            // بعد التعديل يرجع الموعد للمراجعة من الطبيب
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'تم تعديل الموعد بنجاح وإرساله للمراجعة.');
    }

    // cancel appointment (by user)
    public function cancel($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['user', 'doctor'])
            ->firstOrFail();

        $appointment->update(['status' => 'cancelled']);

        event(new AppointmentCancelled($appointment));

        return redirect()->route('dashboard')
            ->with('success', 'تم إلغاء الموعد بنجاح.');
    }

    // confirm appointment (by doctor)
    public function confirm($id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->with('user')
            ->firstOrFail();

        $appointment->update(['status' => 'confirmed']);

        event(new AppointmentConfirmed($appointment));

        return redirect()->route('doctorDashboard')
            ->with('success', 'تم تأكيد الموعد بنجاح.');
    }

    // delete appointment (by doctor, only if cancelled or rejected)
    public function delete($id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['cancelled', 'rejected'])
            ->with('user')
            ->firstOrFail();

        event(new AppointmentDeleted($appointment));

        $appointment->delete();

        return redirect()->route('doctorDashboard')
            ->with('success', 'تم حذف الموعد بنجاح.');
    }

    // reject appointment (by doctor, only if pending)
    public function reject($id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();

        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->with('user')
            ->firstOrFail();

        $appointment->update(['status' => 'rejected']);

        event(new AppointmentRejected( $appointment));
        return redirect()->route('doctorDashboard')
            ->with('success', 'تم رفض الموعد بنجاح.');
    }

    // send email to patient (by doctor)
    public function sendEmail(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'subject.required' => 'موضوع البريد مطلوب',
            'message.required' => 'محتوى الرسالة مطلوب',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        // الطبيب الحالي
        $doctor = Doctor::where('user_id', Auth::id())->firstOrFail();
    
        // الموعد
        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->with(['user', 'doctor'])
            ->firstOrFail();
    
        // 🟢 إنشاء سجل الرسالة في قاعدة البيانات
        DoctorPatientEmail::create([
            'user_id'   => $appointment->user_id,      // المريض
            'doctor_id' => $doctor->id,                // الطبيب
            'subject'   => $request->subject,
            'message'   => $request->message,
        ]);
    
        // (اختياري) إرسال بريد فعلي
        // Mail::to($appointment->user->email)->send(new DoctorToPatientMail(...));
    
        return redirect()->route('doctorDashboard')
            ->with('success', 'تم إرسال البريد للمريض بنجاح.');
    }
  
}
