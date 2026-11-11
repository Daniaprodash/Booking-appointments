@extends('admin.layouts.master')
@section('title', 'تعديل مريض')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-user-edit"></i> تعديل بيانات المريض</h2>
    <a href="{{ route('admin.patients.index') }}" class="admin-btn admin-btn-outline">
        <i class="fas fa-arrow-right"></i> العودة
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-body">
        <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="name" class="admin-form-control"
                               value="{{ old('name', $patient->name) }}" required>
                        @error('name') <small style="color:var(--admin-danger)">{{ $message }}</small> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" class="admin-form-control"
                               value="{{ old('email', $patient->email) }}" required>
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
                               value="{{ old('phone', $patient->phone_number) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="admin-form-group">
                        <label>الجنس</label>
                        <select name="gender" class="admin-form-control admin-form-select">
                            <option value="">— اختر —</option>
                            <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="admin-form-group">
                        <label>العنوان</label>
                        <textarea name="address" class="admin-form-control">{{ old('address', $patient->address) }}</textarea>
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
