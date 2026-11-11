@extends('admin.layouts.master')
@section('title', 'المرضى')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-users"></i> المرضى</h2>
    <a href="{{ route('admin.patients.create') }}" class="admin-btn admin-btn-primary">
        <i class="fas fa-plus"></i> إضافة مريض
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
                        <th>الهاتف</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <img src="{{ $patient->image ? asset($patient->image) : asset('assets/images/patient.webp') }}"
                                     class="admin-avatar" alt="">
                                <span style="font-weight:600">{{ $patient->name }}</span>
                            </div>
                        </td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->phone_number ?? '—' }}</td>
                        <td>{{ $patient->created_at }}</td>
                        <td>
                            <div class="admin-actions">
                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="admin-btn admin-btn-sm admin-btn-outline">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.patients.edit', $patient->id) }}" class="admin-btn admin-btn-sm admin-btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" class="admin-delete-form"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المريض؟')">
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
                                <i class="fas fa-users"></i>
                                <p>لا يوجد مرضى</p>
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
    {{ $patients->links() }}
</div>
@endsection
