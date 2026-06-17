@extends('talents.layouts.master')

@section('page-title')
سجل التوظيف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التقني', 'url' => route('talent.dashboard')],
                ['label' => 'سجل التوظيف'],
            ],
            'title' => 'سجل التوظيف',
            'subtitle' => 'فرص العمل التي حصلت عليها عبر المنصة',
            'actions' => '<a href="' . route('jobs.index') . '" class="btn btn-primary btn-wave"><i class="ri-search-line me-1"></i> تصفح الوظائف</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-trophy-line',
                'label' => 'إجمالي التوظيف',
                'value' => number_format($stats['total']),
                'hint' => 'منذ البداية',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-calendar-line',
                'label' => 'هذا الشهر',
                'value' => number_format($stats['this_month']),
                'hint' => 'توظيفات جديدة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-building-2-line',
                'label' => 'شركات',
                'value' => number_format($stats['companies']),
                'hint' => 'وظّفتك عبر المنصة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-briefcase-line',
                'label' => 'وظائف',
                'value' => number_format($stats['jobs']),
                'hint' => 'أدوار مسجّلة',
            ])
        </div>

        <div class="card custom-card">
            @if(!$talent)
                <div class="card-body p-0">
                    <div class="empty-state">
                        <div class="empty-state-icon empty-state-icon--warning"><i class="ri-user-settings-line"></i></div>
                        <h5 class="fw-bold mb-2">أكمل ملفك أولاً</h5>
                        <p class="text-muted mb-3">لا يمكن عرض سجل التوظيف قبل ربط حسابك بملف تقني مكتمل.</p>
                        <a href="{{ route('talent.profile.edit') }}" class="btn btn-primary btn-sm">
                            <i class="ri-edit-line me-1"></i> إكمال الملف
                        </a>
                    </div>
                </div>
            @elseif($hires->isEmpty())
                <div class="card-body p-0">
                    <div class="empty-state">
                        <div class="empty-state-icon empty-state-icon--trophy"><i class="ri-trophy-line"></i></div>
                        <h5 class="fw-bold mb-2">لا يوجد سجل توظيف بعد</h5>
                        <p class="text-muted mb-3">عند قبولك في وظيفة عبر المنصة سيظهر سجلّك هنا تلقائياً.</p>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">
                                <i class="ri-search-line me-1"></i> تصفح الوظائف
                            </a>
                            <a href="{{ route('talent.hiring-request.index') }}" class="btn btn-primary-light btn-sm">
                                <i class="ri-megaphone-line me-1"></i> انشر طلب توظيف
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title mb-0">سجلات التوظيف</div>
                        <p class="text-muted fs-12 mb-0 mt-1">{{ number_format($stats['total']) }} توظيف مسجّل</p>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover talent-hires-table mb-0">
                            <thead>
                                <tr>
                                    <th>الشركة</th>
                                    <th>الوظيفة / الدور</th>
                                    <th>المصدر</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hires as $hire)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="talent-hire-row__icon"><i class="ri-building-2-line"></i></span>
                                                <span class="fw-semibold">{{ $hire->company?->name ?? '—' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($hire->job)
                                                <a href="{{ route('jobs.show', $hire->job) }}" class="text-default text-decoration-none fw-medium">
                                                    {{ $hire->job->title }}
                                                </a>
                                            @else
                                                {{ $hire->talent?->title ?? '—' }}
                                            @endif
                                        </td>
                                        <td><span class="badge-soft badge-soft-primary">{{ $hire->sourceLabel() }}</span></td>
                                        <td class="text-muted fs-12">{{ $hire->hired_at->translatedFormat('j M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($hires->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">{{ $hires->links() }}</div>
                @endif
            @endif
        </div>

    </div>
</div>
@stop
