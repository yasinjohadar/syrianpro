@extends('admin.layouts.master')

@section('page-title')
    التخصصات التقنية
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'التخصصات التقنية'],
            ],
            'title' => 'التخصصات التقنية',
            'subtitle' => 'إدارة تخصصات المنصة المعروضة في الصفحة الرئيسية',
            'actions' => '<a href="' . route('admin.tech-specialties.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة تخصص جديد</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-code-box-line',
                'label' => 'إجمالي التخصصات', 'value' => number_format($stats['total']),
                'hint' => 'كل التخصصات المسجلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'تخصصات نشطة', 'value' => number_format($stats['active']),
                'hint' => 'مفعّلة حالياً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-pause-circle-line',
                'label' => 'غير نشطة', 'value' => number_format($stats['inactive']),
                'hint' => 'معطّلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-home-smile-line',
                'label' => 'في الرئيسية', 'value' => number_format($stats['on_home']),
                'hint' => 'نشطة وتظهر بالرئيسية',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية التخصصات</div>
            <div class="filter-panel__subtitle">ابحث بالاسم أو فلتر حسب الحالة</div>
            <form action="{{ route('admin.tech-specialties.index') }}" method="GET" id="specialtiesFilterForm"
                  data-admin-ajax-filter
                  data-target="#specialtiesAjaxTarget"
                  data-modals-target="#specialtiesModalsHost"
                  data-count-target="#specialtiesFilteredCount"
                  data-reset-url="{{ route('admin.tech-specialties.index') }}">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control" data-ajax-search
                                   placeholder="البحث بالاسم أو slug..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="is_active" class="form-select" data-ajax-auto>
                            <option value="">كل الحالات</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الرئيسية</label>
                        <select name="show_on_home" class="form-select" data-ajax-auto>
                            <option value="">الكل</option>
                            <option value="1" {{ request('show_on_home') === '1' ? 'selected' : '' }}>يظهر في الرئيسية</option>
                            <option value="0" {{ request('show_on_home') === '0' ? 'selected' : '' }}>مخفي من الرئيسية</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill" id="specialtiesSearchBtn">
                            <i class="ri-search-2-line me-1"></i> بحث
                        </button>
                        <button type="button" class="btn btn-light border" data-ajax-reset title="مسح الفلاتر">
                            <i class="ri-refresh-line"></i>
                        </button>
                    </div>
                </div>
                <div class="ajax-filter-status mt-2" id="specialtiesFilterStatus" aria-live="polite"></div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة التخصصات</span>
                    <span class="table-count-badge" id="specialtiesFilteredCount">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="ajax-filter-target" id="specialtiesAjaxTarget">
                @include('admin.pages.tech-specialties.partials.list')
            </div>
        </div>

        <div id="specialtiesModalsHost">
            @include('admin.pages.tech-specialties.partials.modals')
        </div>

    </div>
</div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteSpecialtyModal');
    var deleteForm = document.getElementById('deleteSpecialtyForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteSpecialtyName').textContent = button.getAttribute('data-specialty-name');
            deleteForm.action = '{{ url('/admin/tech-specialties') }}/' + button.getAttribute('data-specialty-id');
        });
    }
});
</script>
@endpush
