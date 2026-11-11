@extends('admin.layouts.master')
@section('title', 'السجلات الطبية')

@section('content')
<div class="admin-page-header">
    <h2><i class="fas fa-file-medical"></i> السجلات الطبية</h2>
</div>

<div class="admin-card">
    <div class="admin-card-body" style="padding:0">
        <div style="overflow-x:auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>المريض</th>
                        <th>البريد</th>
                        <th>فصيلة الدم</th>
                        <th>آخر تحديث</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td><span style="font-weight:600">{{ $record->patient_name }}</span></td>
                        <td>{{ $record->patient_email }}</td>
                        <td>{{ $record->blood_type ?? '—' }}</td>
                        <td>{{ $record->updated_at ?? '—' }}</td>
                        <td>
                            <div class="admin-actions">
                                <a href="{{ route('admin.medical-records.show', $record->id) }}" class="admin-btn admin-btn-sm admin-btn-outline">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                <form action="{{ route('admin.medical-records.destroy', $record->id) }}" method="POST" class="admin-delete-form"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل الطبي؟')">
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
                                <i class="fas fa-folder-open"></i>
                                <p>لا توجد سجلات طبية</p>
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
    {{ $records->links() }}
</div>
@endsection
