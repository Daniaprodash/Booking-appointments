<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index()
    {
        $patients = User::where('role', 'user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone'    => 'nullable|string|max:20',
            'gender'   => 'nullable|in:male,female',
            'address'  => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'phone_number' => $request->phone,
            'gender'   => $request->gender,
            'address'  => $request->address,
        ]);

        return redirect()->route('admin.patients.index')
            ->with('success', 'تم إضافة المريض بنجاح');
    }

    public function show($id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);
        $appointments = \App\Models\Appointment::where('user_id', $id)
            ->with(['doctor.user', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->get();
        $medicalRecord = \Illuminate\Support\Facades\DB::table('medical_records')
            ->where('user_id', $id)->first();
        return view('admin.patients.show', compact('patient', 'appointments', 'medicalRecord'));
    }

    public function edit($id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $patient->id,
            'password' => 'nullable|min:6',
            'phone'    => 'nullable|string|max:20',
            'gender'   => 'nullable|in:male,female',
            'address'  => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'phone_number' => $request->phone,
            'gender'   => $request->gender,
            'address'  => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $patient->update($data);

        return redirect()->route('admin.patients.index')
            ->with('success', 'تم تحديث بيانات المريض بنجاح');
    }

    public function destroy($id)
    {
        $patient = User::where('role', 'user')->findOrFail($id);

        // حذف المواعيد المرتبطة
        \App\Models\Appointment::where('user_id', $id)->delete();
        \Illuminate\Support\Facades\DB::table('medical_records')->where('user_id', $id)->delete();

        $patient->delete();

        return redirect()->route('admin.patients.index')
            ->with('success', 'تم حذف المريض وجميع بياناته');
    }
}
