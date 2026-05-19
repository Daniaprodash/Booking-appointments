@extends('layouts.app')
@section('title', 'السجلات الطبية')
@section('content')

<div class="medical-records-page-container">
    <div class="medical-records-back-link">
        <a href="{{ route('doctorDashboard') }}">
            <i class="fas fa-arrow-right"></i>
            العودة للوحة التحكم
        </a>
    </div>

    <h1 class="medical-records-page-title">
        <i class="fas fa-file-medical"></i>
        السجلات الطبية — قائمة المرضى
    </h1>

    @if(session('success'))
        <div class="medical-records-alert medical-records-alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="medical-records-alert medical-records-alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($patients->isEmpty())
        <div class="doctor-dashboard-card medical-records-empty">
            <i class="fas fa-user-injured" style="font-size:3rem;color:var(--muted);margin-bottom:12px"></i>
            <p style="margin:0;color:var(--muted);font-weight:600">لا يوجد مرضى مسجلون لديك بعد.</p>
            <p style="margin:8px 0 0;font-size:0.9rem;color:var(--muted)">سيظهر هنا كل مريض قام بحجز موعد عندك.</p>
        </div>
    @else
        <div class="medical-records-grid">
            @foreach($patients as $patient)
                <div class="medical-records-card doctor-dashboard-card">
                    <div class="medical-records-card-header">
                        <img
                            src="{{ $patient->image ? asset($patient->image) : asset('assets/images/patient.webp') }}"
                            alt="{{ $patient->name }}"
                            class="medical-records-avatar"
                        >
                        <div class="medical-records-card-info">
                            <h3 class="medical-records-name">{{ $patient->name }}</h3>
                            <p class="medical-records-email"><i class="fas fa-envelope" style="margin-left:6px;color:var(--muted)"></i>{{ $patient->email }}</p>
                        </div>
                    </div>
                    <div class="medical-records-card-footer">
                        <a href="{{ route('doctor.record.show', $patient->id) }}" class="medical-records-btn">
                            <i class="fas fa-user-folder"></i>
                            <span>عرض السجل الطبي</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
