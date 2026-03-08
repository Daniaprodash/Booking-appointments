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
                        <span><i class="fas fa-phone"></i> {{ $doctor->phone ?? '---' }}</span>
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
                                <button class="btn-action btn-edit">
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
        <!-- مثال لرسالة واحدة (كررها داخل foreach) -->
        <div class="message-card">
            <div class="msg-icon">
                <i class="fa-solid fa-envelope"></i>
            </div>

            <div class="msg-content">
                <h3 class="msg-title">عنوان الرسالة</h3>
                <p class="msg-text">هذا نص مختصر عن محتوى الرسالة...</p>
                <span class="msg-doctor">الدكتور: أحمد علي</span>
            </div>

            <div class="msg-date">
                <span>2024-01-10</span>
            </div>
        </div>
    </div>
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

   
</script>
@endsection