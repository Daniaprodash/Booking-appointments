<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'doctor.user', 'service']);

        // فلترة حسب الطبيب
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // فلترة حسب المريض
        if ($request->filled('patient_id')) {
            $query->where('user_id', $request->patient_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20)
            ->withQueryString();

        $doctors = Doctor::with('user')->get();
        $patients = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.appointments.index', compact('appointments', 'doctors', 'patients'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,rejected,cancelled,completed',
        ]);

        $appointment->update(['status' => $request->status]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'تم تحديث حالة الموعد بنجاح');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'تم حذف الموعد بنجاح');
    }
}
