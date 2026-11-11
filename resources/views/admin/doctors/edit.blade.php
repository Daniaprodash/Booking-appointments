@extends('admin.layouts.master')
@section('title', 'تعديل طبيب')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-edit"></i> تعديل بيانات الطبيب</h2>
    <a href="{{ route('admin.doctors.index') }}" class="admin-btn admin-btn-outline">
        <i class="fas fa-arrow-right"></i> العودة
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="name" class="admin-form-control"
                               value="{{ old('name', $doctor->user->name) }}" required>
                        @error('name') <small style="color:var(--admin-danger)">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" class="admin-form-control"
                               value="{{ old('email', $doctor->user->email) }}" required>
                        @error('email') <small style="color:var(--admin-danger)">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label>كلمة المرور (اتركه فارغاً إن لم ترد التغيير)</label>
                        <input type="password" name="password" class="admin-form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label>رقم الهاتف</label>
                        <input type="text" name="phone" class="admin-form-control"
                               value="{{ old('phone', $doctor->user->phone_number) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label>التخصص</label>
                        <input type="text" name="specialty" class="admin-form-control"
                               value="{{ old('specialty', $doctor->specialty) }}" required>
                        @error('specialty') <small style="color:var(--admin-danger)">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="admin-form-group">
                        <label>نبذة عن الطبيب</label>
                        <textarea name="bio" class="admin-form-control">{{ old('bio', $doctor->bio) }}</textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="fas fa-save"></i> حفظ التغييرات
            </button>
        </form>
    </div>
</div>
@endsection
