@extends('admin.layouts.master')

@section('page-title')
    الأدوار
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-toast-container" id="adminToastContainer"></div>
            @include('admin.partials.ui.alerts')

            @include('admin.partials.ui.page-header', [
                'breadcrumbs' => [
                    ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                    ['label' => 'الأدوار'],
                ],
                'title' => 'جدول الأدوار',
                'subtitle' => 'إدارة أدوار النظام والصلاحيات المرتبطة بها',
                'actions' => '<a href="' . route('admin.roles.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-shield-user-line me-1 fs-18"></i> إضافة دور جديد</a>',
            ])

            <div class="row g-3 mb-4">
                @include('admin.partials.ui.stat-card-gradient', [
                    'col' => 'col-sm-6 col-xl-4',
                    'variant' => 'purple',
                    'icon' => 'ri-shield-user-line',
                    'label' => 'إجمالي الأدوار',
                    'value' => number_format($stats['total']),
                    'hint' => 'أدوار مسجّلة في النظام',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'col' => 'col-sm-6 col-xl-4',
                    'variant' => 'green',
                    'icon' => 'ri-lock-unlock-line',
                    'label' => 'الصلاحيات المُعيَّنة',
                    'value' => number_format($stats['assigned_permissions']),
                    'hint' => number_format($stats['permissions']) . ' صلاحية متاحة',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'col' => 'col-sm-6 col-xl-4',
                    'variant' => 'cyan',
                    'icon' => 'ri-group-line',
                    'label' => 'المستخدمون المُعيَّنون',
                    'value' => number_format($stats['users']),
                    'hint' => 'عبر جميع الأدوار',
                ])
            </div>

            <div class="filter-panel">
                <div class="filter-panel__title">تصفية الأدوار</div>
                <div class="filter-panel__subtitle">ابحث عن دور بالاسم</div>
                <x-admin.ajax-filter-form
                    :action="route('admin.roles.index')"
                    target="#rolesAjaxTarget"
                    modals-target="#rolesModalsHost"
                    count-target="#rolesFilteredCount"
                    :reset-url="route('admin.roles.index')"
                    id="rolesFilterForm">
                    <div class="row g-2 g-md-3 align-items-end">
                        <div class="col-lg-10">
                            <label class="form-label fs-12 text-muted mb-1">بحث</label>
                            <div class="search-input-wrap">
                                <i class="ri-search-line"></i>
                                <input type="text" name="query" class="form-control" data-ajax-search
                                       placeholder="البحث باسم الدور..."
                                       value="{{ request('query') }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill" id="rolesSearchBtn">
                                <i class="ri-search-2-line me-1"></i> بحث
                            </button>
                            <button type="button" class="btn btn-light border" data-ajax-reset title="مسح الفلاتر">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </x-admin.ajax-filter-form>
            </div>

            <div class="card custom-card data-table-card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold fs-16">قائمة الأدوار</span>
                        <span class="table-count-badge" id="rolesFilteredCount">{{ number_format($stats['filtered']) }}</span>
                    </div>
                </div>
                <div class="ajax-filter-target" id="rolesAjaxTarget">
                    @include('admin.pages.roles.partials.list')
                </div>
            </div>

            <div id="rolesModalsHost">
                @include('admin.pages.roles.partials.modals')
            </div>

        </div>
    </div>
@stop
