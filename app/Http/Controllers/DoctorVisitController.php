<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorVisitController extends Controller
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

    public function store(Request $request, int $id)
    {
        $resolved = $this->resolveDoctorAndPatient($id);
        if (! $resolved) {
            return redirect()->route('showMedicalRecords')->with('error', 'غير مصرح لك بإضافة زيارة لهذا المريض.');
        }

        /** @var Doctor $doctor */
        /** @var User $patient */
        [$doctor, $patient] = $resolved;

        $validated = $request->validate([
            'visit_date' => ['required', 'date'],
            'diagnosis' => ['required', 'string'],
            'treatment' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ], [
            'visit_date.required' => 'تاريخ الزيارة مطلوب.',
            'visit_date.date' => 'تاريخ الزيارة غير صالح.',
            'diagnosis.required' => 'التشخيص مطلوب.',
        ]);

        DB::table('visits')->insert([
            'user_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'visit_date' => $validated['visit_date'],
            'diagnosis' => $validated['diagnosis'],
            'treatment' => $validated['treatment'] ?? null,
            'next_plan' => $validated['notes'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('doctor.record.show', $patient->id)
            ->with('success', 'تم إضافة الزيارة بنجاح.');
    }

    public function update(Request $request, int $visitId)
    {
        $doctor = $this->currentDoctor();
        if (! $doctor) {
            return redirect()->route('dashboard')->with('error', 'غير مصرح لك بهذه الصفحة.');
        }

        $visit = DB::table('visits')->where('id', $visitId)->first();
        if (! $visit) {
            return redirect()->route('showMedicalRecords')->with('error', 'الزيارة غير موجودة.');
        }

        $resolved = $this->resolveDoctorAndPatient((int) $visit->user_id);
        if (! $resolved) {
            return redirect()->route('showMedicalRecords')->with('error', 'غير مصرح لك بتعديل هذه الزيارة.');
        }

        /** @var User $patient */
        [, $patient] = $resolved;

        $validated = $request->validate([
            'visit_date' => ['required', 'date'],
            'diagnosis' => ['required', 'string'],
            'treatment' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ], [
            'visit_date.required' => 'تاريخ الزيارة مطلوب.',
            'visit_date.date' => 'تاريخ الزيارة غير صالح.',
            'diagnosis.required' => 'التشخيص مطلوب.',
        ]);

        DB::table('visits')->where('id', $visitId)->update([
            'visit_date' => $validated['visit_date'],
            'diagnosis' => $validated['diagnosis'],
            'treatment' => $validated['treatment'] ?? null,
            'next_plan' => $validated['notes'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('doctor.record.show', $patient->id)
            ->with('success', 'تم تحديث الزيارة بنجاح.');
    }
}
