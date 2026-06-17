@extends('talents.layouts.master')

@section('page-title')
لوحة التقني
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid talent-dashboard">

        @include('talents.partials.dashboard.hero', [
            'user' => $user,
            'talent' => $talent,
            'publicHiringRequest' => $publicHiringRequest,
            'profileCompletion' => $profileCompletion,
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-send-plane-line',
                'label' => 'إجمالي الطلبات',
                'value' => number_format($stats['applications_total']),
                'hint' => 'طلبات التوظيف',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-time-line',
                'label' => 'قيد المراجعة',
                'value' => number_format($stats['applications_pending']),
                'hint' => 'بانتظار الرد',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-checkbox-circle-line',
                'label' => 'مقبول',
                'value' => number_format($stats['applications_accepted']),
                'hint' => 'طلبات ناجحة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-sparkling-line',
                'label' => 'وظائف مقترحة',
                'value' => number_format($matchedJobsCount),
                'hint' => 'تناسب مهاراتك',
            ])
        </div>

        <div class="row g-3 mb-4">
            <div class="col-xl-8">
                @include('talents.partials.dashboard.hiring-spotlight', [
                    'publicHiringRequest' => $publicHiringRequest,
                    'hiringResponsesCount' => $hiringResponsesCount,
                ])

                <div class="card custom-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <div class="card-title mb-0">أحدث الطلبات</div>
                            <p class="text-muted fs-12 mb-0 mt-1">آخر 5 طلبات تقديم</p>
                        </div>
                        <a href="{{ route('talent.applications.index') }}" class="btn btn-sm btn-primary-light">عرض الكل</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover talent-applications-table mb-0">
                                <thead>
                                    <tr>
                                        <th>الوظيفة</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @include('talents.partials.dashboard.recent-applications', ['applications' => $recentApplications])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="talent-hire-card mb-3">
                    <div class="talent-hire-card__icon"><i class="ri-trophy-line"></i></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <p class="talent-hire-card__label mb-1">سجل التوظيف</p>
                                <h3 class="talent-hire-card__value mb-0">{{ number_format($hiresCount) }}</h3>
                            </div>
                            <a href="{{ route('talent.hires.index') }}" class="btn btn-sm btn-light">عرض الكل</a>
                        </div>
                        @if($latestHire)
                            <p class="talent-hire-card__meta mb-0">
                                آخر توظيف: {{ $latestHire->company?->name ?? '—' }}
                                · {{ $latestHire->hired_at->diffForHumans() }}
                            </p>
                        @else
                            <p class="talent-hire-card__meta mb-0">لا يوجد سجل توظيف بعد — استمر في التقديم!</p>
                        @endif
                    </div>
                </div>

                @include('talents.partials.dashboard.matched-jobs', ['matchedJobs' => $matchedJobs])
            </div>
        </div>

        <div class="shortcut-section">
            <div class="shortcut-section__header mb-3">
                <h5 class="shortcut-section__title mb-1">
                    <i class="ri-flashlight-line text-warning"></i>
                    اختصارات سريعة
                </h5>
                <p class="shortcut-section__subtitle mb-0">أدر مسيرتك المهنية مباشرة من لوحة التحكم</p>
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
                        'col' => 'col-lg-2 col-md-4 col-sm-6',
                    ])
                @endforeach
            </div>
        </div>

    </div>
</div>
@stop
