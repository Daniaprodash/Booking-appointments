<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorMedicalRecordController extends Controller
{
    protected function currentDoctor(): ?Doctor
    {
        if (Auth::user()?->role !== 'doctor') {
            return null;
        }

        return Doctor::where('user_id', Auth::id())->first();
    }

    /**
     * @return array{0: Doctor, 1: User}|null
     */
    protected function resolveDoctorAndPatient(int $patientId): ?array
    {
        $doctor = $this->currentDoctor();
        if (! $doctor) {
            return null;
        }

        $patient = User::find($patientId);
        if (! $patient || $patient->role !== 'user') {
            return null;
        }

        $hasAppointment = $patient->appointments()->where('doctor_id', $doctor->id)->exists();
        if (! $hasAppointment) {
            return null;
        }

        return [$doctor, $patient];
    }

    public function show(int $id)
    {
        $resolved = $this->resolveDoctorAndPatient($id);
        if (! $resolved) {
            return redirect()->route('showMedicalRecords')->with('error', 'غير مصرح لك بعرض هذا الملف أو المريض غير مرتبط بمواعيدك.');
        }

        /** @var Doctor $doctor */
        /** @var User $patient */
        [$doctor, $patient] = $resolved;

        $medicalRecord = DB::table('medical_records')->where('user_id', $patient->id)->first();
        if ($medicalRecord === null) {
            $medicalRecord = (object) [
                'chronic_diseases' => null,
                'allergies' => null,
                'current_medications' => null,
                'past_surgeries' => null,
                'dental_history' => null,
                'notes' => null,
            ];
        }

        $visits = DB::table('visits')
            ->where('user_id', $patient->id)
            ->orderByDesc('visit_date')
            ->orderByDesc('id')
            ->get();

        return view('doctor.patient.medical_record', compact('patient', 'doctor', 'medicalRecord', 'visits'));
    }

    public function update(Request $request, int $id)
    {
        $resolved = $this->resolveDoctorAndPatient($id);
        if (! $resolved) {
            return redirect()->route('showMedicalRecords')->with('error', 'غير مصرح لك بتحديث هذا الملف.');
        }

        /** @var User $patient */
        [, $patient] = $resolved;

        $validated = $request->validate([
            'chronic_diseases' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'current_medications' => ['nullable', 'string'],
            'past_surgeries' => ['nullable', 'string'],
            'dental_history' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $payload = [
            'chronic_diseases' => $validated['chronic_diseases'] ?? null,
            'allergies' => $validated['allergies'] ?? null,
            'current_medications' => $validated['current_medications'] ?? null,
            'past_surgeries' => $validated['past_surgeries'] ?? null,
            'dental_history' => $validated['dental_history'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'updated_at' => now(),
        ];

        $exists = DB::table('medical_records')->where('user_id', $patient->id)->exists();

        if ($exists) {
            DB::table('medical_records')->where('user_id', $patient->id)->update($payload);
        } else {
            $payload['user_id'] = $patient->id;
            $payload['created_at'] = now();
            DB::table('medical_records')->insert($payload);
        }

        return redirect()
            ->route('doctor.record.show', $patient->id)
            ->with('success', 'تم حفظ السجل الطبي بنجاح.');
    }
}
