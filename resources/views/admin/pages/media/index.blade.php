@php
function formatBytesHelper($bytes) {
    if ($bytes === 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) { $bytes /= 1024; $i++; }
    return round($bytes, 1) . ' ' . $units[$i];
}
@endphp

@extends('admin.layouts.master')

@section('page-title')
    الوسائط
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
                ['label' => 'الوسائط'],
            ],
            'title' => 'إدارة الوسائط',
            'subtitle' => 'عرض وإدارة الملفات المرفوعة والمزامنة',
            'actions' => '<div class="d-flex gap-3 flex-wrap">'
                . '<a href="' . route('admin.media-monitoring.index') . '" class="btn btn-link text-muted fw-bold text-decoration-none p-0"><i class="ri-pulse-line me-1 fs-18"></i> المراقبة</a>'
                . '<a href="' . route('admin.media.orphans') . '" class="btn btn-link text-warning fw-bold text-decoration-none p-0"><i class="ri-file-warning-line me-1 fs-18"></i> اليتيمة</a>'
                . '</div>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-folder-3-line',
                'label' => 'إجمالي الملفات', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-cloud-check-line',
                'label' => 'تمت مزامنتها', 'value' => number_format($stats['synced']),
                'hint' => 'على السحابة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-time-line',
                'label' => 'قيد المزامنة', 'value' => number_format($stats['pending']),
                'hint' => 'بانتظار الرفع',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-hard-drive-2-line',
                'label' => 'الحجم الإجمالي', 'value' => formatBytesHelper($stats['total_size']),
                'hint' => number_format($stats['orphaned']) . ' ملف يتيم',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية الملفات</div>
            <div class="filter-panel__subtitle">ابحث بالمسار أو فلتر حسب المزود والحالة والرؤية</div>
            <form method="GET" action="{{ route('admin.media.index') }}" id="mediaFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control"
                                   placeholder="المسار أو checksum..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">المزود</label>
                        <select name="provider" class="form-select">
                            <option value="">كل المزودين</option>
                            <option value="local" {{ request('provider') === 'local' ? 'selected' : '' }}>محلي</option>
                            <option value="s3" {{ request('provider') === 's3' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">المزامنة</label>
                        <select name="sync_status" class="form-select">
                            <option value="">كل الحالات</option>
                            <option value="completed" {{ request('sync_status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="pending" {{ request('sync_status') === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="failed" {{ request('sync_status') === 'failed' ? 'selected' : '' }}>فاشل</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">الرؤية</label>
                        <select name="visibility" class="form-select">
                            <option value="">كل المستويات</option>
                            <option value="public" {{ request('visibility') === 'public' ? 'selected' : '' }}>عام</option>
                            <option value="private" {{ request('visibility') === 'private' ? 'selected' : '' }}>خاص</option>
                        </select>
                    </div>
                    <div class="col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill" id="mediaSearchBtn">
                            <i class="ri-search-2-line me-1"></i> تصفية
                        </button>
                        <a href="{{ route('admin.media.index') }}" class="btn btn-light border" title="إعادة تعيين">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">قائمة الملفات</span>
                <span class="table-count-badge">{{ number_format($stats['filtered']) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th style="min-width: 200px;">المسار</th>
                                <th style="min-width: 90px;">المزود</th>
                                <th style="min-width: 70px;">النوع</th>
                                <th style="min-width: 80px;">الحجم</th>
                                <th style="min-width: 80px;">الرؤية</th>
                                <th style="min-width: 80px;">المزامنة</th>
                                <th style="min-width: 70px;">المرجع</th>
                                <th style="min-width: 110px;">التاريخ</th>
                                <th style="min-width: 110px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($media as $file)
                            <tr>
                                <td><code class="fs-12 text-muted">{{ $file->id }}</code></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                            $fileIcon = 'ri-file-line';
                                            if ($file->mime_type && str_starts_with($file->mime_type, 'image/')) $fileIcon = 'ri-image-line';
                                            elseif ($file->mime_type && str_starts_with($file->mime_type, 'video/')) $fileIcon = 'ri-video-line';
                                        @endphp
                                        <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                            <i class="{{ $fileIcon }}"></i>
                                        </span>
                                        <span class="text-truncate d-inline-block" style="max-width:180px" title="{{ $file->path }}">
                                            {{ $file->path }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-soft {{ $file->provider === 'local' ? 'badge-soft-secondary' : 'badge-soft-primary' }}">
                                        {{ $file->provider }}
                                    </span>
                                </td>
                                <td><span class="meta-text">{{ $file->extension }}</span></td>
                                <td><span class="meta-text">{{ $file->size_formatted() }}</span></td>
                                <td>
                                    <span class="badge-soft {{ $file->visibility === 'public' ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                        {{ $file->visibility === 'public' ? 'عام' : 'خاص' }}
                                    </span>
                                </td>
                                <td>
                                    @if($file->is_synced)
                                        <span class="badge-soft badge-soft-success"><i class="ri-check-line"></i> نعم</span>
                                    @else
                                        <span class="badge-soft badge-soft-warning"><i class="ri-time-line"></i> لا</span>
                                    @endif
                                </td>
                                <td><span class="badge-soft badge-soft-info">{{ $file->reference_count }}</span></td>
                                <td>
                                    <span class="meta-text" title="{{ $file->created_at->locale('ar')->translatedFormat('j F Y، H:i') }}">
                                        <i class="ri-time-line"></i>
                                        {{ $file->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.media.show', $file) }}"
                                           class="action-btn action-btn--view" title="عرض">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if(!$file->is_synced)
                                        <form action="{{ route('admin.media.sync', $file) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn--edit" title="مزامنة الآن">
                                                <i class="ri-refresh-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteMediaModal"
                                                data-media-id="{{ $file->id }}"
                                                data-media-path="{{ $file->path }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-folder-open-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد ملفات</h5>
                                        <p class="text-muted mb-0">لا توجد ملفات مطابقة للبحث.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($media->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">
                        {{ $media->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteMediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteMediaForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف الملف</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد الحذف المؤقت</h4>
                    <p class="action-modal-user mb-2 text-truncate px-3" id="deleteMediaPath"></p>
                    <p class="text-muted mb-0 px-md-4">سيتم نقل الملف إلى المحذوفات مؤقتاً.</p>
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
    var filterForm = document.getElementById('mediaFilterForm');
    var searchBtn = document.getElementById('mediaSearchBtn');
    if (filterForm && searchBtn) {
        filterForm.addEventListener('submit', function () {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري البحث...';
        });
    }

    var deleteModal = document.getElementById('deleteMediaModal');
    var deleteForm = document.getElementById('deleteMediaForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteMediaPath').textContent = button.getAttribute('data-media-path');
            deleteForm.action = '{{ url('/admin/media') }}/' + button.getAttribute('data-media-id') + '/soft';
        });
    }
});
</script>
@endpush
