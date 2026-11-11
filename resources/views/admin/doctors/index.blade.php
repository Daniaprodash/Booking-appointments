@extends('admin.layouts.master')
@section('title', 'الأطباء')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-user-md"></i> الأطباء</h2>
    <a href="{{ route('admin.doctors.create') }}" class="admin-btn admin-btn-primary">
        <i class="fas fa-plus"></i> إضافة طبيب
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-body" style="padding:0">
        <div style="overflow-x:auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد</th>
                        <th>التخصص</th>
                        <th>تاريخ الإضافة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <img src="{{ $doctor->user?->image ? asset($doctor->user->image) : asset('assets/images/default-doctor.jpg') }}"
                                     class="admin-avatar" alt="">
                                <span style="font-weight:600">{{ $doctor->user?->name ?? 'غير معروف' }}</span>
                            </div>
                        </td>
                        <td>{{ $doctor->user?->email ?? '—' }}</td>
                        <td>{{ $doctor->specialty ?? '—' }}</td>
                        <td>{{ $doctor->created_at }}</td>
                        <td>
                            <div class="admin-actions">
                                <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="admin-btn admin-btn-sm admin-btn-outline">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="admin-btn admin-btn-sm admin-btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" class="admin-delete-form"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الطبيب؟ سيتم حذف جميع المواعيد المرتبطة به.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-sm admin-btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="admin-empty">
                                <i class="fas fa-user-md"></i>
                                <p>لا يوجد أطباء بعد</p>
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
    {{ $doctors->links() }}
</div>
@endsection
