<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $doctorsCount = Doctor::count();
        $patientsCount = User::where('role', 'user')->count();
        $appointmentsCount = Appointment::count();
        $servicesCount = Service::count();

        $appointmentsByStatus = [
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'rejected' => Appointment::where('status', 'rejected')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        $upcomingAppointments = Appointment::with(['user', 'doctor.user', 'service'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'doctorsCount',
            'patientsCount',
            'appointmentsCount',
            'servicesCount',
            'appointmentsByStatus',
            'upcomingAppointments'
        ));
    }
}
