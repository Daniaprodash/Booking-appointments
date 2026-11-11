@extends('admin.layouts.master')
@section('title', 'لوحة الإحصائيات')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-chart-pie"></i> لوحة الإحصائيات</h2>
</div>

<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:linear-gradient(135deg,#16e1c7,#0fb3a1);color:#fff">
            <i class="fas fa-user-md"></i>
        </div>
        <div class="admin-stat-info">
            <div class="admin-stat-number">{{ $doctorsCount }}</div>
            <div class="admin-stat-label">الأطباء</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:linear-gradient(135deg,#7c5cff,#6a4ce0);color:#fff">
            <i class="fas fa-users"></i>
        </div>
        <div class="admin-stat-info">
            <div class="admin-stat-number">{{ $patientsCount }}</div>
            <div class="admin-stat-label">المرضى</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:linear-gradient(135deg,#ffb86b,#f5a623);color:#fff">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="admin-stat-info">
            <div class="admin-stat-number">{{ $appointmentsCount }}</div>
            <div class="admin-stat-label">إجمالي المواعيد</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:linear-gradient(135deg,#ff6b95,#ff4f70);color:#fff">
            <i class="fas fa-cogs"></i>
        </div>
        <div class="admin-stat-info">
            <div class="admin-stat-number">{{ $servicesCount }}</div>
            <div class="admin-stat-label">الخدمات</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-chart-bar"></i> المواعيد حسب الحالة</h4>
            </div>
            <div class="admin-card-body">
                <div class="admin-stats-grid" style="grid-template-columns:repeat(2,1fr)">
                    <div style="padding:1rem;background:rgba(255,243,205,0.3);border-radius:10px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:700;color:#856404">{{ $appointmentsByStatus['pending'] }}</div>
                        <div style="font-size:0.85rem;color:var(--admin-muted)">قيد الانتظار</div>
                    </div>
                    <div style="padding:1rem;background:rgba(212,237,218,0.3);border-radius:10px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:700;color:#155724">{{ $appointmentsByStatus['confirmed'] }}</div>
                        <div style="font-size:0.85rem;color:var(--admin-muted)">مؤكد</div>
                    </div>
                    <div style="padding:1rem;background:rgba(248,215,218,0.3);border-radius:10px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:700;color:#721c24">{{ $appointmentsByStatus['rejected'] }}</div>
                        <div style="font-size:0.85rem;color:var(--admin-muted)">مرفوض</div>
                    </div>
                    <div style="padding:1rem;background:rgba(226,232,240,0.3);border-radius:10px;text-align:center">
                        <div style="font-size:1.5rem;font-weight:700;color:#5a6380">{{ $appointmentsByStatus['cancelled'] }}</div>
                        <div style="font-size:0.85rem;color:var(--admin-muted)">ملغي</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h4><i class="fas fa-clock"></i> أقرب المواعيد القادمة</h4>
            </div>
            <div class="admin-card-body" style="padding:0">
                @if($upcomingAppointments->count() > 0)
                    <div style="overflow-x:auto">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>المريض</th>
                                    <th>الطبيب</th>
                                    <th>التاريخ</th>
                                    <th>الوقت</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingAppointments as $apt)
                                <tr>
                                    <td>{{ $apt->user->name ?? 'غير معروف' }}</td>
                                    <td>د. {{ $apt->doctor?->user?->name ?? 'غير معروف' }}</td>
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
                        <p>لا توجد مواعيد قادمة</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
