@extends('admin.layouts.master')



@section('page-title')

    قائمة المستخدمين

@stop



@section('content')

    <div class="main-content app-content">

        <div class="container-fluid">



            <div class="admin-toast-container" id="adminToastContainer"></div>

            @include('admin.partials.ui.alerts')



            @include('admin.partials.ui.page-header', [

                'breadcrumbs' => [

                    ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],

                    ['label' => 'المستخدمون'],

                ],

                'title' => 'كافة المستخدمين',

                'subtitle' => 'إدارة حسابات المستخدمين والأدوار والصلاحيات',

                'actions' => '<a href="' . route('admin.users.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-user-add-line me-1 fs-18"></i> إنشاء مستخدم جديد</a>',

            ])



            <div class="row g-3 mb-4">

                @include('admin.partials.ui.stat-card-gradient', [

                    'variant' => 'purple', 'icon' => 'ri-group-line',

                    'label' => 'إجمالي المستخدمين', 'value' => number_format($stats['total']),

                    'hint' => 'حسب الفلاتر الحالية',

                ])

                @include('admin.partials.ui.stat-card-gradient', [

                    'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',

                    'label' => 'مستخدمون نشطون', 'value' => number_format($stats['active']),

                    'hint' => 'حسابات مفعلة',

                ])

                @include('admin.partials.ui.stat-card-gradient', [

                    'variant' => 'cyan', 'icon' => 'ri-wifi-line',

                    'label' => 'متصلون الآن', 'value' => number_format($stats['online']),

                    'hint' => 'جلسات نشطة',

                ])

                @include('admin.partials.ui.stat-card-gradient', [

                    'variant' => 'orange', 'icon' => 'ri-book-open-line',

                    'label' => 'طلاب', 'value' => number_format($stats['students']),

                    'hint' => 'دور student',

                ])

            </div>



            <div class="filter-panel">

                <div class="filter-panel__title">تصفية المستخدمين</div>

                <div class="filter-panel__subtitle">ابحث أو فلتر حسب الحالة والتفعيل</div>

                <form action="{{ route('admin.users.index') }}" method="GET" id="usersFilterForm"

                      data-admin-ajax-filter

                      data-target="#usersAjaxTarget"

                      data-modals-target="#usersModalsHost"

                      data-count-target="#usersFilteredCount"

                      data-reset-url="{{ route('admin.users.index') }}"

                      data-toggle-url="{{ url('/admin/users') }}"
                      data-toggle-modal="#userToggleStatusModal">

                    <div class="row g-2 g-md-3 align-items-end">

                        <div class="col-lg-4">

                            <label class="form-label fs-12 text-muted mb-1">بحث</label>

                            <div class="search-input-wrap">

                                <i class="ri-search-line"></i>

                                <input type="text" name="query" class="form-control" data-ajax-search

                                       placeholder="البحث بالاسم، البريد، أو الهاتف..."

                                       value="{{ request('query') }}" autocomplete="off">

                            </div>

                        </div>

                        <div class="col-lg-3">

                            <label class="form-label fs-12 text-muted mb-1">الحالة</label>

                            <select name="status" class="form-select" data-ajax-auto>

                                <option value="">كل الحالات</option>

                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>مفعل</option>

                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>موقوف</option>

                                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>محظور</option>

                            </select>

                        </div>

                        <div class="col-lg-3">

                            <label class="form-label fs-12 text-muted mb-1">الحالة النشطة</label>

                            <select name="is_active" class="form-select" data-ajax-auto>

                                <option value="">كل الحالات النشطة</option>

                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>

                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>

                            </select>

                        </div>

                        <div class="col-lg-2 d-flex gap-2">

                            <button type="submit" class="btn btn-primary flex-fill" id="usersSearchBtn">

                                <i class="ri-search-2-line me-1"></i> بحث

                            </button>

                            <button type="button" class="btn btn-light border" data-ajax-reset title="مسح الفلاتر">

                                <i class="ri-refresh-line"></i>

                            </button>

                        </div>

                    </div>

                    <div class="ajax-filter-status mt-2" id="usersFilterStatus" aria-live="polite"></div>

                </form>

            </div>



            <div class="card custom-card data-table-card">

                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">

                    <div class="d-flex align-items-center gap-2">

                        <span class="fw-bold fs-16">قائمة المستخدمين</span>

                        <span class="table-count-badge" id="usersFilteredCount">{{ number_format($stats['filtered']) }}</span>

                    </div>

                </div>

                <div class="ajax-filter-target" id="usersAjaxTarget">

                    @include('admin.pages.users.partials.list')

                </div>

            </div>



            <div id="usersModalsHost">

                @include('admin.pages.users.partials.modals')

            </div>



            <x-admin.confirm-modal

                id="userToggleStatusModal"

                ajax-confirm

                variant="success"

                icon="ri-shut-down-line"

                title="تأكيد تفعيل المستخدم"

                message="سيتمكن المستخدم من الدخول واستخدام النظام."

                confirm-text="نعم، فعّل"

            />



        </div>

    </div>

@stop


