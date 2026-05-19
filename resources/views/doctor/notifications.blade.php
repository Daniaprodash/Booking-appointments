@extends('layouts.app')
@section('title', 'إشعارات الطبيب')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/notificationsStyle.css') }}">

<div class="notifications-page-container">
    <div class="notifications-header">
        <a href="{{ route('doctorDashboard') }}" class="notif-back-link">
            <i class="fas fa-arrow-right"></i>
            العودة للوحة التحكم
        </a>
        <h1 class="notifications-title">
            <i class="fas fa-bell"></i>
            إشعارات الطبيب
        </h1>
    </div>

    <div class="notifications-top-actions">
        @if($unreadCount > 0)
            <form action="{{ route('doctor.notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="notif-btn notif-btn-outline">
                    <i class="fas fa-check-double"></i>
                    <span>قراءة الكل</span>
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="notif-alert notif-alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="notifications-card">
        @if($activities->isEmpty())
            <div class="notif-empty">
                <i class="fas fa-bell-slash"></i>
                <h4>لا توجد إشعارات</h4>
                <p>ستظهر هنا جميع النشاطات والتنبيهات الخاصة بك</p>
            </div>
        @else
            <div class="notif-stats">
                <div class="notif-stat">
                    <span class="notif-stat-number">{{ $activities->total() }}</span>
                    <span class="notif-stat-label">إجمالي الإشعارات</span>
                </div>
                <div class="notif-stat notif-stat-unread">
                    <span class="notif-stat-number">{{ $unreadCount }}</span>
                    <span class="notif-stat-label">غير مقروءة</span>
                </div>
            </div>

            <ul class="notif-list">
                @foreach($activities as $activity)
                    <li class="notif-item {{ $activity->is_read ? 'notif-item-read' : '' }}" data-id="{{ $activity->id }}">
                        <div class="notif-icon notif-icon-{{ match($activity->type ?? '') {
                            'appointment_created' => 'created',
                            'appointment_confirmed' => 'confirmed',
                            'appointment_rejected' => 'rejected',
                            'appointment_cancelled' => 'cancelled',
                            'appointment_deleted' => 'deleted',
                            'appointment_updated' => 'updated',
                            'payment_received' => 'payment',
                            'profile_updated' => 'profile',
                            default => 'default'
                        } }}">
                            @php
                                $iconClass = match($activity->type ?? '') {
                                    'appointment_created' => 'fa-calendar-plus',
                                    'appointment_confirmed' => 'fa-check-circle',
                                    'appointment_rejected' => 'fa-times-circle',
                                    'appointment_cancelled' => 'fa-ban',
                                    'appointment_deleted' => 'fa-trash-alt',
                                    'appointment_updated' => 'fa-edit',
                                    'payment_received' => 'fa-money-bill-wave',
                                    'profile_updated' => 'fa-user-edit',
                                    default => 'fa-bell',
                                };
                            @endphp
                            <i class="fas {{ $iconClass }}"></i>
                        </div>
                        <div class="notif-content">
                            <p class="notif-message">{{ $activity->message ?? 'نشاط جديد' }}</p>
                            <div class="notif-meta">
                                <span class="notif-time">
                                    <i class="far fa-clock"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                                <span class="notif-date">
                                    {{ $activity->created_at->format('Y/m/d - h:i A') }}
                                </span>
                                @if($activity->user)
                                    <span class="notif-patient">
                                        <i class="fas fa-user"></i>
                                        {{ $activity->user->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="notif-actions">
                            @if(!$activity->is_read)
                                <form action="{{ route('doctor.notifications.read', $activity->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="notif-mark-read" title="تمييز كمقروء">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @else
                                <span class="notif-read-badge">
                                    <i class="fas fa-check-double"></i>
                                    مقروء
                                </span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="notif-pagination">
                {{ $activities->links('vendor.pagination.notifications') }}
            </div>
        @endif
    </div>
</div>
@endsection
