<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientProfileController extends Controller
{
    /**
     * Show the patient profile edit form (personal information only).
     */
    public function edit()
    {
        $user = auth()->user();

        if ($user->role !== 'user') {
            return redirect()->route('dashboard')->with('error', 'هذه الصفحة مخصصة للمرضى فقط.');
        }

        return view('patient.profile.edit', compact('user'));
    }

    /**
     * Update the authenticated patient's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'user') {
            return redirect()->route('dashboard')->with('error', 'غير مصرح لك بهذه العملية.');
        }

        $request->merge([
            'phone_number' => $request->filled('phone_number') ? $request->input('phone_number') : null,
            'address' => $request->filled('address') ? $request->input('address') : null,
            'date_of_birth' => $request->filled('date_of_birth') ? $request->input('date_of_birth') : null,
            'gender' => $request->filled('gender') ? $request->input('gender') : null,
            'emergency_contact' => $request->filled('emergency_contact') ? $request->input('emergency_contact') : null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ], [
            'name.required' => 'الاسم مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل.',
            'gender.in' => 'يرجى اختيار ذكر أو أنثى.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->date_of_birth = $validated['date_of_birth'] ?? null;
        $user->gender = $validated['gender'] ?? null;
        $user->emergency_contact = $validated['emergency_contact'] ?? null;

        if ($request->hasFile('image')) {
            $dir = public_path('assets/images/profiles');
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            if ($user->image) {
                $oldPath = public_path($user->image);
                if (is_string($user->image) && $oldPath && file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('image');
            $name = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            $user->image = 'assets/images/profiles/' . $name;
        }

        $user->save();

        return redirect()->back()->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}
