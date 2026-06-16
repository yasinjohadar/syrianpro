@extends('admin.layouts.master')

@section('page-title')
    أماكن التخزين
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
                ['label' => 'أماكن التخزين'],
            ],
            'title' => 'أماكن التخزين',
            'subtitle' => 'إدارة وجهات التخزين السحابي والمحلي',
            'actions' => '<a href="' . route('admin.storage.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة مكان تخزين</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-hard-drive-3-line',
                'label' => 'إجمالي الأماكن', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['drivers']) . ' أنواع مزود',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'نشط', 'value' => number_format($stats['active']),
                'hint' => 'جاهز للاستخدام',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-pause-circle-line',
                'label' => 'غير نشط', 'value' => number_format($stats['inactive']),
                'hint' => 'معطّل مؤقتاً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-cloud-line',
                'label' => 'أنواع المزود', 'value' => number_format($stats['drivers']),
                'hint' => 'S3، R2، محلي…',
            ])
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة أماكن التخزين</span>
                    <span class="table-count-badge">{{ number_format($stats['total']) }}</span>
                </div>
                <a href="{{ route('admin.storage.analytics') }}" class="btn btn-sm btn-light border">
                    <i class="ri-bar-chart-box-line me-1"></i> الإحصائيات
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 180px;">الاسم</th>
                                <th style="min-width: 140px;">النوع</th>
                                <th style="min-width: 100px;">الحالة</th>
                                <th style="min-width: 90px;">الأولوية</th>
                                <th style="min-width: 130px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($configs as $config)
                            <tr>
                                <td class="text-muted fw-medium">{{ $config->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                            <i class="ri-cloud-line"></i>
                                        </span>
                                        <a href="{{ route('admin.storage.edit', $config->id) }}"
                                           class="fw-bold row-title-link text-decoration-none">
                                            {{ $config->name }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-soft badge-soft-info">
                                        {{ App\Models\AppStorageConfig::DRIVERS[$config->driver] ?? $config->driver }}
                                    </span>
                                </td>
                                <td>
                                    @if($config->is_active)
                                        <span class="badge-soft badge-soft-success">نشط</span>
                                    @else
                                        <span class="badge-soft badge-soft-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="meta-text"><i class="ri-sort-desc"></i> {{ $config->priority }}</span>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.storage.edit', $config->id) }}"
                                           class="action-btn action-btn--edit" title="تعديل">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn action-btn--view test-storage"
                                                title="اختبار الاتصال"
                                                data-config-id="{{ $config->id }}">
                                            <i class="ri-wifi-line"></i>
                                        </button>
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteStorageModal"
                                                data-config-id="{{ $config->id }}"
                                                data-config-name="{{ $config->name }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-hard-drive-3-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد إعدادات تخزين</h5>
                                        <p class="text-muted mb-3">أضف أول مكان تخزين سحابي أو محلي.</p>
                                        <a href="{{ route('admin.storage.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ri-add-line me-1"></i> إضافة مكان تخزين
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

<div class="modal fade modal-user-action" id="deleteStorageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteStorageForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف مكان التخزين</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف مكان التخزين</h4>
                    <p class="action-modal-user mb-2" id="deleteStorageName"></p>
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
    var deleteModal = document.getElementById('deleteStorageModal');
    var deleteForm = document.getElementById('deleteStorageForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteStorageName').textContent = button.getAttribute('data-config-name');
            deleteForm.action = '{{ url('/admin/storage') }}/' + button.getAttribute('data-config-id');
        });
    }

    document.querySelectorAll('.test-storage').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var configId = this.dataset.configId;
            var originalHtml = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            fetch('{{ url('/admin/storage') }}/' + configId + '/test', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (window.adminUiToast) {
                    window.adminUiToast(data.success ? 'success' : 'error', data.message || (data.success ? 'الاتصال ناجح' : 'فشل الاتصال'));
                } else {
                    alert(data.success ? ('✓ ' + (data.message || 'الاتصال ناجح')) : ('✗ ' + (data.message || 'فشل الاتصال')));
                }
            })
            .catch(function (error) {
                alert('حدث خطأ: ' + error.message);
            })
            .finally(function () {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
        });
    });
});
</script>
@endpush
