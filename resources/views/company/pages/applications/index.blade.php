@extends('company.layouts.master')

@section('page-title')
المتقدمون
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'المتقدمون'],
            ],
            'title' => 'المتقدمون',
            'subtitle' => $filteredJob
                ? 'طلبات على: ' . $filteredJob->title
                : 'طلبات التوظيف على وظائف شركتك',
            'actions' => $filteredJob
                ? '<a href="' . route('company.applications.index') . '" class="btn btn-light border btn-wave"><i class="ri-close-line me-1"></i> إزالة الفلتر</a>'
                : '',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-inbox-line',
                'label' => 'إجمالي الطلبات',
                'value' => number_format($stats['total']),
                'hint' => 'كل المتقدمين',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-time-line',
                'label' => 'قيد المراجعة',
                'value' => number_format($stats['pending']),
                'hint' => 'بانتظار الرد',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-checkbox-circle-line',
                'label' => 'مقبول',
                'value' => number_format($stats['accepted']),
                'hint' => 'طلبات ناجحة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-close-circle-line',
                'label' => 'مرفوض',
                'value' => number_format($stats['rejected']),
                'hint' => 'غير مناسب',
            ])
        </div>

        <div class="filter-panel mb-4">
            <div class="filter-panel__title">تصفية الطلبات</div>
            <div class="filter-panel__subtitle">ابحث بالاسم أو الحالة</div>
            <form action="{{ route('company.applications.index') }}" method="GET">
                @if($filteredJob)
                    <input type="hidden" name="job_id" value="{{ $filteredJob->id }}">
                @endif
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control"
                                   placeholder="اسم المتقدم أو البريد..."
                                   value="{{ request('query') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">كل الحالات</option>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-filter-3-line me-1"></i> تطبيق
                        </button>
                        @if(request()->hasAny(['query', 'status', 'job_id']))
                            <a href="{{ route('company.applications.index') }}" class="btn btn-light border" title="إعادة تعيين">
                                <i class="ri-refresh-line"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card">
            <div class="card-header">
                <h6 class="card-title mb-0">قائمة المتقدمين</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>المتقدم</th>
                                <th>الوظيفة</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $application)
                                <tr>
                                    <td class="text-muted fw-medium">{{ $applications->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="row-avatar row-avatar--alt">{{ mb_substr($application->user?->name ?? '?', 0, 1) }}</span>
                                            <div>
                                                <div class="fw-bold">{{ $application->user?->name ?? '—' }}</div>
                                                <span class="text-muted fs-11">{{ $application->user?->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $application->job?->title ?? 'وظيفة محذوفة' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($application->status) {
                                                'accepted' => 'success',
                                                'rejected' => 'secondary',
                                                'shortlisted' => 'warning',
                                                'reviewing' => 'info',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <span class="badge-soft badge-soft-{{ $statusClass }}">{{ $application->statusLabel() }}</span>
                                    </td>
                                    <td class="text-muted fs-12">{{ $application->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('company.applications.show', $application) }}" class="action-btn action-btn--view" title="عرض">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="ri-team-line"></i></div>
                                            <h5 class="fw-bold mb-2">لا توجد طلبات</h5>
                                            <p class="text-muted mb-0">ستظهر هنا طلبات التوظيف عندما يتقدم التقنيون لوظائفك.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($applications->hasPages())
                <div class="card-footer border-top bg-transparent py-3">{{ $applications->links() }}</div>
            @endif
        </div>
    </div>
</div>
@stop
