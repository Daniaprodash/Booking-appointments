@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div class="container py-4" style="max-width: 640px;">
    <h1 class="h4 mb-4">تعديل الملف الشخصي</h1>

    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    <form action="{{ route('patient.profile.update') }}" method="POST" enctype="multipart/form-data" class="card shadow-sm border-0">
        @csrf
        @method('PUT')

        <div class="card-body p-4">
            <div class="mb-3">
                <label class="form-label" for="name">الاسم</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required autocomplete="name">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" required autocomplete="email">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="phone_number">رقم الهاتف</label>
                <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                       value="{{ old('phone_number', $user->phone_number) }}" autocomplete="tel">
                @error('phone_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="address">العنوان</label>
                <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="date_of_birth">تاريخ الميلاد</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                value="{{ old('date_of_birth', $user->date_of_birth) }}">
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="gender">الجنس</label>
                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="">— اختر —</option>
                    <option value="male" @selected(old('gender', $user->gender) === 'male')>ذكر</option>
                    <option value="female" @selected(old('gender', $user->gender) === 'female')>أنثى</option>
                </select>
                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="emergency_contact">جهة اتصال للطوارئ</label>
                <input type="text" name="emergency_contact" id="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror"
                       value="{{ old('emergency_contact', $user->emergency_contact) }}">
                @error('emergency_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">الصورة الحالية</label>
                <div class="mb-2">
                    <img src="{{ $user->image ? asset($user->image) : asset('assets/images/default-doctor.jpg') }}"
                         alt="صورة الملف الشخصي"
                         class="rounded border"
                         style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <label class="form-label" for="image">رفع صورة جديدة (اختياري)</label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="card-footer bg-white border-0 pt-0 px-4 pb-4">
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
