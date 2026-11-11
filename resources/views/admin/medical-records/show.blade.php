@extends('admin.layouts.master')
@section('title', 'تفاصيل السجل الطبي')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-file-medical"></i> السجل الطبي لـ {{ $record->patient_name }}</h2>
    <a href="{{ route('admin.medical-records.index') }}" class="admin-btn admin-btn-outline">
        <i class="fas fa-arrow-right"></i> العودة
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-user"></i> معلومات المريض</h4>
            </div>
            <div class="admin-card-body">
                <div class="admin-detail-grid">
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الاسم</div>
                        <div class="admin-detail-value">{{ $record->patient_name }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">البريد</div>
                        <div class="admin-detail-value">{{ $record->patient_email }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الهاتف</div>
                        <div class="admin-detail-value">{{ $record->phone_number ?? '—' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الجنس</div>
                        <div class="admin-detail-value">
                            {{ $record->gender == 'male' ? 'ذكر' : ($record->gender == 'female' ? 'أنثى' : '—') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-heartbeat"></i> البيانات الطبية</h4>
            </div>
            <div class="admin-card-body">
                <div class="admin-detail-grid">
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">فصيلة الدم</div>
                        <div class="admin-detail-value" style="font-size:1.3rem;color:var(--admin-danger)">
                            {{ $record->blood_type ?? '—' }}
                        </div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الأمراض المزمنة</div>
                        <div class="admin-detail-value">{{ $record->chronic_diseases ?? 'لا يوجد' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الحساسية</div>
                        <div class="admin-detail-value">{{ $record->allergies ?? 'لا يوجد' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">الأدوية الحالية</div>
                        <div class="admin-detail-value">{{ $record->current_medications ?? 'لا يوجد' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">العمليات السابقة</div>
                        <div class="admin-detail-value">{{ $record->past_surgeries ?? 'لا يوجد' }}</div>
                    </div>
                    <div class="admin-detail-item">
                        <div class="admin-detail-label">تاريخ الأسنان</div>
                        <div class="admin-detail-value">{{ $record->dental_history ?? 'لا يوجد' }}</div>
                    </div>
                </div>
                @if($record->notes)
                    <div style="margin-top:1rem;padding:1rem;background:var(--admin-bg);border-radius:10px">
                        <div class="admin-detail-label" style="margin-bottom:0.25rem">ملاحظات</div>
                        <div style="color:var(--admin-dark)">{{ $record->notes }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-history"></i> سجل الزيارات</h4>
            </div>
            <div class="admin-card-body" style="padding:0">
                @if($visits->count() > 0)
                    <div style="overflow-x:auto">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الطبيب</th>
                                    <th>التشخيص</th>
                                    <th>العلاج</th>
                                    <th>الخطة القادمة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visits as $visit)
                                <tr>
                                    <td>{{ $visit->visit_date }}</td>
                                    <td>د. {{ $visit->doctor_name }}</td>
                                    <td>{{ $visit->diagnosis }}</td>
                                    <td>{{ $visit->treatment ?? '—' }}</td>
                                    <td>{{ $visit->next_plan ?? '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="admin-empty">
                        <i class="fas fa-calendar-times"></i>
                        <p>لا توجد زيارات مسجلة</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
