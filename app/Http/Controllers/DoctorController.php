<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\User;

class DoctorController extends Controller
{
    // get doctors
    public function getAllDoctors()
  {
    $doctors = Doctor::all();
    return view('index', compact('doctors'));
  }

    /**
     * Show the form for editing the doctor's profile (user data).
     */
    public function editProfile()
    {
        $user = Auth::user();
        if ($user->role !== 'doctor') {
            return redirect()->route('doctorDashboard')->with('error', 'غير مصرح لك بهذه الصفحة.');
        }
        return view('doctorProfileEdit', compact('user'));
    }

    /**
     * Update the doctor's profile (name, email, image).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'doctor') {
            return redirect()->route('doctorDashboard')->with('error', 'غير مصرح لك بهذه الصفحة.');
        }

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required'  => 'الاسم مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique'   => 'البريد الإلكتروني مستخدم من قبل مستخدم آخر.',
            'image.image'    => 'يجب أن يكون الملف صورة.',
            'image.max'      => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('image')) {
            $dir = public_path('assets/images/profiles');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            if ($user->image) {
                $oldPath = public_path($user->image);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $file = $request->file('image');
            $name = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            $user->image = 'assets/images/profiles/' . $name;
        }

        $user->save();
        return redirect()->route('doctorDashboard')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
        /**الكود يقوم بـ:
      - التأكد من وجود صورة مرفوعة
      - إنشاء مجلد الصور إذا غير موجود
      - حذف الصورة القديمة
      - إنشاء اسم فريد للصورة
      - رفع الصورة للمجلد
      - حفظ اسم الصورة في قاعدة البيانات*/



    /**
     * عرض ملف المريض (للطبيب فقط، والمرضى الذين لديهم مواعيد عنده).
     */
    public function patientFile(User $patient)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'غير مصرح لك بهذه الصفحة.');
        }

        $hasAppointment = $patient->appointments()->where('doctor_id', $doctor->id)->exists();
        if (!$hasAppointment) {
            return redirect()->route('showMedicalRecords')->with('error', 'هذا المريض غير مرتبط بمواعيدك.');
        }

        return view('patientFile', compact('patient', 'doctor'));
    }

    
}
