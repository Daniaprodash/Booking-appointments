@extends('layouts.app')
@section('title', 'الفواتير والمدفوعات')
@section('content')
    <div class="doctor-dashboard-container" role="main" style="max-width:900px;margin:0 auto;padding:28px">
        <div style="margin-bottom:24px">
            <a href="{{ route('doctorDashboard') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--primary-2);text-decoration:none;font-weight:600">
                <i class="fas fa-arrow-right"></i>
                العودة للوحة التحكم
            </a>
        </div>
        <div class="doctor-dashboard-card" style="padding:48px;text-align:center">
            <i class="fas fa-wallet" style="font-size:3rem;color:var(--muted);margin-bottom:16px"></i>
            <h4 style="margin:0 0 8px;color:var(--accent-contrast)">الفواتير والمدفوعات</h4>
            <p style="color:var(--muted);margin:0">هذه الصفحة قيد التطوير</p>
        </div>
    </div>
@endsection
