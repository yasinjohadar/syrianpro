@extends('admin.layouts.master')

@section('page-title')
    ربط الأقراص
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'التخزين السحابي'],
                ['label' => 'ربط الأقراص'],
            ],
            'title' => 'ربط الأقراص (Disk Mappings)',
            'subtitle' => 'ربط أقراص Laravel بأماكن التخزين السحابي',
            'actions' => '<a href="' . route('admin.storage-disk-mappings.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة ربط</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-links-line',
                'label' => 'إجمالي الربط', 'value' => number_format($stats['total']),
                'hint' => 'أقراص مُعرَّفة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'نشط', 'value' => number_format($stats['active']),
                'hint' => 'قيد الاستخدام',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-pause-circle-line',
                'label' => 'غير نشط', 'value' => number_format($stats['inactive']),
                'hint' => 'معطّل',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-database-2-line',
                'label' => 'الأقراص', 'value' => number_format($stats['total']),
                'hint' => 'images، documents…',
            ])
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">قائمة الربط</span>
                <span class="table-count-badge">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 130px;">اسم القرص</th>
                                <th style="min-width: 140px;">التسمية</th>
                                <th style="min-width: 160px;">التخزين الأساسي</th>
                                <th style="min-width: 100px;">الحالة</th>
                                <th style="min-width: 100px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mappings as $mapping)
                            <tr>
                                <td class="text-muted fw-medium">{{ $mapping->id }}</td>
                                <td><code class="text-primary">{{ $mapping->disk_name }}</code></td>
                                <td class="fw-medium">{{ $mapping->label }}</td>
                                <td>
                                    @if($mapping->primaryStorage)
                                        <span class="badge-soft badge-soft-primary">{{ $mapping->primaryStorage->name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mapping->is_active)
                                        <span class="badge-soft badge-soft-success">نشط</span>
                                    @else
                                        <span class="badge-soft badge-soft-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.storage-disk-mappings.edit', $mapping->id) }}"
                                           class="action-btn action-btn--edit" title="تعديل">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteMappingModal"
                                                data-mapping-id="{{ $mapping->id }}"
                                                data-mapping-label="{{ $mapping->label }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-links-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد ربطات</h5>
                                        <p class="text-muted mb-3">اربط أقراص Laravel بأماكن التخزين السحابي.</p>
                                        <a href="{{ route('admin.storage-disk-mappings.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ri-add-line me-1"></i> إضافة ربط
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteMappingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteMappingForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف الربط</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف الربط</h4>
                    <p class="action-modal-user mb-2" id="deleteMappingLabel"></p>
                    <p class="text-muted mb-0 px-md-4">لا يمكن التراجع عن هذا الإجراء.</p>
                </div>
                <div class="modal-footer justify-content-center flex-wrap">
                    <button type="button" class="btn btn-light btn-lg px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-danger btn-lg px-4">
                        <i class="ri-delete-bin-line me-1"></i> نعم، احذف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteMappingModal');
    var deleteForm = document.getElementById('deleteMappingForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteMappingLabel').textContent = button.getAttribute('data-mapping-label');
            deleteForm.action = '{{ url('/admin/storage-disk-mappings') }}/' + button.getAttribute('data-mapping-id');
        });
    }
});
</script>
@endpush
