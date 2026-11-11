@extends('layouts.app')
@section('title', 'الملف الطبي')

@section('content')
<div class="mr-container">
    <div class="mr-header">
        <a href="{{ route('dashboard') }}" class="mr-back-btn">
            <i class="fas fa-arrow-right"></i>
            العودة للوحة التحكم
        </a>
        <div class="mr-header-content">
            <div class="mr-header-icon">
                <i class="fas fa-file-medical"></i>
            </div>
            <h1>الملف الطبي</h1>
            <p>عرض سجلّك الطبي الكامل</p>
        </div>
    </div>

    <div class="mr-grid">
        <!-- معلومات المريض الشخصية -->
        <div class="mr-card">
            <div class="mr-card-header">
                <i class="fas fa-user"></i>
                <h2>المعلومات الشخصية</h2>
            </div>
            <div class="mr-card-body">
                <div class="mr-info-grid">
                    <div class="mr-info-item">
                        <span class="mr-info-label">الاسم الكامل</span>
                        <span class="mr-info-value">{{ $user->name }}</span>
                    </div>
                    <div class="mr-info-item">
                        <span class="mr-info-label">البريد الإلكتروني</span>
                        <span class="mr-info-value">{{ $user->email }}</span>
                    </div>
                    <div class="mr-info-item">
                        <span class="mr-info-label">رقم الهاتف</span>
                        <span class="mr-info-value">{{ $user->phone_number ?? 'غير محدد' }}</span>
                    </div>
                    <div class="mr-info-item">
                        <span class="mr-info-label">تاريخ الميلاد</span>
                        <span class="mr-info-value">{{ $user->date_of_birth ?? 'غير محدد' }}</span>
                    </div>
                    <div class="mr-info-item">
                        <span class="mr-info-label">الجنس</span>
                        <span class="mr-info-value">{{ $user->gender === 'male' ? 'ذكر' : ($user->gender === 'female' ? 'أنثى' : 'غير محدد') }}</span>
                    </div>
                    <div class="mr-info-item">
                        <span class="mr-info-label">العنوان</span>
                        <span class="mr-info-value">{{ $user->address ?? 'غير محدد' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- السجل الطبي -->
        <div class="mr-card">
            <div class="mr-card-header">
                <i class="fas fa-heartbeat"></i>
                <h2>السجل الطبي</h2>
            </div>
            <div class="mr-card-body">
                @if($medicalRecord)
                    <div class="mr-medical-grid">
                        @if($medicalRecord->blood_type)
                        <div class="mr-medical-item">
                            <span class="mr-medical-label"><i class="fas fa-tint"></i> فصيلة الدم</span>
                            <span class="mr-medical-value mr-blood-type">{{ $medicalRecord->blood_type }}</span>
                        </div>
                        @endif
                        <div class="mr-medical-item">
                            <span class="mr-medical-label"><i class="fas fa-lungs"></i> الأمراض المزمنة</span>
                            <span class="mr-medical-value">{{ $medicalRecord->chronic_diseases ?: 'لا يوجد' }}</span>
                        </div>
                        <div class="mr-medical-item">
                            <span class="mr-medical-label"><i class="fas fa-allergies"></i> الحساسية</span>
                            <span class="mr-medical-value">{{ $medicalRecord->allergies ?: 'لا يوجد' }}</span>
                        </div>
                        <div class="mr-medical-item">
                            <span class="mr-medical-label"><i class="fas fa-pills"></i> الأدوية الحالية</span>
                            <span class="mr-medical-value">{{ $medicalRecord->current_medications ?: 'لا يوجد' }}</span>
                        </div>
                        <div class="mr-medical-item">
                            <span class="mr-medical-label"><i class="fas fa-procedures"></i> العمليات السابقة</span>
                            <span class="mr-medical-value">{{ $medicalRecord->past_surgeries ?: 'لا يوجد' }}</span>
                        </div>
                        <div class="mr-medical-item">
                            <span class="mr-medical-label"><i class="fas fa-tooth"></i> تاريخ الأسنان</span>
                            <span class="mr-medical-value">{{ $medicalRecord->dental_history ?: 'لا يوجد' }}</span>
                        </div>
                        @if($medicalRecord->notes)
                        <div class="mr-medical-item mr-full-width">
                            <span class="mr-medical-label"><i class="fas fa-sticky-note"></i> ملاحظات طبية</span>
                            <span class="mr-medical-value">{{ $medicalRecord->notes }}</span>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="mr-empty">
                        <i class="fas fa-folder-open"></i>
                        <p>لم يتم إنشاء سجل طبي بعد</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- سجل الزيارات -->
        <div class="mr-card mr-visits-card">
            <div class="mr-card-header">
                <i class="fas fa-calendar-check"></i>
                <h2>سجل الزيارات</h2>
            </div>
            <div class="mr-card-body">
                @forelse($visits as $visit)
                    <div class="mr-visit-item">
                        <div class="mr-visit-header">
                            <div class="mr-visit-date">
                                <i class="fas fa-calendar-day"></i>
                                <span>{{ $visit->visit_date }}</span>
                            </div>
                            <div class="mr-visit-doctor">
                                <i class="fas fa-user-md"></i>
                                <span>د. {{ $visit->doctor_name }}</span>
                            </div>
                        </div>
                        <div class="mr-visit-details">
                            <div class="mr-visit-section">
                                <h4><i class="fas fa-stethoscope"></i> التشخيص</h4>
                                <p>{{ $visit->diagnosis }}</p>
                            </div>
                            @if($visit->treatment)
                            <div class="mr-visit-section">
                                <h4><i class="fas fa-prescription-bottle-alt"></i> العلاج</h4>
                                <p>{{ $visit->treatment }}</p>
                            </div>
                            @endif
                            @if($visit->next_plan)
                            <div class="mr-visit-section">
                                <h4><i class="fas fa-clipboard-list"></i> الخطة القادمة</h4>
                                <p>{{ $visit->next_plan }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="mr-empty">
                        <i class="fas fa-calendar-times"></i>
                        <p>لا توجد زيارات مسجلة بعد</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
