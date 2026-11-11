@extends('admin.layouts.master')
@section('title', 'تفاصيل الطبيب')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-user-md"></i> {{ $doctor->user?->name ?? 'طبيب' }}</h2>
    <div class="admin-actions">
        <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="admin-btn admin-btn-primary">
            <i class="fas fa-edit"></i> تعديل
        </a>
        <a href="{{ route('admin.doctors.index') }}" class="admin-btn admin-btn-outline">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="admin-card">
            <div class="admin-card-body" style="text-align:center">
                <img src="{{ $doctor->user?->image ? asset($doctor->user->image) : asset('assets/images/default-doctor.jpg') }}"
                     style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid rgba(22,225,199,0.2);margin-bottom:1rem">
                <h5 style="font-weight:700">{{ $doctor->user?->name }}</h5>
                <p style="color:var(--admin-muted);margin:0">{{ $doctor->specialty }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-info-circle"></i> المعلومات الشخصية</h4>
            </div>
            <div class="admin-card-body">
                <div class="admin-detail-grid">
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">البريد الإلكتروني</div>
                        <div class="admin-detail-value">{{ $doctor->user?->email ?? '—' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الهاتف</div>
                        <div class="admin-detail-value">{{ $doctor->user?->phone_number ?? '—' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">التخصص</div>
                        <div class="admin-detail-value">{{ $doctor->specialty ?? '—' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">تاريخ التسجيل</div>
                        <div class="admin-detail-value">{{ $doctor->created_at }}</div>
                    </div>
                </div>
                @if($doctor->bio)
                    <div style="margin-top:1rem;padding:1rem;background:var(--admin-bg);border-radius:10px">
                        <div class="admin-detail-label" style="margin-bottom:0.25rem">نبذة</div>
                        <div style="color:var(--admin-dark)">{{ $doctor->bio }}</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-chart-line"></i> إحصائيات سريعة</h4>
            </div>
            <div class="admin-card-body">
                <div class="admin-stats-grid" style="grid-template-columns:repeat(2,1fr)">
                    <div style="padding:1rem;background:var(--admin-bg);border-radius:10px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:700;color:var(--admin-primary-dark)">{{ $appointmentsCount }}</div>
                        <div style="font-size:0.85rem;color:var(--admin-muted)">إجمالي المواعيد</div>
                    </div>
                    <div style="padding:1rem;background:var(--admin-bg);border-radius:10px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:700;color:var(--admin-primary-dark)">{{ $patientsCount }}</div>
                        <div style="font-size:0.85rem;color:var(--admin-muted)">عدد المرضى</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
