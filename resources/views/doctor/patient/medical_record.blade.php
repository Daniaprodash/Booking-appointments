@extends('layouts.app')
@section('title', 'السجل الطبي — ' . $patient->name)

@php
    $genderLabel = match ($patient->gender ?? null) {
        'male' => 'ذكر',
        'female' => 'أنثى',
        default => '—',
    };
    $dobFormatted = $patient->date_of_birth
        ? \Illuminate\Support\Carbon::parse($patient->date_of_birth)->format('Y-m-d')
        : null;
    $visitsForJs = $visits->map(fn ($v) => [
        'id' => $v->id,
        'visit_date' => $v->visit_date,
        'diagnosis' => $v->diagnosis,
        'treatment' => $v->treatment,
        'notes' => $v->next_plan,
    ]);
    $mrReadFields = [
        ['label' => 'الأمراض المزمنة', 'value' => $medicalRecord->chronic_diseases ?? null],
        ['label' => 'الحساسية', 'value' => $medicalRecord->allergies ?? null],
        ['label' => 'الأدوية الحالية', 'value' => $medicalRecord->current_medications ?? null],
        ['label' => 'العمليات السابقة', 'value' => $medicalRecord->past_surgeries ?? null],
        ['label' => 'التاريخ السني', 'value' => $medicalRecord->dental_history ?? null],
        ['label' => 'ملاحظات', 'value' => $medicalRecord->notes ?? null],
    ];
    $visitFieldErrors = $errors->hasAny(['visit_date', 'diagnosis', 'treatment', 'notes']);
    $mrInitial = [
        'chronic_diseases' => $medicalRecord->chronic_diseases ?? '',
        'allergies' => $medicalRecord->allergies ?? '',
        'current_medications' => $medicalRecord->current_medications ?? '',
        'past_surgeries' => $medicalRecord->past_surgeries ?? '',
        'dental_history' => $medicalRecord->dental_history ?? '',
        'notes' => $medicalRecord->notes ?? '',
    ];
@endphp

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/medicalRecordDashboard.css') }}">

<div class="doctor-dashboard-container medical-record-page" role="main" id="doctorDashboardContainer">
    <div class="doctor-dashboard-sidebar-overlay" id="doctorSidebarOverlay" aria-hidden="true"></div>

    <aside class="doctor-dashboard-sidebar" id="doctorDashboardSidebar" aria-label="تنقل الطبيب">
        <button type="button" class="doctor-dashboard-sidebar-close" id="doctorSidebarClose" aria-label="إغلاق القائمة">
            <i class="fas fa-times"></i>
        </button>
        <div class="doctor-dashboard-brand">
            <div class="doctor-dashboard-logo"><i class="fas fa-tooth"></i></div>
            <div>
                <div class="doctor-dashboard-title">حجز المواعيد</div>
                <div style="font-size:0.85rem;color:var(--muted)">لوحة تحكم الطبيب</div>
            </div>
        </div>
        <div class="doctor-dashboard-profile">
            <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('assets/images/default-doctor.jpg') }}" alt="" class="doctor-dashboard-avatar">
            <div class="doctor-dashboard-profile-info">
                <h4>{{ auth()->user()->name }}</h4>
                <p>{{ $doctor->specialty ?? '' }}</p>
            </div>
        </div>
        <nav class="doctor-dashboard-nav-list" aria-label="قائمة التنقل">
            <a class="doctor-dashboard-nav-item" href="{{ route('doctorDashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة المعلومات</a>
            <a class="doctor-dashboard-nav-item active" href="{{ route('showMedicalRecords') }}"><i class="fas fa-file-medical"></i> السجلات الطبية</a>
            <a class="doctor-dashboard-nav-item" href="{{ route('payment') }}"><i class="fas fa-wallet"></i> الفواتير والمدفوعات</a>
            <a class="doctor-dashboard-nav-item" href="{{ route('settings') }}"><i class="fas fa-cog"></i> الإعدادات</a>
            <a class="doctor-dashboard-nav-item" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
        </nav>
        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">@csrf</form>
        <div class="doctor-dashboard-sidebar-footer">
            آخر تسجيل دخول: <br><strong style="color:var(--primary-2)">{{ auth()->user()->last_login_at ?? '—' }}</strong>
        </div>
    </aside>

    <main class="doctor-dashboard-main">
        <div class="doctor-dashboard-topbar" style="margin-bottom:16px">
            <div class="doctor-dashboard-topbar-start">
                <button type="button" class="doctor-dashboard-sidebar-toggle" id="doctorSidebarToggle" aria-label="فتح القائمة">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <a href="{{ route('showMedicalRecords') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--primary-2);text-decoration:none;font-weight:600;margin-bottom:8px">
                        <i class="fas fa-arrow-right"></i>
                        العودة لقائمة المرضى
                    </a>
                    <h2 style="margin:0;font-size:1.25rem;font-weight:700;color:var(--accent-contrast)">لوحة السجل الطبي للمريض</h2>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="medical-records-alert medical-records-alert-success mb-3">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="medical-records-alert medical-records-alert-error mb-3">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- 1) Patient basic info (read-only) --}}
        <section class="mr-section" aria-labelledby="mr-patient-heading">
            <div class="mr-section-header">
                <h2 id="mr-patient-heading" class="mr-section-title"><i class="fas fa-id-card"></i> بيانات المريض</h2>
            </div>
            <div class="mr-section-body">
                <div class="mr-patient-header">
                    <img class="mr-patient-avatar" src="{{ $patient->image ? asset($patient->image) : asset('assets/images/patient.webp') }}" alt="{{ $patient->name }}">
                    <div>
                        <h3 class="mr-patient-name">{{ $patient->name }}</h3>
                        <div class="mr-patient-meta">
                            <div class="mr-meta-item">
                                <i class="fas fa-envelope"></i>
                                <div><strong>البريد</strong>{{ $patient->email ?? '—' }}</div>
                            </div>
                            <div class="mr-meta-item">
                                <i class="fas fa-phone"></i>
                                <div><strong>الهاتف</strong>{{ $patient->phone_number ?? '—' }}</div>
                            </div>
                            <div class="mr-meta-item">
                                <i class="fas fa-venus-mars"></i>
                                <div><strong>الجنس</strong>{{ $genderLabel }}</div>
                            </div>
                            <div class="mr-meta-item">
                                <i class="fas fa-birthday-cake"></i>
                                <div><strong>تاريخ الميلاد</strong>{{ $dobFormatted ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- 2) Medical record --}}
        <section class="mr-section" aria-labelledby="mr-record-heading">
            <div class="mr-section-header">
                <h2 id="mr-record-heading" class="mr-section-title"><i class="fas fa-notes-medical"></i> السجل الطبي</h2>
                <div class="mr-btn-group" id="mr-record-actions">
                    <button type="button" class="mr-btn mr-btn-primary" id="mr-btn-edit-record" onclick="mrEnterEditMode()">
                        <i class="fas fa-edit"></i> تعديل السجل الطبي
                    </button>
                </div>
            </div>
            <div class="mr-section-body">
                <div id="mr-record-read">
                    <div class="mr-field-grid">
                        @foreach($mrReadFields as $field)
                            <div class="mr-field-card">
                                <div class="mr-field-label">{{ $field['label'] }}</div>
                                @if(filled($field['value']))
                                    <p class="mr-field-value">{{ $field['value'] }}</p>
                                @else
                                    <p class="mr-field-value mr-empty-dash mb-0">—</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <form id="mr-record-form" action="{{ route('doctor.record.update', $patient->id) }}" method="POST" class="mr-d-none">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="chronic_diseases">الأمراض المزمنة</label>
                            <textarea class="form-control" name="chronic_diseases" id="chronic_diseases" rows="4">{{ old('chronic_diseases', $medicalRecord->chronic_diseases ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="allergies">الحساسية</label>
                            <textarea class="form-control" name="allergies" id="allergies" rows="4">{{ old('allergies', $medicalRecord->allergies ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="current_medications">الأدوية الحالية</label>
                            <textarea class="form-control" name="current_medications" id="current_medications" rows="4">{{ old('current_medications', $medicalRecord->current_medications ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="past_surgeries">العمليات السابقة</label>
                            <textarea class="form-control" name="past_surgeries" id="past_surgeries" rows="4">{{ old('past_surgeries', $medicalRecord->past_surgeries ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="dental_history">التاريخ السني</label>
                            <textarea class="form-control" name="dental_history" id="dental_history" rows="4">{{ old('dental_history', $medicalRecord->dental_history ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="notes">ملاحظات</label>
                            <textarea class="form-control" name="notes" id="notes" rows="4">{{ old('notes', $medicalRecord->notes ?? '') }}</textarea>
                        </div>
                    </div>
                    @if($errors->any() && ! $visitFieldErrors)
                        <div class="alert alert-danger mt-3 mb-0" role="alert">
                            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="mr-btn-group mt-4">
                        <button type="submit" class="mr-btn mr-btn-primary"><i class="fas fa-save"></i> حفظ التغييرات</button>
                        <button type="button" class="mr-btn mr-btn-outline" onclick="mrCancelEdit()">إلغاء</button>
                    </div>
                </form>
            </div>
        </section>

        {{-- 3) Visits --}}
        <section class="mr-section" aria-labelledby="mr-visits-heading">
            <div class="mr-section-header">
                <h2 id="mr-visits-heading" class="mr-section-title"><i class="fas fa-stethoscope"></i> سجل الزيارات</h2>
                <button type="button" class="mr-btn mr-btn-primary" onclick="mrOpenVisitModal('create')">
                    <i class="fas fa-plus-circle"></i> إضافة زيارة جديدة
                </button>
            </div>
            <div class="mr-section-body">
                <div class="mr-table-wrap">
                    <table class="mr-table">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>التشخيص</th>
                                <th>العلاج</th>
                                <th>ملاحظات / الخطة</th>
                                <th style="width:110px">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($visits as $visit)
                                <tr>
                                    <td>{{ $visit->visit_date ?? '—' }}</td>
                                    <td>{{ $visit->diagnosis ?? '—' }}</td>
                                    <td class="mr-cell-muted">{{ $visit->treatment ?? '—' }}</td>
                                    <td class="mr-cell-muted">{{ $visit->next_plan ?? '—' }}</td>
                                    <td>
                                        <button type="button" class="mr-btn mr-btn-outline mr-btn-icon" onclick="mrOpenVisitModal('edit', {{ $visit->id }})">
                                            <i class="fas fa-edit"></i> تعديل
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">لا توجد زيارات مسجّلة بعد.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>

{{-- Visit modal --}}
<div id="visitModal" class="mr-modal" aria-hidden="true" role="dialog" aria-labelledby="visitModalTitle">
    <div class="mr-modal-dialog">
        <div class="mr-modal-header">
            <h3 class="mr-modal-title" id="visitModalTitle">زيارة</h3>
            <button type="button" class="mr-modal-close" onclick="mrCloseVisitModal()" aria-label="إغلاق">&times;</button>
        </div>
        <form id="visitModalForm" method="POST" action="">
            @csrf
            <div id="visitMethodSlot"></div>
            <div class="mr-modal-body">
                <div class="mb-3">
                    <label class="form-label" for="visit_date">تاريخ الزيارة <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('visit_date') is-invalid @enderror" name="visit_date" id="visit_date" required>
                    @error('visit_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="diagnosis">التشخيص <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('diagnosis') is-invalid @enderror" name="diagnosis" id="diagnosis" rows="3" required></textarea>
                    @error('diagnosis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="treatment">العلاج</label>
                    <textarea class="form-control @error('treatment') is-invalid @enderror" name="treatment" id="treatment" rows="3"></textarea>
                    @error('treatment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-0">
                    <label class="form-label" for="visit_notes">ملاحظات / الخطة القادمة</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="visit_notes" rows="2"></textarea>
                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mr-modal-footer">
                <button type="button" class="mr-btn mr-btn-outline" onclick="mrCloseVisitModal()">إلغاء</button>
                <button type="submit" class="mr-btn mr-btn-primary"><i class="fas fa-check"></i> حفظ</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.MR_VISIT_STORE_URL = @json(route('doctor.visit.store', $patient->id));
    window.MR_VISIT_UPDATE_URL_BASE = @json(url('/doctor/visit'));
    window.MR_VISITS = @json($visitsForJs);
    window.MR_INITIAL = @json($mrInitial);

    function mrEnterEditMode() {
        document.getElementById('mr-record-read').classList.add('mr-d-none');
        document.getElementById('mr-record-form').classList.remove('mr-d-none');
        document.getElementById('mr-btn-edit-record').classList.add('mr-d-none');
    }

    function mrCancelEdit() {
        if (window.MR_INITIAL) {
            document.getElementById('chronic_diseases').value = window.MR_INITIAL.chronic_diseases || '';
            document.getElementById('allergies').value = window.MR_INITIAL.allergies || '';
            document.getElementById('current_medications').value = window.MR_INITIAL.current_medications || '';
            document.getElementById('past_surgeries').value = window.MR_INITIAL.past_surgeries || '';
            document.getElementById('dental_history').value = window.MR_INITIAL.dental_history || '';
            document.getElementById('notes').value = window.MR_INITIAL.notes || '';
        }
        document.getElementById('mr-record-read').classList.remove('mr-d-none');
        document.getElementById('mr-record-form').classList.add('mr-d-none');
        document.getElementById('mr-btn-edit-record').classList.remove('mr-d-none');
    }

    function mrOpenVisitModal(mode, visitId) {
        const modal = document.getElementById('visitModal');
        const form = document.getElementById('visitModalForm');
        const methodSlot = document.getElementById('visitMethodSlot');
        const title = document.getElementById('visitModalTitle');

        form.reset();
        methodSlot.innerHTML = '';

        if (mode === 'create') {
            title.textContent = 'إضافة زيارة جديدة';
            form.action = window.MR_VISIT_STORE_URL;
            form.method = 'POST';
        } else {
            title.textContent = 'تعديل الزيارة';
            const visit = (window.MR_VISITS || []).find(function (v) { return String(v.id) === String(visitId); });
            form.action = window.MR_VISIT_UPDATE_URL_BASE + '/' + visitId;
            form.method = 'POST';
            methodSlot.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            if (visit) {
                document.getElementById('visit_date').value = visit.visit_date || '';
                document.getElementById('diagnosis').value = visit.diagnosis || '';
                document.getElementById('treatment').value = visit.treatment || '';
                document.getElementById('visit_notes').value = visit.notes || '';
            }
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function mrCloseVisitModal() {
        const modal = document.getElementById('visitModal');
        if (!modal.classList.contains('is-open')) {
            return;
        }
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    document.getElementById('visitModal').addEventListener('click', function (e) {
        if (e.target === this) mrCloseVisitModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') mrCloseVisitModal();
    });

    (function () {
        var container = document.getElementById('doctorDashboardContainer');
        var overlay = document.getElementById('doctorSidebarOverlay');
        var toggleBtn = document.getElementById('doctorSidebarToggle');
        var closeBtn = document.getElementById('doctorSidebarClose');
        function openSidebar() {
            if (container) container.classList.add('sidebar-open');
            if (overlay) { overlay.classList.add('active'); overlay.setAttribute('aria-hidden', 'false'); }
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            if (container) container.classList.remove('sidebar-open');
            if (overlay) { overlay.classList.remove('active'); overlay.setAttribute('aria-hidden', 'true'); }
            document.body.style.overflow = '';
        }
        if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && container && container.classList.contains('sidebar-open')) closeSidebar();
        });
    })();

    @if($visitFieldErrors)
        mrOpenVisitModal('create');
        document.getElementById('visit_date').value = @json(old('visit_date', ''));
        document.getElementById('diagnosis').value = @json(old('diagnosis', ''));
        document.getElementById('treatment').value = @json(old('treatment', ''));
        document.getElementById('visit_notes').value = @json(old('notes', ''));
    @endif

    @if($errors->any() && ! $visitFieldErrors)
        mrEnterEditMode();
    @endif
</script>
@endsection
