@extends('company.layouts.master')

@section('page-title')
لوحة الشركة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.dashboard-welcome', ['roleLabel' => $roleLabel])

        @include('admin.partials.ui.alerts')

        @if(!$company)
            <div class="alert alert-warning" role="alert">
                لم يُربط حسابك بملف شركة بعد. تواصل مع الإدارة لربط حسابك أو أكمل ملف الشركة من
                <a href="{{ route('company.profile.edit') }}">صفحة الملف</a>.
            </div>
        @endif

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-briefcase-line',
                'label' => 'وظائف نشطة',
                'value' => number_format($stats['active_jobs']),
                'hint' => 'منشورة على المنصة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-inbox-line',
                'label' => 'طلبات جديدة',
                'value' => number_format($stats['applications_total']),
                'hint' => number_format($stats['applications_pending']) . ' قيد المراجعة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-user-star-line',
                'label' => 'قاعدة المواهب',
                'value' => number_format($stats['talents_pool']) . '+',
                'hint' => 'تقنيون سوريون',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-trophy-line',
                'label' => 'من وظّفنا',
                'value' => number_format($stats['hires_total']),
                'hint' => 'سجل التوظيف',
            ])
        </div>

        <div class="row g-3">
            <div class="col-xl-7">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">أحدث الطلبات</div>
                        <a href="{{ route('company.applications.index') }}" class="btn btn-sm btn-primary-light">عرض الكل</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>المتقدم</th>
                                        <th>الوظيفة</th>
                                        <th>الحالة</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentApplications as $application)
                                        <tr>
                                            <td>{{ $application->user?->name ?? '—' }}</td>
                                            <td>{{ $application->job?->title ?? 'وظيفة محذوفة' }}</td>
                                            <td><span class="badge bg-primary-transparent">{{ $application->statusLabel() }}</span></td>
                                            <td>
                                                <a href="{{ route('company.applications.show', $application) }}" class="btn btn-sm btn-light">عرض</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">لا توجد طلبات بعد</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="card-title mb-0">وظائفي النشطة</div>
                        <a href="{{ route('company.jobs.create') }}" class="btn btn-sm btn-primary-light">+ إضافة</a>
                    </div>
                    <div class="card-body">
                        @forelse($activeJobs as $job)
                            <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                                <div>
                                    <a href="{{ route('company.jobs.edit', $job) }}" class="fw-semibold text-default text-decoration-none">{{ $job->title }}</a>
                                    <div class="text-muted fs-12">{{ $job->location }} · {{ $job->applicationsCount() }} متقدم</div>
                                </div>
                                <span class="badge bg-success-transparent">نشط</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">لا توجد وظائف نشطة — <a href="{{ route('company.jobs.create') }}">أضف وظيفة</a></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="shortcut-section">
            <div class="shortcut-section__header mb-3">
                <h5 class="shortcut-section__title mb-1">
                    <i class="ri-flashlight-line text-warning"></i>
                    اختصارات سريعة
                </h5>
                <p class="shortcut-section__subtitle mb-0">أدر شركتك مباشرة من لوحة التحكم</p>
            </div>
            <div class="row g-3 shortcut-grid">
                @foreach($shortcuts as $shortcut)
                    @include('admin.partials.ui.shortcut-card', [
                        'url' => $shortcut['url'],
                        'title' => $shortcut['title'],
                        'description' => $shortcut['description'],
                        'icon' => $shortcut['icon'],
                        'icon_color' => $shortcut['icon_color'] ?? 'primary',
                        'badge' => $shortcut['badge'] ?? null,
                    ])
                @endforeach
            </div>
        </div>

    </div>
</div>
@stop
