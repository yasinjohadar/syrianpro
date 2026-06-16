@extends('admin.layouts.master')

@section('page-title')
    المواهب
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'المواهب'],
            ],
            'title' => 'المواهب',
            'subtitle' => 'إدارة ملفات المواهب التقنية في المنصة',
            'actions' => '<a href="' . route('admin.talents.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة موهبة جديدة</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-user-star-line',
                'label' => 'إجمالي المواهب', 'value' => number_format($stats['total']),
                'hint' => 'كل المواهب المسجلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'مواهب نشطة', 'value' => number_format($stats['active']),
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
            <div class="filter-panel__title">تصفية المواهب</div>
            <form action="{{ route('admin.talents.index') }}" method="GET" id="talentsFilterForm"
                  data-admin-ajax-filter
                  data-target="#talentsAjaxTarget"
                  data-modals-target="#talentsModalsHost"
                  data-count-target="#talentsFilteredCount"
                  data-reset-url="{{ route('admin.talents.index') }}">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control" data-ajax-search
                                   placeholder="البحث بالاسم أو المسمى..."
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
                        <label class="form-label fs-12 text-muted mb-1">موثّق</label>
                        <select name="is_verified" class="form-select" data-ajax-auto>
                            <option value="">الكل</option>
                            <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>موثّق</option>
                            <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>غير موثّق</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="ri-search-2-line me-1"></i> بحث</button>
                        <button type="button" class="btn btn-light border" data-ajax-reset><i class="ri-refresh-line"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة المواهب</span>
                    <span class="table-count-badge" id="talentsFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="talentsAjaxTarget">
                @include('admin.pages.talents.partials.list')
            </div>
        </div>

        <div id="talentsModalsHost">
            @include('admin.pages.talents.partials.modals')
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteTalentModal');
    var deleteForm = document.getElementById('deleteTalentForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteTalentName').textContent = button.getAttribute('data-talent-name');
            deleteForm.action = '{{ url('/admin/talents') }}/' + button.getAttribute('data-talent-id');
        });
    }
});
</script>
@endpush
