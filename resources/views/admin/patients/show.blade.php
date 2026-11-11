@extends('admin.layouts.master')
@section('title', 'تفاصيل المريض')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-user"></i> {{ $patient->name }}</h2>
    <div class="admin-actions">
        <a href="{{ route('admin.patients.edit', $patient->id) }}" class="admin-btn admin-btn-primary">
            <i class="fas fa-edit"></i> تعديل
        </a>
        <a href="{{ route('admin.patients.index') }}" class="admin-btn admin-btn-outline">
            <i class="fas fa-arrow-right"></i> العودة
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="admin-card">
            <div class="admin-card-body" style="text-align:center">
                <img src="{{ $patient->image ? asset($patient->image) : asset('assets/images/patient.webp') }}"
                     style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid rgba(22,225,199,0.2);margin-bottom:1rem">
                <h5 style="font-weight:700">{{ $patient->name }}</h5>
                <p style="color:var(--admin-muted);margin:0">{{ $patient->email }}</p>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-info-circle"></i> معلومات شخصية</h4>
            </div>
            <div class="admin-card-body">
                <div class="admin-detail-grid" style="grid-template-columns:1fr">
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الهاتف</div>
                        <div class="admin-detail-value">{{ $patient->phone_number ?? '—' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الجنس</div>
                        <div class="admin-detail-value">
                            @if($patient->gender == 'male') ذكر
                            @elseif($patient->gender == 'female') أنثى
                            @else —
                            @endif
                        </div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">العنوان</div>
                        <div class="admin-detail-value">{{ $patient->address ?? '—' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">تاريخ التسجيل</div>
                        <div class="admin-detail-value">{{ $patient->created_at }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- المواعيد -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-calendar-check"></i> المواعيد ({{ $appointments->count() }})</h4>
            </div>
            <div class="admin-card-body" style="padding:0">
                @if($appointments->count() > 0)
                    <div style="overflow-x:auto">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>الطبيب</th>
                                    <th>الخدمة</th>
                                    <th>التاريخ</th>
                                    <th>الوقت</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $apt)
                                <tr>
                                    <td>د. {{ $apt->doctor?->user?->name ?? '—' }}</td>
                                    <td>{{ $apt->service?->title ?? '—' }}</td>
                                    <td>{{ $apt->appointment_date }}</td>
                                    <td>{{ $apt->appointment_time }}</td>
                                    <td>
                                        <span class="admin-badge admin-badge-{{ $apt->status }}">
                                            @switch($apt->status)
                                                @case('pending') قيد الانتظار @break
                                                @case('confirmed') مؤكد @break
                                                @case('rejected') مرفوض @break
                                                @case('cancelled') ملغي @break
                                                @default {{ $apt->status }}
                                            @endswitch
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="admin-empty">
                        <i class="fas fa-calendar-times"></i>
                        <p>لا توجد مواعيد لهذا المريض</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- السجل الطبي -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-file-medical"></i> السجل الطبي</h4>
            </div>
            <div class="admin-card-body">
                @if($medicalRecord)
                    <div class="admin-detail-grid" style="grid-template-columns:repeat(2,1fr)">
                        <div class="admin-detail-item">
                            <div class="admin-detail-label">فصيلة الدم</div>
                            <div class="admin-detail-value">{{ $medicalRecord->blood_type ?? '—' }}</div>
                        </div>
                        <div class="admin-detail-item">
                            <div class="admin-detail-label">الأمراض المزمنة</div>
                            <div class="admin-detail-value">{{ $medicalRecord->chronic_diseases ?? 'لا يوجد' }}</div>
                        </div>
                        <div class="admin-detail-item">
                            <div class="admin-detail-label">الحساسية</div>
                            <div class="admin-detail-value">{{ $medicalRecord->allergies ?? 'لا يوجد' }}</div>
                        </div>
                        <div class="admin-detail-item">
                            <div class="admin-detail-label">الأدوية الحالية</div>
                            <div class="admin-detail-value">{{ $medicalRecord->current_medications ?? 'لا يوجد' }}</div>
                        </div>
                        <div class="admin-detail-item">
                            <div class="admin-detail-label">العمليات السابقة</div>
                            <div class="admin-detail-value">{{ $medicalRecord->past_surgeries ?? 'لا يوجد' }}</div>
                        </div>
                        <div class="admin-detail-item">
                            <div class="admin-detail-label">تاريخ الأسنان</div>
                            <div class="admin-detail-value">{{ $medicalRecord->dental_history ?? 'لا يوجد' }}</div>
                        </div>
                    </div>
                @else
                    <div class="admin-empty">
                        <i class="fas fa-folder-open"></i>
                        <p>لا يوجد سجل طبي لهذا المريض</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
