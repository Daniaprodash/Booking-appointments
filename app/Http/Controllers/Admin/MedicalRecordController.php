<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $records = DB::table('medical_records')
            ->join('users', 'medical_records.user_id', '=', 'users.id')
            ->select('medical_records.*', 'users.name as patient_name', 'users.email as patient_email')
            ->orderBy('medical_records.updated_at', 'desc')
            ->paginate(15);

        return view('admin.medical-records.index', compact('records'));
    }

    public function show($id)
    {
        $record = DB::table('medical_records')
            ->join('users', 'medical_records.user_id', '=', 'users.id')
            ->select('medical_records.*', 'users.name as patient_name', 'users.email as patient_email',
                'users.phone_number', 'users.gender', 'users.date_of_birth')
            ->where('medical_records.id', $id)
            ->first();

        if (!$record) {
            return redirect()->route('admin.medical-records.index')
                ->with('error', 'السجل الطبي غير موجود');
        }

        $visits = DB::table('visits')
            ->where('visits.user_id', $record->user_id)
            ->join('doctors', 'visits.doctor_id', '=', 'doctors.id')
            ->join('users as doctor_users', 'doctors.user_id', '=', 'doctor_users.id')
            ->select('visits.*', 'doctor_users.name as doctor_name')
            ->orderBy('visit_date', 'desc')
            ->get();

        return view('admin.medical-records.show', compact('record', 'visits'));
    }

    public function destroy($id)
    {
        DB::table('medical_records')->where('id', $id)->delete();

        return redirect()->route('admin.medical-records.index')
            ->with('success', 'تم حذف السجل الطبي');
    }
}
