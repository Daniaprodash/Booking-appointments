<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - الأدمن</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/adminStyle.css') }}">
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <div class="admin-logo">
                    <i class="fas fa-tooth"></i>
                </div>
                <h3>لوحة التحكم</h3>
            </div>
            <nav class="admin-sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>الإحصائيات</span>
                </a>
                <a href="{{ route('admin.doctors.index') }}" class="admin-nav-item {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                    <i class="fas fa-user-md"></i>
                    <span>الأطباء</span>
                </a>
                <a href="{{ route('admin.patients.index') }}" class="admin-nav-item {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>المرضى</span>
                </a>
                <a href="{{ route('admin.appointments.index') }}" class="admin-nav-item {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>المواعيد</span>
                </a>
                <a href="{{ route('admin.medical-records.index') }}" class="admin-nav-item {{ request()->routeIs('admin.medical-records.*') ? 'active' : '' }}">
                    <i class="fas fa-file-medical"></i>
                    <span>السجلات الطبية</span>
                </a>
                <hr class="admin-sidebar-divider">
                <a href="{{ route('index') }}" class="admin-nav-item">
                    <i class="fas fa-globe"></i>
                    <span>الموقع العام</span>
                </a>
                <a href="{{ route('auth.logout') }}" class="admin-nav-item"
                   onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </nav>
            <form id="admin-logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">@csrf</form>
        </aside>

        <!-- Sidebar Overlay for mobile -->
        <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <button class="admin-topbar-toggle" id="adminSidebarToggle" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="admin-topbar-info">
                    <i class="fas fa-user-shield"></i>
                    <span>{{ auth()->user()->name }}</span>
                </div>
            </header>

            <!-- Page Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="admin-alert admin-alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="admin-alert admin-alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');
            const toggleBtn = document.getElementById('adminSidebarToggle');

            function toggleSidebar() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);
        });
    </script>
    @stack('scripts')
</body>
</html>
