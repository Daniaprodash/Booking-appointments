@extends('layouts.app')
@section('title','تسجيل الدخول')

@section('content')
<main class="auth-layout">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="brand">
                    <i class="fas fa-sign-in-alt"></i>
                    تسجيل دخول
                </div>
                <h2>مرحباً بك</h2>
                <p class="helper">سجّل الدخول إلى حسابك للمتابعة</p>
            </div>

            @if (session('status'))
                <div class="alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{route('auth.login')}}" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autofocus
                           class="@error('email') invalid @enderror" placeholder="example@domain.com">
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="input-group">
                        <input id="password" type="password" name="password" required
                               class="@error('password') invalid @enderror" placeholder="••••••••">
                        
                    </div>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        تذكرني
                    </label>
                    <a href="" class="link">نسيت كلمة المرور؟</a>
                </div>

                <button type="submit" class="btn-primary">تسجيل الدخول</button>

                <p class="helper text-center">ليس لديك حساب؟ <a href="{{route('auth.register')}}" class="link">تسجيل حساب جديد</a></p>

                @if ($errors->any())
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </form>

            <hr>

            <div class="social-login">
                <p class="helper">أو سجّل الدخول عبر</p>
                <div class="social-buttons">
                    <a href="">Google</a>
                    <a href="">Facebook</a>
                </div>
            </div>
            
        </div>
    </div>
</main>


@endsection