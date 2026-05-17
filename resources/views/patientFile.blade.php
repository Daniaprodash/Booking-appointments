@extends('layouts.app')
@section('title', 'ملف المريض — ' . ($patient->name ?? 'مريض'))
@section('content')
    <div class="doctor-dashboard-container" role="main" id="doctorDashboardContainer">
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
                <img src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('assets/images/default-doctor.jpg') }}" alt="صورة الطبيب" class="doctor-dashboard-avatar">
                <div class="doctor-dashboard-profile-info">
                    <h4>{{ auth()->user()->name ?? 'د. اسم الطبيب' }}</h4>
                    <p>{{ $doctor->specialty ?? '' }}</p>
                </div>
            </div>
            <nav class="doctor-dashboard-nav-list" aria-label="قائمة التنقل">
                <a class="doctor-dashboard-nav-item" href="{{ route('doctorDashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة المعلومات</a>
                <a class="doctor-dashboard-nav-item active" href="{{ route('showMedicalRecords') }}"><i class="fas fa-file-medical"></i> السجلات الطبية</a>
                <a class="doctor-dashboard-nav-item" href="#"><i class="fas fa-wallet"></i> الفواتير والمدفوعات</a>
                <a class="doctor-dashboard-nav-item" href="#"><i class="fas fa-cog"></i> الإعدادات</a>
                <a class="doctor-dashboard-nav-item" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
            </nav>
            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">@csrf</form>
            <div class="doctor-dashboard-sidebar-footer">
                آخر تسجيل دخول: <br><strong style="color:var(--primary-2)">{{ auth()->user()->last_login_at ?? '—' }}</strong>
            </div>
        </aside>

        <main class="doctor-dashboard-main">
            <div class="doctor-dashboard-topbar" style="margin-bottom:20px">
                <div class="doctor-dashboard-topbar-start">
                    <button type="button" class="doctor-dashboard-sidebar-toggle" id="doctorSidebarToggle" aria-label="فتح القائمة">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <a href="{{ route('showMedicalRecords') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--primary-2);text-decoration:none;font-weight:600;margin-bottom:8px">
                            <i class="fas fa-arrow-right"></i>
                            العودة للسجلات الطبية
                        </a>
                        <h2 style="margin:0;font-size:1.25rem;font-weight:700;color:var(--accent-contrast)">ملف المريض</h2>
                    </div>
                </div>
            </div>

            <div class="doctor-dashboard-card" style="padding:24px">
                <div style="display:flex;gap:20px;align-items:center;flex-wrap:wrap;margin-bottom:24px">
                    <img src="{{ $patient->image ? asset($patient->image) : asset('assets/images/patient.webp') }}" alt="{{ $patient->name }}" width="80" height="80" style="border-radius:12px;object-fit:cover;border:3px solid rgba(22,225,199,0.2)">
                    <div>
                        <h3 style="margin:0 0 6px;font-size:1.2rem;color:var(--accent-contrast)">{{ $patient->name }}</h3>
                        <p style="margin:0;color:var(--muted);font-size:0.9rem"><i class="fas fa-envelope" style="margin-left:6px"></i>{{ $patient->email }}</p>
                    </div>
                </div>
                <p style="margin:0 0 16px;color:var(--muted);font-size:0.9rem">نظرة سريعة على بيانات المريض. للسجل الطبي الكامل وزيارات العيادة:</p>
                <a href="{{ route('doctor.record.show', $patient->id) }}" class="medical-records-btn" style="display:inline-flex;text-decoration:none">
                    <i class="fas fa-notes-medical"></i>
                    <span>فتح لوحة السجل الطبي</span>
                </a>
            </div>
        </main>
    </div>

    
    <script>
        (function() {
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
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && container && container.classList.contains('sidebar-open')) closeSidebar();
            });
        })();
    </script>
@endsection
