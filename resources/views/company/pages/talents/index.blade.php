@extends('company.layouts.master')

@section('page-title')
قاعدة المواهب
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'قاعدة المواهب'],
            ],
            'title' => 'قاعدة المواهب',
            'subtitle' => 'اكتشف التقنيين السوريين — skills · portfolios · remote',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-user-star-line',
                'label' => 'إجمالي المواهب',
                'value' => number_format($stats['total']),
                'hint' => 'تقنيون نشطون',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-verified-badge-line',
                'label' => 'موثّقون',
                'value' => number_format($stats['verified']),
                'hint' => 'ملفات موثقة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-star-line',
                'label' => 'مميزون',
                'value' => number_format($stats['featured']),
                'hint' => 'مواهب مميزة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-global-line',
                'label' => 'Remote',
                'value' => number_format($stats['remote']),
                'hint' => 'يعملون عن بُعد',
            ])
        </div>

        <div class="filter-panel mb-4">
            <div class="filter-panel__title">تصفية المواهب</div>
            <div class="filter-panel__subtitle">ابحث بالاسم، التخصص، المهارة، المدينة، والمعدل</div>
            <form action="{{ route('company.talents.index') }}" method="GET">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="الاسم، المسمى، المدينة..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">التخصص</label>
                        <select name="tech_specialty_id" class="form-select">
                            <option value="">الكل</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" @selected(request('tech_specialty_id') == $specialty->id)>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">المدينة</label>
                        <select name="city" class="form-select">
                            <option value="">الكل</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">مهارة</label>
                        <input type="text" name="skill" class="form-control" placeholder="React, Laravel..."
                               value="{{ request('skill') }}">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">التوفر</label>
                        <select name="availability" class="form-select">
                            <option value="">الكل</option>
                            @foreach($availabilities as $availability)
                                <option value="{{ $availability }}" @selected(request('availability') === $availability)>
                                    {{ $availability }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">عن بُعد</label>
                        <select name="is_remote" class="form-select">
                            <option value="">الكل</option>
                            <option value="1" @selected(request('is_remote') === '1')>نعم</option>
                            <option value="0" @selected(request('is_remote') === '0')>لا</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">موثّق</label>
                        <select name="is_verified" class="form-select">
                            <option value="">الكل</option>
                            <option value="1" @selected(request('is_verified') === '1')>موثّق</option>
                            <option value="0" @selected(request('is_verified') === '0')>غير موثّق</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">يبحث عن عمل</label>
                        <select name="open_to_work" class="form-select">
                            <option value="">الكل</option>
                            <option value="1" @selected(request('open_to_work') === '1')>نعم</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">مميز</label>
                        <select name="is_featured" class="form-select">
                            <option value="">الكل</option>
                            <option value="1" @selected(request('is_featured') === '1')>مميز</option>
                            <option value="0" @selected(request('is_featured') === '0')>عادي</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">معدل من ($)</label>
                        <input type="number" name="rate_min" min="0" class="form-control" placeholder="15"
                               value="{{ request('rate_min') }}">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">معدل إلى ($)</label>
                        <input type="number" name="rate_max" min="0" class="form-control" placeholder="50"
                               value="{{ request('rate_max') }}">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">ترتيب</label>
                        <select name="sort" class="form-select">
                            <option value="order" @selected(request('sort', 'order') === 'order')>الافتراضي</option>
                            <option value="name" @selected(request('sort') === 'name')>الاسم</option>
                            <option value="rate_desc" @selected(request('sort') === 'rate_desc')>أعلى معدل</option>
                            <option value="rate_asc" @selected(request('sort') === 'rate_asc')>أقل معدل</option>
                            <option value="featured" @selected(request('sort') === 'featured')>المميز أولاً</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-filter-3-line me-1"></i> تطبيق
                        </button>
                        @if(request()->hasAny(['query', 'tech_specialty_id', 'city', 'skill', 'availability', 'is_remote', 'open_to_work', 'is_verified', 'is_featured', 'rate_min', 'rate_max', 'sort']))
                            <a href="{{ route('company.talents.index') }}" class="btn btn-light border" title="إعادة تعيين">
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
                    <h6 class="card-title mb-0">قائمة المواهب</h6>
                    <p class="text-muted fs-12 mb-0 mt-1">{{ $talents->total() }} موهبة</p>
                </div>
            </div>

            @include('company.partials.talents.list', ['talents' => $talents])
        </div>
    </div>
</div>
@stop
