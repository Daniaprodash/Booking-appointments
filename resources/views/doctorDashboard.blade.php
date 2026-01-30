@extends('layouts.app')
@section('title','لوحة الطبيب')
@section('content')
    <div class="doctor-dashboard-container" role="main">

        <!-- Sidebar -->
        <aside class="doctor-dashboard-sidebar" aria-label="تنقل الطبيب">
            <div class="doctor-dashboard-brand">
                <div class="doctor-dashboard-logo"><i class="fas fa-tooth"></i></div>
                <div>
                    <div class="doctor-dashboard-title">حجز المواعيد</div>
                    <div style="font-size:0.85rem;color:var(--muted)">لوحة تحكم الطبيب</div>
                </div>
            </div>

            <div class="doctor-dashboard-profile">
                <img src="{{ auth()->user()->avatar ?? asset('assets/images/default-doctor.jpg') }}" alt="صورة" class="doctor-dashboard-avatar">
                <div class="doctor-dashboard-profile-info">
                    <h4>{{ auth()->user()->name ?? 'د. اسم الطبيب' }}</h4>
                    <p>{{ $doctor->specialty }}</p>
                </div>
            </div>

            <nav class="doctor-dashboard-nav-list" aria-label="قائمة التنقل">
                <a class="doctor-dashboard-nav-item active" href="#"><i class="fas fa-tachometer-alt"></i> لوحة المعلومات</a>
                <a class="doctor-dashboard-nav-item" href="#"><i class="fas fa-file-medical"></i> السجلات الطبية</a>
                <a class="doctor-dashboard-nav-item" href="#"><i class="fas fa-wallet"></i> الفواتير والمدفوعات</a>
                <a class="doctor-dashboard-nav-item" href="#"><i class="fas fa-cog"></i> الإعدادات</a>
                <a class="doctor-dashboard-nav-item" href="#"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
            </nav>

            <div class="doctor-dashboard-sidebar-footer">
                آخر تسجيل دخول: <br><strong style="color:var(--primary-2)">{{ auth()->user()->last_login_at ?? 'لم يتم تسجيل دخول بعد' }}</strong>
            </div>
        </aside>

        <!-- Main -->
        <main class="doctor-dashboard-main">
            <div class="doctor-dashboard-topbar">
                <div class="doctor-dashboard-searchbox">
                    <i class="fas fa-search" style="color:var(--muted)"></i>
                    <input placeholder="ابحث عن مريض، موعد أو ملاحظة">
                </div>

                <div class="doctor-dashboard-actions">
                    <div class="doctor-dashboard-icon-btn" title="إشعارات">
                        <i class="fas fa-bell" style="color:var(--primary-2)"></i>
                    </div>
                    <div class="doctor-dashboard-icon-btn" title="مساعدة">
                        <i class="fas fa-question" style="color:var(--primary-2)"></i>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div style="background:linear-gradient(90deg,var(--primary-1),var(--primary-2));color:#fff;padding:12px 16px;border-radius:10px;margin-bottom:16px;display:flex;align-items:center;gap:10px;box-shadow:0 4px 12px rgba(15,179,161,0.2)">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div style="background:linear-gradient(90deg,#ff6b6b,#ee5a52);color:#fff;padding:12px 16px;border-radius:10px;margin-bottom:16px;display:flex;align-items:center;gap:10px;box-shadow:0 4px 12px rgba(238,90,82,0.2)">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <section class="doctor-dashboard-stats-grid" aria-label="إحصائيات سريعة">
                <div class="doctor-dashboard-stat-card">
                    <div class="doctor-dashboard-stat-icon" style="background:linear-gradient(135deg,var(--primary-1),var(--primary-2))">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="doctor-dashboard-stat-content">
                        <h3>{{ $appointments->count() }}</h3>
                        <p>عدد المواعيد</p>
                    </div>
                </div>

                <div class="doctor-dashboard-stat-card">
                    <div class="doctor-dashboard-stat-icon" style="background:#ffb86b">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <div class="doctor-dashboard-stat-content">
                        <h3>{{ $appointments->count() }}</h3>
                        <p>عدد المرضى</p>
                    </div>
                </div>

                <div class="doctor-dashboard-stat-card">
                    <div class="doctor-dashboard-stat-icon" style="background:#7c5cff">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="doctor-dashboard-stat-content">
                        <h3>{{ $percentage }}</h3>
                        <p>نسبة الحضور</p>
                    </div>
                </div>

                <div class="doctor-dashboard-stat-card">
                    <div class="doctor-dashboard-stat-icon" style="background:#ff6b95">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="doctor-dashboard-stat-content">
                        <h3>$1,240</h3>
                        <p>إجمالي اليوم</p>
                    </div>
                </div>
            </section>

            <div class="doctor-dashboard-layout">
                <section class="doctor-dashboard-card">
                    <h4>المواعيد القادمة</h4>
                    <div class="doctor-dashboard-appointments-list" aria-live="polite">
                        @forelse($appointments as $appointment)
                        <div class="doctor-dashboard-appointment">
                            <div class="meta">
                                <div class="doctor-dashboard-patient">
                                    <img src="{{ asset('assets/images/patient.webp') }}" alt="مريض">
                                    <div>
                                        <div style="font-weight:700">{{ $appointment->user->name }}</div>
                                        <div style="font-size:0.9rem;color:var(--muted)">{{ $appointment->service->title }} - {{ $appointment->appointment_date }}</div>
                                    </div>
                                </div>
                            </div>
                            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                                <div class="doctor-dashboard-time-badge">{{ $appointment->appointment_time }} </div>
                               
                                <div style="font-size:0.9rem;color:var(--muted);padding:4px 8px;background:rgba(138,147,173,0.1);border-radius:6px">
                                    @if($appointment->status == 'pending')
                                        قيد الانتظار
                                    @elseif($appointment->status == 'confirmed')
                                        مؤكد
                                    @elseif($appointment->status == 'rejected')
                                        مرفوض
                                    @else
                                        ملغي
                                    @endif
                                </div>
                                <div style="display:flex;gap:6px;flex-wrap:wrap">
                                    @if($appointment->status == 'pending')
                                        <form action="{{ route('appointments.confirm', $appointment->id) }}" method="POST" style="margin:0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="appointment-action-btn confirm-btn" title="تأكيد الموعد">
                                                <i class="fas fa-check"></i>
                                                <span>تأكيد</span>
                                            </button>
                                        </form>
                                        <form action="{{ route('appointments.reject', $appointment->id) }}" method="POST" style="margin:0" onsubmit="return confirm('هل أنت متأكد من رفض هذا الموعد؟')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="appointment-action-btn reject-btn" title="رفض الموعد">
                                                <i class="fas fa-times"></i>
                                                <span>رفض</span>
                                            </button>
                                        </form>
                                    @endif
                                    @if(in_array($appointment->status, ['cancelled', 'rejected']))
                                        <form action="{{ route('appointments.delete', $appointment->id) }}" method="POST" style="margin:0" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموعد؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="appointment-action-btn delete-btn" title="حذف الموعد">
                                                <i class="fas fa-trash"></i>
                                                <span>حذف</span>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" class="appointment-action-btn email-btn" title="إرسال بريد للمريض" 
                                        data-appointment-id="{{ $appointment->id }}" 
                                        data-patient-name="{{ $appointment->user->name }}" 
                                        data-patient-email="{{ $appointment->user->email }}"
                                        onclick="openEmailModal(this)">
                                        <i class="fas fa-envelope"></i>
                                        <span>إرسال بريد</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center;padding:20px;color:var(--muted)">
                            لا توجد مواعيد متاحة
                        </div>
                        @endforelse
                        @if(!$showAll && $allAppointmentsCount > 3)
                        <a href="{{ route('doctorDashboard', ['show_all' => true]) }}" class="doctor-dashboard-nav-item" style="justify-content:center;margin-top:12px;background:transparent;color:var(--primary-2);border-radius:8px">
                            عرض كل المواعيد ({{ $allAppointmentsCount }})
                        </a>
                        @elseif($showAll && $allAppointmentsCount > 3)
                        <a href="{{ route('doctorDashboard') }}" class="doctor-dashboard-nav-item" style="justify-content:center;margin-top:12px;background:transparent;color:var(--primary-2);border-radius:8px">
                            عرض آخر 3 مواعيد
                        </a>
                        @endif
                    </div>

                    <hr style="margin:16px 0">

                    <h4>نشاط حديث</h4>
                    <div class="doctor-dashboard-recent-activity">
                        <div class="doctor-dashboard-activity">
                            <i class="fas fa-check-circle" style="color:var(--primary-2);margin-top:4px"></i>
                            <div>
                                <div style="font-weight:700">تم تأكيد موعد مريم علي</div>
                                <div style="font-size:0.9rem;color:var(--muted)">قبل 10 دقائق</div>
                            </div>
                        </div>

                        <div class="doctor-dashboard-activity">
                            <i class="fas fa-file-medical" style="color:#7c5cff;margin-top:4px"></i>
                            <div>
                                <div style="font-weight:700">تم رفع تقرير جديد للمريض خالد</div>
                                <div style="font-size:0.9rem;color:var(--muted)">قبل ساعة</div>
                            </div>
                        </div>
                    </div>
                </section>

                <aside>
                    <div class="doctor-dashboard-card" style="margin-bottom:16px">
                        <h4>التقويم</h4>
                        <div class="doctor-dashboard-calendar">تقويم تفاعلي (مكان مخصص لدمج مكتبة التقويم لاحقاً)</div>
                    </div>

                    <div class="doctor-dashboard-card">
                         <h4>الملف الشخصي</h4>
                        <div style="display:flex;gap:12px;align-items:center">
                            <img src="{{ auth()->user()->avatar ?? asset('assets/images/default-doctor.jpg') }}" width="64" height="64" class="doctor-dashboard-avatar" style="object-fit:cover">
                            <div>
                                <div style="font-weight:700">{{ auth()->user()->name ?? 'د. اسم الطبيب' }}</div>
                                <div style="color:var(--muted)">{{ auth()->user()->email ?? 'email@domain.com' }}</div>
                                <div style="margin-top:8px">
                                    <a class="doctor-dashboard-nav-item" style="padding:8px 10px;border-radius:8px;background:linear-gradient(90deg,var(--primary-1),var(--primary-2));color:#fff;font-size:0.9rem">
                                        تحرير الملف
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>

        </main>
    </div>

    <!-- Email Modal -->
    <div id="emailModal" class="email-modal" style="display:none">
        <div class="email-modal-content">
            <div class="email-modal-header">
                <h4>إرسال بريد للمريض</h4>
                <button type="button" class="email-modal-close" onclick="closeEmailModal()">&times;</button>
            </div>
            <form id="emailForm" action="" method="POST">
                @csrf
                <div class="email-modal-body">
                    <div style="margin-bottom:12px">
                        <label style="display:block;margin-bottom:6px;font-weight:600;color:var(--accent-contrast)">المريض:</label>
                        <div id="patientInfo" style="padding:8px;background:rgba(138,147,173,0.1);border-radius:6px;font-size:0.9rem"></div>
                    </div>
                    <div style="margin-bottom:12px">
                        <label for="emailSubject" style="display:block;margin-bottom:6px;font-weight:600;color:var(--accent-contrast)">موضوع الرسالة:</label>
                        <input type="text" id="emailSubject" name="subject" required placeholder="أدخل موضوع الرسالة" style="width:100%;padding:10px;border:1px solid rgba(138,147,173,0.2);border-radius:8px;font-size:0.9rem;outline:none;transition:border 0.2s">
                    </div>
                    <div style="margin-bottom:12px">
                        <label for="emailMessage" style="display:block;margin-bottom:6px;font-weight:600;color:var(--accent-contrast)">محتوى الرسالة:</label>
                        <textarea id="emailMessage" name="message" rows="6" required placeholder="أدخل محتوى الرسالة" style="width:100%;padding:10px;border:1px solid rgba(138,147,173,0.2);border-radius:8px;font-size:0.9rem;outline:none;resize:vertical;transition:border 0.2s;font-family:inherit"></textarea>
                    </div>
                </div>
                <div class="email-modal-footer">
                    <button type="button" class="appointment-action-btn" onclick="closeEmailModal()" style="background:#8a93ad;margin-left:8px">
                        <span>إلغاء</span>
                    </button>
                    <button type="submit" class="appointment-action-btn email-btn">
                        <i class="fas fa-paper-plane"></i>
                        <span>إرسال</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- send email to patient -->
    <script>
        function openEmailModal(button) {
            const appointmentId = button.getAttribute('data-appointment-id');
            const patientName = button.getAttribute('data-patient-name');
            const patientEmail = button.getAttribute('data-patient-email');
            
            document.getElementById('emailForm').action = '{{ route("appointments.send-email", ":id") }}'.replace(':id', appointmentId);
            document.getElementById('patientInfo').textContent = patientName + ' (' + patientEmail + ')';
            document.getElementById('emailModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeEmailModal() {
            document.getElementById('emailModal').style.display = 'none';
            document.getElementById('emailForm').reset();
            document.body.style.overflow = 'auto';
        }

        // إغلاق الـ modal عند النقر خارجه
        document.addEventListener('DOMContentLoaded', function() {
            const emailModal = document.getElementById('emailModal');
            if (emailModal) {
                emailModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEmailModal();
                    }
                });
            }
            
            // إغلاق الـ modal عند الضغط على زر ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('emailModal');
                    if (modal && modal.style.display === 'flex') {
                        closeEmailModal();
                    }
                }
            });
        });
    </script>
@endsection
