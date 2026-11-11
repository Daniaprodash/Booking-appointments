<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'specialty' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'bio'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'doctor',
            'phone_number' => $request->phone,
        ]);

        Doctor::create([
            'user_id'   => $user->id,
            'specialty' => $request->specialty,
            'bio'       => $request->bio,
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'تم إضافة الطبيب بنجاح');
    }

    public function show($id)
    {
        $doctor = Doctor::with('user', 'services')->findOrFail($id);
        $appointmentsCount = \App\Models\Appointment::where('doctor_id', $id)->count();
        $patientsCount = \App\Models\Appointment::where('doctor_id', $id)->distinct('user_id')->count('user_id');
        return view('admin.doctors.show', compact('doctor', 'appointmentsCount', 'patientsCount'));
    }

    public function edit($id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $doctor->user->id,
            'password'  => 'nullable|min:6',
            'specialty' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'bio'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $doctor->user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone_number' => $request->phone,
        ]);

        if ($request->filled('password')) {
            $doctor->user->update(['password' => Hash::make($request->password)]);
        }

        $doctor->update([
            'specialty' => $request->specialty,
            'bio'       => $request->bio,
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'تم تحديث بيانات الطبيب بنجاح');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $user = $doctor->user;

        // حذف المواعيد المرتبطة
        \App\Models\Appointment::where('doctor_id', $id)->delete();

        $doctor->delete();

        if ($user && $user->role === 'doctor') {
            $user->delete();
        }

        return redirect()->route('admin.doctors.index')
            ->with('success', 'تم حذف الطبيب وجميع بياناته');
    }
}
