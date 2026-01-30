@extends('layouts.app')
@section('title', 'تسجيل حساب')
@section('content')
 <main class="register-hero">
    <section class="register-copy">
        <p class="pre-title">ابدأ رحلتك نحو ابتسامة صحية</p>
        <h1>انضم إلى <span>عائلة عياداتنا الآن</span></h1>
        <p>
            أنشئ حسابك خلال دقائق واحجز موعدك مع أفضل أطباء الأسنان في المنطقة.
            واجهة الاستخدام البسيطة لدينا تساعدك على تحديد الوقت المناسب ومتابعة حجوزاتك بسهولة.
        </p>
        <ul class="benefits">
            <li>مواعيد فورية مع تذكير عبر البريد</li>
            <li>إدارة كاملة لسجل زياراتك وخدماتك</li>
            <li>دعم متواصل على مدار الساعة</li>
        </ul>
    </section>

    <section class="register-form-card" aria-labelledby="register-title">
        <form method="POST" action="{{ route('auth.register') }}">
            @csrf
            <h2 id="register-title" class="register-title">
                <span><i class="fas fa-user-plus"></i></span>
                إنشاء حساب جديد
            </h2>

            <div class="floating-group">
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder=" " required>
                <label for="name">الاسم الكامل</label>
                @error('name') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <div class="floating-group">
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder=" " required>
                <label for="email">البريد الإلكتروني</label>
                @error('email') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <div class="floating-group">
                <input type="password" name="password" id="password" placeholder=" " minlength="6" required>
                <label for="password">كلمة المرور</label>
                @error('password') <p class="error-text">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="register-submit">إنشاء الحساب</button>

            <p class="auth-footer">
                لديك حساب مسبقاً؟
                <a href="{{ route('auth.login') }}">تسجيل الدخول</a>
            </p>
        </form>
    </section>
 </main>
@endsection