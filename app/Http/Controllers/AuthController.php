<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  // show login
  public function showLogin()
  {
      return view('auth.login');
  }

//login processing
   public function loginProcessing(Request $request){
    $validator= Validator::make($request->all() , [
         'email' => 'required|email',
         'password' => 'required|min:6',
    ] , [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
    ]);
      if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }
        

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
           // التحقق من الدور (role)
        $user = Auth::user();
        if ($user->role === 'doctor') {
            return redirect()->intended(route('doctorDashboard'));
        } elseif ($user->role === 'user') {
            return redirect()->intended(route('dashboard'));
        } else {
            // في حال كان الدور غير معروف
            Auth::logout();
            return redirect()->back()->withErrors([
                'role' => 'لا يوجد صلاحية مناسبة لهذا الحساب',
            ]);
        }

        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ])->withInput($request->except('password'));
   }  
//end login processing

// show register
  public function showRegister()
  {
      return view('auth.register');
  }
  
// register processing
  public function registerProcessing(Request $request)
  {
    $validator= Validator::make($request->all() , [
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6',
    ]);
  
   if ($validator->fails()) {
    return redirect()->back()
      ->withErrors($validator)
      ->withInput($request->except('password'));
   }
   $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
   ]);
   // تسجيل دخول تلقائي بعد إنشاء الحساب
   Auth::login($user);
   $request->session()->regenerate();
   return redirect()->intended(route('index'));
  }  
// end register processing

// logout
  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('index')->with('success', 'تم تسجيل الخروج بنجاح');
  }
// end logout

}
