<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

    // cancel appointment (by user)
    public function cancel($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('dashboard')
            ->with('success', 'تم إلغاء الموعد بنجاح.');
    }

    // confirm appointment (by doctor)
    public function confirm($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->firstOrFail();

        $appointment->update(['status' => 'confirmed']);

        return redirect()->route('doctorDashboard')
            ->with('success', 'تم تأكيد الموعد بنجاح.');
    }

    // delete appointment (by doctor, only if cancelled or rejected)
    public function delete($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->whereIn('status', ['cancelled', 'rejected'])
            ->firstOrFail();

        $appointment->delete();

        return redirect()->route('doctorDashboard')
            ->with('success', 'تم حذف الموعد بنجاح.');
    }

    // reject appointment (by doctor, only if pending)
    public function reject($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $appointment->update(['status' => 'rejected']);

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

        $appointment = Appointment::where('id', $id)
            ->where('doctor_id', Auth::id())
            ->with(['user', 'doctor'])
            ->firstOrFail();

            
            return redirect()->route('doctorDashboard')
                ->with('success', 'تم إرسال البريد للمريض بنجاح.');
       
    }
  
}
