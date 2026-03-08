@extends('layouts.app')
@section('title', 'تحرير الملف الشخصي')
@section('content')
    <div class="doctor-dashboard-container" role="main" style="max-width:720px;margin:0 auto;padding:28px">
        <div style="margin-bottom:24px">
            <a href="{{ route('doctorDashboard') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--primary-2);text-decoration:none;font-weight:600">
                <i class="fas fa-arrow-right"></i>
                العودة للوحة التحكم
            </a>
        </div>

        <div class="doctor-dashboard-card" style="padding:28px">
            <h4 style="margin:0 0 24px;color:var(--accent-contrast)">تحرير الملف الشخصي</h4>

            @if($errors->any())
                <div style="background:linear-gradient(90deg,#ff6b6b,#ee5a52);color:#fff;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:0.9rem">
                    <ul style="margin:0;padding-right:20px">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="display:flex;gap:24px;align-items:flex-start;flex-wrap:wrap;margin-bottom:20px">
                    <div style="flex-shrink:0">
                        <label style="display:block;margin-bottom:8px;font-weight:600;color:var(--accent-contrast)">الصورة الحالية</label>
                        <img src="{{ $user->image ? asset($user->image) : asset('assets/images/default-doctor.jpg') }}" alt="صورة الملف" width="120" height="120" style="object-fit:cover;border-radius:12px;border:3px solid rgba(22,225,199,0.2)">
                    </div>
                    <div style="flex:1;min-width:200px">
                        <label for="image" style="display:block;margin-bottom:8px;font-weight:600;color:var(--accent-contrast)">تغيير الصورة (اختياري)</label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="width:100%;padding:10px;border:1px solid rgba(138,147,173,0.2);border-radius:8px;font-size:0.9rem;background:var(--card)">
                    </div>
                </div>

                <div style="margin-bottom:20px">
                    <label for="name" style="display:block;margin-bottom:8px;font-weight:600;color:var(--accent-contrast)">الاسم</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required style="width:100%;padding:12px;border:1px solid rgba(138,147,173,0.2);border-radius:8px;font-size:1rem;outline:none">
                </div>

                <div style="margin-bottom:24px">
                    <label for="email" style="display:block;margin-bottom:8px;font-weight:600;color:var(--accent-contrast)">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required style="width:100%;padding:12px;border:1px solid rgba(138,147,173,0.2);border-radius:8px;font-size:1rem;outline:none">
                </div>

                <div style="display:flex;gap:12px;flex-wrap:wrap">
                    <button type="submit" class="appointment-action-btn" style="background:linear-gradient(90deg,var(--primary-1),var(--primary-2));color:#fff;border:none;padding:12px 24px;border-radius:10px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:8px">
                        <i class="fas fa-save"></i>
                        حفظ التغييرات
                    </button>
                    <a href="{{ route('doctorDashboard') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;border-radius:10px;background:var(--muted);color:#fff;text-decoration:none;font-weight:600">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
