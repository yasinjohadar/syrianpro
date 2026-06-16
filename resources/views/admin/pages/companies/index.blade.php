@extends('admin.layouts.master')

@section('page-title') الشركات @stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')], ['label' => 'الشركات']],
            'title' => 'الشركات',
            'subtitle' => 'إدارة شركات المنصة Remote-friendly',
            'actions' => '<a href="' . route('admin.companies.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة شركة</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'purple', 'icon' => 'ri-building-line', 'label' => 'إجمالي الشركات', 'value' => number_format($stats['total']), 'hint' => 'كل الشركات'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'green', 'icon' => 'ri-checkbox-circle-line', 'label' => 'نشطة', 'value' => number_format($stats['active']), 'hint' => 'مفعّلة'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'cyan', 'icon' => 'ri-pause-circle-line', 'label' => 'غير نشطة', 'value' => number_format($stats['inactive']), 'hint' => 'معطّلة'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'orange', 'icon' => 'ri-star-line', 'label' => 'مميزة', 'value' => number_format($stats['featured']), 'hint' => 'في الرئيسية'])
        </div>

        <div class="filter-panel">
            <form action="{{ route('admin.companies.index') }}" method="GET" data-admin-ajax-filter
                  data-target="#companiesAjaxTarget" data-modals-target="#companiesModalsHost"
                  data-count-target="#companiesFilteredCount" data-reset-url="{{ route('admin.companies.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-lg-4">
                        <input type="text" name="query" class="form-control" data-ajax-search placeholder="بحث..." value="{{ request('query') }}">
                    </div>
                    <div class="col-lg-2">
                        <select name="category" class="form-select" data-ajax-auto>
                            <option value="">كل التصنيفات</option>
                            <option value="tech" @selected(request('category') === 'tech')>تقنية</option>
                            <option value="design" @selected(request('category') === 'design')>تصميم</option>
                            <option value="data" @selected(request('category') === 'data')>بيانات</option>
                            <option value="education" @selected(request('category') === 'education')>تعليم</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select name="is_active" class="form-select" data-ajax-auto>
                            <option value="">كل الحالات</option>
                            <option value="1" @selected(request('is_active') === '1')>نشط</option>
                            <option value="0" @selected(request('is_active') === '0')>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-lg-2"><button type="submit" class="btn btn-primary w-100">بحث</button></div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header"><span class="fw-bold">قائمة الشركات</span> <span class="table-count-badge" id="companiesFilteredCount">{{ $stats['filtered'] }}</span></div>
            <div class="ajax-filter-target" id="companiesAjaxTarget">@include('admin.pages.companies.partials.list')</div>
        </div>
        <div id="companiesModalsHost">@include('admin.pages.companies.partials.modals')</div>
    </div>
</div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var m = document.getElementById('deleteCompanyModal');
    var f = document.getElementById('deleteCompanyForm');
    if (m && f) m.addEventListener('show.bs.modal', function (e) {
        document.getElementById('deleteCompanyName').textContent = e.relatedTarget.getAttribute('data-company-name');
        f.action = '{{ url('/admin/companies') }}/' + e.relatedTarget.getAttribute('data-company-id');
    });
});
</script>
@endpush
