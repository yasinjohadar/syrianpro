@extends('admin.layouts.master')

@section('page-title')
    الوظائف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الوظائف'],
            ],
            'title' => 'الوظائف',
            'subtitle' => 'إدارة الوظائف المعروضة في المنصة',
            'actions' => '<a href="' . route('admin.jobs.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة وظيفة جديدة</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-briefcase-line',
                'label' => 'إجمالي الوظائف', 'value' => number_format($stats['total']),
                'hint' => 'كل الوظائف المسجلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'وظائف نشطة', 'value' => number_format($stats['active']),
                'hint' => 'مفعّلة حالياً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-pause-circle-line',
                'label' => 'غير نشطة', 'value' => number_format($stats['inactive']),
                'hint' => 'معطّلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-star-line',
                'label' => 'مميزة', 'value' => number_format($stats['featured']),
                'hint' => 'نشطة ومميزة',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية الوظائف</div>
            <div class="filter-panel__subtitle">ابحث بالعنوان أو الشركة أو الموقع</div>
            <form action="{{ route('admin.jobs.index') }}" method="GET" id="jobsFilterForm"
                  data-admin-ajax-filter
                  data-target="#jobsAjaxTarget"
                  data-modals-target="#jobsModalsHost"
                  data-count-target="#jobsFilteredCount"
                  data-reset-url="{{ route('admin.jobs.index') }}">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control" data-ajax-search
                                   placeholder="البحث بالعنوان أو الشركة..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="is_active" class="form-select" data-ajax-auto>
                            <option value="">كل الحالات</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">مميزة</label>
                        <select name="is_featured" class="form-select" data-ajax-auto>
                            <option value="">الكل</option>
                            <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>مميزة</option>
                            <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>غير مميزة</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">Remote</label>
                        <select name="remote_type" class="form-select" data-ajax-auto>
                            <option value="">الكل</option>
                            <option value="full-remote" {{ request('remote_type') === 'full-remote' ? 'selected' : '' }}>عن بُعد</option>
                            <option value="hybrid" {{ request('remote_type') === 'hybrid' ? 'selected' : '' }}>هجين</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-search-2-line me-1"></i> بحث
                        </button>
                        <button type="button" class="btn btn-light border" data-ajax-reset title="مسح الفلاتر">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
                <div class="ajax-filter-status mt-2" id="jobsFilterStatus" aria-live="polite"></div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة الوظائف</span>
                    <span class="table-count-badge" id="jobsFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="jobsAjaxTarget">
                @include('admin.pages.jobs.partials.list')
            </div>
        </div>

        <div id="jobsModalsHost">
            @include('admin.pages.jobs.partials.modals')
        </div>

    </div>
</div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteJobModal');
    var deleteForm = document.getElementById('deleteJobForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteJobName').textContent = button.getAttribute('data-job-name');
            deleteForm.action = '{{ url('/admin/jobs') }}/' + button.getAttribute('data-job-id');
        });
    }
});
</script>
@endpush
