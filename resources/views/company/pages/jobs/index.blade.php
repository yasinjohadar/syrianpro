@extends('company.layouts.master')

@section('page-title')
وظائفي
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'وظائفي'],
            ],
            'title' => 'وظائفي',
            'subtitle' => 'إدارة الوظائف المنشورة لشركتك — remote · USD',
            'actions' => '<a href="' . route('company.jobs.create') . '" class="btn btn-primary btn-wave"><i class="ri-add-circle-line me-1"></i> أضف وظيفة</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-briefcase-line',
                'label' => 'إجمالي الوظائف',
                'value' => number_format($stats['total']),
                'hint' => 'كل وظائف شركتك',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-checkbox-circle-line',
                'label' => 'نشطة',
                'value' => number_format($stats['active']),
                'hint' => 'منشورة حالياً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-pause-circle-line',
                'label' => 'متوقفة',
                'value' => number_format($stats['inactive']),
                'hint' => 'غير منشورة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-team-line',
                'label' => 'المتقدمون',
                'value' => number_format($stats['applications']),
                'hint' => 'على كل الوظائف',
            ])
        </div>

        <div class="filter-panel mb-4">
            <div class="filter-panel__title">تصفية الوظائف</div>
            <div class="filter-panel__subtitle">ابحث بالعنوان أو الموقع أو نوع العمل</div>
            <form action="{{ route('company.jobs.index') }}" method="GET">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="البحث بالعنوان أو الموقع..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="is_active" class="form-select">
                            <option value="">كل الحالات</option>
                            <option value="1" @selected(request('is_active') === '1')>نشط</option>
                            <option value="0" @selected(request('is_active') === '0')>متوقف</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">نوع العمل</label>
                        <select name="remote_type" class="form-select">
                            <option value="">الكل</option>
                            <option value="full-remote" @selected(request('remote_type') === 'full-remote')>عن بُعد</option>
                            <option value="hybrid" @selected(request('remote_type') === 'hybrid')>هجين</option>
                            <option value="onsite" @selected(request('remote_type') === 'onsite')>حضوري</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-filter-3-line me-1"></i> تطبيق
                        </button>
                        @if(request()->hasAny(['query', 'is_active', 'remote_type']))
                            <a href="{{ route('company.jobs.index') }}" class="btn btn-light border" title="إعادة تعيين">
                                <i class="ri-refresh-line"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="card-title mb-0">قائمة الوظائف</h6>
                    <p class="text-muted fs-12 mb-0 mt-1">{{ $jobs->total() }} وظيفة</p>
                </div>
                <a href="{{ route('company.jobs.create') }}" class="btn btn-sm btn-primary-light">
                    <i class="ri-add-line me-1"></i> وظيفة جديدة
                </a>
            </div>

            @include('company.partials.jobs.list', ['jobs' => $jobs])
        </div>
    </div>
</div>
@stop
