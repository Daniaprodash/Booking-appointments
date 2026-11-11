@extends('admin.layouts.master')
@section('title', 'إدارة المواعيد')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-calendar-check"></i> إدارة المواعيد</h2>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h4><i class="fas fa-filter"></i> فلترة</h4>
    </div>
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="admin-filter-form">
            <div class="admin-form-group">
                <label>الطبيب</label>
                <select name="doctor_id" class="admin-form-control admin-form-select">
                    <option value="">الكل</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
                            د. {{ $doc->user?->name ?? '—' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-form-group">
                <label>المريض</label>
                <select name="patient_id" class="admin-form-control admin-form-select">
                    <option value="">الكل</option>
                    @foreach($patients as $pat)
                        <option value="{{ $pat->id }}" {{ request('patient_id') == $pat->id ? 'selected' : '' }}>
                            {{ $pat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="admin-form-group">
                <label>الحالة</label>
                <select name="status" class="admin-form-control admin-form-select">
                    <option value="">الكل</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            <div class="admin-form-group">
                <label>من تاريخ</label>
                <input type="date" name="date_from" class="admin-form-control" value="{{ request('date_from') }}">
            </div>
            <div class="admin-form-group">
                <label>إلى تاريخ</label>
                <input type="date" name="date_to" class="admin-form-control" value="{{ request('date_to') }}">
            </div>
            <div class="admin-form-group" style="align-self:flex-end">
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-search"></i> بحث
                </button>
                <a href="{{ route('admin.appointments.index') }}" class="admin-btn admin-btn-outline">
                    <i class="fas fa-redo"></i> إعادة تعيين
                </a>
            </div>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body" style="padding:0">
        <div style="overflow-x:auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>المريض</th>
                        <th>الطبيب</th>
                        <th>الخدمة</th>
                        <th>التاريخ</th>
                        <th>الوقت</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $apt)
                    <tr>
                        <td>{{ $apt->user?->name ?? '—' }}</td>
                        <td>د. {{ $apt->doctor?->user?->name ?? '—' }}</td>
                        <td>{{ $apt->service?->title ?? '—' }}</td>
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
                        <td>
                            <div class="admin-actions">
                                <form action="{{ route('admin.appointments.status', $apt->id) }}" method="POST" style="display:flex;gap:4px;align-items:center">
                                    @csrf @method('PATCH')
                                    <select name="status" class="admin-form-control admin-form-select" style="width:auto;padding:0.35rem 0.5rem;font-size:0.8rem;min-width:100px">
                                        <option value="pending" {{ $apt->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                        <option value="confirmed" {{ $apt->status == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                                        <option value="rejected" {{ $apt->status == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                                        <option value="cancelled" {{ $apt->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                    </select>
                                    <button type="submit" class="admin-btn admin-btn-sm admin-btn-primary" title="تحديث الحالة">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.appointments.destroy', $apt->id) }}" method="POST" class="admin-delete-form"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الموعد؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-sm admin-btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="admin-empty">
                                <i class="fas fa-calendar-times"></i>
                                <p>لا توجد مواعيد</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="admin-pagination">
    {{ $appointments->links() }}
</div>
@endsection
