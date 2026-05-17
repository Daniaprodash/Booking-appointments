@extends('layouts.app')
@section('title', 'لوحة التحكم')

@section('content')
 <div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <div class="welcome-section">
                <div class="welcome-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="welcome-text">
                    <h1>مرحباً، <span class="user-name">{{ Auth::user()->name }}</span></h1>
                    <p class="welcome-subtitle">
                        <i class="fas fa-calendar-alt"></i>
                        إدارة مواعيدك وحجوزاتك الطبية من مكان واحد
                    </p>
                    @if(Auth::user()->role === 'user')
                        <div class="dashboard-profile-edit-wrap">
                            <a href="{{ route('patient.profile.edit') }}" class="btn-book-appointment dashboard-profile-edit-btn">
                                <i class="fas fa-user-edit"></i>
                                تعديل الملف الشخصي
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="stats-cards">
                <div class="stat-card stat-appointments">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $appointments->where('status', 'confirmed')->count() ?? 0 }}</div>
                        <div class="stat-label">مواعيد مؤكدة</div>
                    </div>
                </div>

                <div class="stat-card stat-pending">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $appointments->where('status', 'pending')->count() ?? 0 }}</div>
                        <div class="stat-label">قيد الانتظار</div>
                    </div>
                </div>

                <div class="stat-card stat-pending">
                    <div class="stat-icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $appointments->where('status', 'rejected')->count() ?? 0 }}</div>
                        <div class="stat-label">مرفوضة</div>
                    </div>
                </div>

                <div class="stat-card stat-total">
                    <div class="stat-icon">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-number">{{ $appointments->count() ?? 0 }}</div>
                        <div class="stat-label">إجمالي المواعيد</div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="dashboard-tabs">
        <button class="tab-btn active" onclick="switchTab('doctors')">
            <i class="fas fa-user-md"></i> الأطباء المتاحون
        </button>
        <button class="tab-btn" onclick="switchTab('appointments')">
            <i class="fas fa-calendar-check"></i> مواعيدي ({{ $appointments->count() ?? 0 }})
        </button>
        <button class="tab-btn" onclick="switchTab('booking')">
            <i class="fas fa-plus-circle"></i> حجز موعد جديد
        </button>

        <button class="tab-btn" onclick="switchTab('mail')">
            <i class="fas fa-message"></i>
        </button>
    </div>

    <!-- قسم الأطباء -->
    <div id="doctors" class="tab-content active">
        <div class="doctors-grid">
            @forelse($doctors ?? [] as $doctor)
                <div class="doctor-card-dash">
                    
                    <img 
                        src="{{ $doctor->user->image ?? asset('assets/images/default-doctor.jpg') }}" 
                        alt="{{ $doctor->user->name }}"
                        class="doctor-avatar"
                        onerror="this.src='{{ asset('assets/images/default-doctor.jpg') }}'">
                    <h3 class="doctor-name">{{ $doctor->user->name }}</h3>
                    <div class="doctor-specialty">{{ $doctor->specialty ?? 'طبيب أسنان' }}</div>
                    <div class="doctor-contact">
                        <span><i class="fas fa-phone"></i> {{ $doctor->user->phone_number ?? '---' }}</span>
                        <span><i class="fas fa-envelope"></i> {{ $doctor->user->email ?? '---' }}</span>
                    </div>
                    @if($doctor->bio)
                        <p style="font-size: 0.85rem; color: var(--muted); margin-top: 0.5rem;">
                            {{ Str::limit($doctor->bio, 80) }}
                        </p>
                    @endif
                    <button class="btn-book-appointment" onclick="bookDoctor({{ $doctor->id }}, '{{ $doctor->user->name }}')">
                        <i class="fas fa-calendar-plus"></i> احجز موعد
                    </button>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1/-1;">
                    <i class="fas fa-user-md"></i>
                    <p>لا توجد أطباء متاحين حالياً</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- قسم المواعيد -->
    <div id="appointments" class="tab-content">
        <div class="appointments-list">
            @forelse($appointments ?? [] as $appointment)
                <div class="appointment-card">
                    <div class="appointment-info">
                        <div class="appointment-doctor">
                            <i class="fas fa-user-md" style="color: var(--mint); margin-left: 0.5rem;"></i>
                            {{ $appointment->doctor->user->name ?? 'غير محدد' }}
                        </div>
                        <div class="appointment-service">
                            <i class="fas fa-tooth" style="margin-left: 0.5rem;"></i>
                            {{ $appointment->service->title ?? 'خدمة غير محددة' }}
                        </div>
                        <div class="appointment-datetime">
                            <span><i class="fas fa-calendar" style="margin-left: 0.3rem;"></i> {{ $appointment->appointment_date }}</span>
                            <span><i class="fas fa-clock" style="margin-left: 0.3rem;"></i> {{ $appointment->appointment_time }}</span>
                        </div>
                        @if($appointment->notes)
                            <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--muted);">
                                <i class="fas fa-sticky-note" style="margin-left: 0.3rem;"></i> {{ $appointment->notes }}
                            </p>
                        @endif
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-end;">
                        <span class="appointment-status status-{{ $appointment->status }}">
                            @if($appointment->status == 'pending')
                                قيد الانتظار
                            @elseif($appointment->status == 'confirmed')
                                مؤكد
                            @elseif($appointment->status == 'rejected')
                                مرفوض
                            @else
                                ملغي
                            @endif
                        </span>
                        <div class="appointment-actions">
                            @if($appointment->status != 'cancelled')
                                <button
                                    type="button"
                                    class="btn-action btn-edit"
                                    data-id="{{ $appointment->id }}"
                                    data-doctor-id="{{ $appointment->doctor_id }}"
                                    data-service-id="{{ $appointment->service_id }}"
                                    data-date="{{ $appointment->appointment_date }}"
                                    data-time="{{ $appointment->appointment_time }}"
                                    data-notes="{{ $appointment->notes }}"
                                    onclick="openEditAppointmentModal(this)">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                                <form action="{{ route('appointments.cancel', $appointment->id) }}"
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الموعد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-cancel">
                                        <i class="fas fa-times"></i> إلغاء
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>لا توجد مواعيد محجوزة حالياً</p>
                    <button class="btn-book-appointment" onclick="switchTab('booking')" style="margin-top: 1rem; max-width: 200px;">
                        احجز موعد جديد
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- قسم الحجز -->
    <div id="booking" class="tab-content">
        <div class="booking-form-card">
            <h2 class="form-title">
                <i class="fas fa-calendar-plus"></i>
                حجز موعد جديد
            </h2>

            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <div class="form-group">
                    <label for="doctor_id"><i class="fas fa-user-md" style="margin-left: 0.5rem;"></i>اختر الطبيب</label>
                    <select name="doctor_id" id="doctor_id" required>
                        <option value="">-- اختر الطبيب --</option>
                        @foreach($doctors ?? [] as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} - {{ $doctor->specialty ?? 'طبيب أسنان' }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p style="color: var(--pink); font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="service_id"><i class="fas fa-tooth" style="margin-left: 0.5rem;"></i>اختر الخدمة</label>
                    <select name="service_id" id="service_id" required>
                        <option value="">-- اختر الخدمة --</option>
                        @foreach($services ?? [] as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p style="color: var(--pink); font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="appointment_date"><i class="fas fa-calendar" style="margin-left: 0.5rem;"></i>تاريخ الموعد</label>
                    <input 
                        type="date" 
                        name="appointment_date" 
                        id="appointment_date" 
                        value="{{ old('appointment_date') }}"
                        min="{{ date('Y-m-d') }}"
                        required>
                    @error('appointment_date')
                        <p style="color: var(--pink); font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="appointment_time"><i class="fas fa-clock" style="margin-left: 0.5rem;"></i>وقت الموعد</label>
                    <input 
                        type="time" 
                        name="appointment_time" 
                        id="appointment_time" 
                        value="{{ old('appointment_time') }}"
                        required>
                    @error('appointment_time')
                        <p style="color: var(--pink); font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes"><i class="fas fa-sticky-note" style="margin-left: 0.5rem;"></i>ملاحظات (اختياري)</label>
                    <textarea 
                        name="notes" 
                        id="notes" 
                        placeholder="أضف أي ملاحظات أو معلومات إضافية...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p style="color: var(--pink); font-size: 0.85rem; margin-top: 0.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-check-circle"></i> تأكيد الحجز
                </button>
            </form>
        </div>
    </div>

<!-- قسم البريد -->
<div id="mail" class="tab-content">
    <h1 class="mail-title">البريد الوارد</h1>

    <div class="messages-container">

        @forelse ($messages as $msg)
            <div class="message-card">
                <div class="msg-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>

                <div class="msg-content">
                    <h3 class="msg-title">{{ $msg->subject }}</h3>

                    <p class="msg-text">
                        {{ Str::limit($msg->message, 50) }}
                    </p>

                    <span class="msg-doctor">
                        الطبيب: {{ $msg->doctor->user->name ?? 'غير معروف' }}
                    </span>
                </div>

                <div class="msg-date">
                    <span>{{ $msg->created_at->format('Y-m-d') }}</span>
                </div>
            </div>
        @empty

            <p class="no-messages">لا توجد رسائل حتى الآن.</p>

        @endforelse

    </div>
</div>

<!-- Edit Appointment Modal -->
<div id="editAppointmentModal" class="appointment-modal-overlay" aria-hidden="true">
    <div class="appointment-modal-card">
        <div class="appointment-modal-header">
            <h3><i class="fas fa-edit"></i> تعديل الموعد</h3>
            <button type="button" class="appointment-modal-close" onclick="closeEditAppointmentModal()">&times;</button>
        </div>

        <form id="editAppointmentForm" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="appointment-modal-body">
                <div class="form-group">
                    <label for="edit_doctor_id"><i class="fas fa-user-md" style="margin-left: 0.5rem;"></i>اختر الطبيب</label>
                    <select name="doctor_id" id="edit_doctor_id" required>
                        <option value="">-- اختر الطبيب --</option>
                        @foreach($doctors ?? [] as $doctor)
                            <option value="{{ $doctor->id }}">
                                {{ $doctor->user->name }} - {{ $doctor->specialty ?? 'طبيب أسنان' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_service_id"><i class="fas fa-tooth" style="margin-left: 0.5rem;"></i>اختر الخدمة</label>
                    <select name="service_id" id="edit_service_id" required>
                        <option value="">-- اختر الخدمة --</option>
                        @foreach($services ?? [] as $service)
                            <option value="{{ $service->id }}">{{ $service->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_appointment_date"><i class="fas fa-calendar" style="margin-left: 0.5rem;"></i>تاريخ الموعد</label>
                    <input type="date" name="appointment_date" id="edit_appointment_date" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="edit_appointment_time"><i class="fas fa-clock" style="margin-left: 0.5rem;"></i>وقت الموعد</label>
                    <input type="time" name="appointment_time" id="edit_appointment_time" required>
                </div>

                <div class="form-group">
                    <label for="edit_notes"><i class="fas fa-sticky-note" style="margin-left: 0.5rem;"></i>ملاحظات (اختياري)</label>
                    <textarea name="notes" id="edit_notes" placeholder="أضف ملاحظات الموعد..."></textarea>
                </div>
            </div>

            <div class="appointment-modal-footer">
                <button type="button" class="btn-action appointment-modal-cancel" onclick="closeEditAppointmentModal()">إلغاء</button>
                <button type="submit" class="btn-submit appointment-modal-save">
                    <i class="fas fa-check-circle"></i> حفظ التعديل
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // إخفاء جميع التبويبات
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // إزالة active من جميع الأزرار
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // إظهار التبويب المحدد
        document.getElementById(tabName).classList.add('active');
        
        // إضافة active للزر المحدد
        event.target.classList.add('active');
    }

    function bookDoctor(doctorId, doctorName) {
        // التبديل إلى تبويب الحجز
        switchTab('booking');
        
        // تحديد الطبيب في القائمة
        document.getElementById('doctor_id').value = doctorId;
        
        // تمرير الحدث للزر
        const event = new Event('click');
        document.querySelector('.tab-btn:last-child(2)').dispatchEvent(event);
    }

    function openEditAppointmentModal(button) {
        const appointmentId = button.getAttribute('data-id');
        const doctorId = button.getAttribute('data-doctor-id');
        const serviceId = button.getAttribute('data-service-id');
        const appointmentDate = button.getAttribute('data-date');
        const appointmentTime = button.getAttribute('data-time');
        const notes = button.getAttribute('data-notes') ?? '';

        const form = document.getElementById('editAppointmentForm');
        form.action = '{{ route("appointments.update", ":id") }}'.replace(':id', appointmentId);

        document.getElementById('edit_doctor_id').value = doctorId || '';
        document.getElementById('edit_service_id').value = serviceId || '';
        document.getElementById('edit_appointment_date').value = appointmentDate || '';
        document.getElementById('edit_appointment_time').value = appointmentTime || '';
        document.getElementById('edit_notes').value = notes;

        const modal = document.getElementById('editAppointmentModal');
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeEditAppointmentModal() {
        const modal = document.getElementById('editAppointmentModal');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditAppointmentModal();
        }
    });

    document.addEventListener('click', function(e) {
        const modal = document.getElementById('editAppointmentModal');
        if (e.target === modal) {
            closeEditAppointmentModal();
        }
    });
</script>
@endsection