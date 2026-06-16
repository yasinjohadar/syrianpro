@extends('admin.layouts.master')

@section('page-title')
    النسخ الاحتياطية
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'النسخ الاحتياطي'],
                ['label' => 'النسخ'],
            ],
            'title' => 'النسخ الاحتياطية',
            'subtitle' => 'إدارة النسخ اليدوية والمجدولة',
            'actions' => '<div class="d-flex gap-3 flex-wrap">'
                . '<a href="' . route('admin.backup-schedules.index') . '" class="btn btn-link text-muted fw-bold text-decoration-none p-0"><i class="ri-calendar-schedule-line me-1 fs-18"></i> الجدولة</a>'
                . '<a href="' . route('admin.backups.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> نسخة جديدة</a>'
                . '</div>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-database-2-line',
                'label' => 'إجمالي النسخ', 'value' => number_format($stats['total'] ?? 0),
                'hint' => number_format($stats['filtered'] ?? 0) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'مكتملة', 'value' => number_format($stats['completed'] ?? 0),
                'hint' => 'نجحت',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-close-circle-line',
                'label' => 'فاشلة', 'value' => number_format($stats['failed'] ?? 0),
                'hint' => 'تحتاج مراجعة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-hard-drive-2-line',
                'label' => 'الحجم الإجمالي', 'value' => number_format(($stats['total_size'] ?? 0) / 1024 / 1024, 2) . ' MB',
                'hint' => number_format($stats['running'] ?? 0) . ' قيد التنفيذ',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية النسخ</div>
            <div class="filter-panel__subtitle">فلتر حسب الحالة أو النوع أو مكان التخزين</div>
            <form method="GET" action="{{ route('admin.backups.index') }}" id="backupsFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">الكل</option>
                            @foreach(\App\Models\Backup::STATUSES as $key => $label)
                                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">نوع النسخ</label>
                        <select name="backup_type" class="form-select">
                            <option value="">الكل</option>
                            @foreach(\App\Models\Backup::BACKUP_TYPES as $key => $label)
                                <option value="{{ $key }}" @selected(request('backup_type') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">مكان التخزين</label>
                        <select name="storage_config_id" class="form-select">
                            <option value="">الكل</option>
                            @foreach($storageConfigs ?? [] as $config)
                                <option value="{{ $config->id }}" @selected(request('storage_config_id') == $config->id)>{{ $config->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill" id="backupsSearchBtn">
                            <i class="ri-search-2-line me-1"></i> تصفية
                        </button>
                        <a href="{{ route('admin.backups.index') }}" class="btn btn-light border" title="إعادة تعيين">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">قائمة النسخ</span>
                <span class="table-count-badge">{{ number_format($stats['filtered'] ?? 0) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" id="backups-table-wrap">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th style="min-width:160px;">الاسم</th>
                                <th style="min-width:100px;">النوع</th>
                                <th style="min-width:140px;">التخزين</th>
                                <th style="min-width:100px;">الحالة</th>
                                <th style="min-width:90px;">الحجم</th>
                                <th style="min-width:130px;">التاريخ</th>
                                <th style="min-width:120px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backups as $backup)
                            <tr data-backup-id="{{ $backup->id }}" data-status="{{ $backup->status }}">
                                <td class="text-muted fw-medium">{{ $backup->id }}</td>
                                <td>
                                    <a href="{{ route('admin.backups.show', $backup->id) }}"
                                       class="fw-bold row-title-link text-decoration-none">
                                        {{ $backup->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-soft badge-soft-info">
                                        {{ \App\Models\Backup::BACKUP_TYPES[$backup->backup_type] }}
                                    </span>
                                </td>
                                <td>
                                    @if($backup->storageConfig)
                                        <span class="badge-soft badge-soft-primary">{{ $backup->storageConfig->name }}</span>
                                        <small class="d-block text-muted mt-1">{{ $backup->storage_driver }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="backup-status-cell">
                                    @if($backup->status === 'completed')
                                        <span class="badge-soft badge-soft-success">مكتمل</span>
                                    @elseif($backup->status === 'failed')
                                        <span class="badge-soft badge-soft-danger">فشل</span>
                                    @elseif($backup->status === 'running')
                                        <span class="badge-soft badge-soft-warning">قيد التنفيذ</span>
                                    @else
                                        <span class="badge-soft badge-soft-secondary">معلق</span>
                                    @endif
                                </td>
                                <td><span class="meta-text">{{ $backup->getFileSize() }}</span></td>
                                <td>
                                    <span class="meta-text">
                                        <i class="ri-calendar-line"></i>
                                        {{ $backup->created_at->format('Y-m-d H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.backups.show', $backup->id) }}"
                                           class="action-btn action-btn--view" title="عرض">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if($backup->status === 'completed')
                                        <a href="{{ route('admin.backups.download', $backup->id) }}"
                                           class="action-btn action-btn--edit" title="تحميل">
                                            <i class="ri-download-2-line"></i>
                                        </a>
                                        @endif
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteBackupModal"
                                                data-confirm-action="{{ route('admin.backups.destroy', $backup->id) }}"
                                                data-confirm-subject="{{ $backup->name }}"
                                                data-confirm-subject-meta="{{ \App\Models\Backup::BACKUP_TYPES[$backup->backup_type] }} · {{ $backup->getFileSize() }} · {{ $backup->created_at->format('Y-m-d H:i') }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-database-2-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد نسخ احتياطية</h5>
                                        <p class="text-muted mb-3">أنشئ أول نسخة احتياطية الآن.</p>
                                        <a href="{{ route('admin.backups.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ri-add-line me-1"></i> نسخة جديدة
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($backups->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">
                        {{ $backups->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<x-admin.confirm-modal
    id="deleteBackupModal"
    title="تأكيد حذف النسخة"
    message="لا يمكن التراجع عن هذا الإجراء."
    dynamic
    form-id="deleteBackupForm"
    subject-id="deleteBackupName"
    subject-meta-id="deleteBackupMeta"
    method="DELETE"
    confirm-text="نعم، احذف"
/>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var filterForm = document.getElementById('backupsFilterForm');
    var searchBtn = document.getElementById('backupsSearchBtn');
    if (filterForm && searchBtn) {
        filterForm.addEventListener('submit', function () {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري البحث...';
        });
    }

    var hasActive = document.querySelector('tr[data-status="pending"], tr[data-status="running"]');
    if (hasActive) {
        setInterval(function () {
            if (document.querySelector('tr[data-status="pending"], tr[data-status="running"]')) {
                window.location.reload();
            }
        }, 15000);
    }
});
</script>
@endpush
