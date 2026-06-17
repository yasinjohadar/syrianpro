@extends('company.layouts.master')

@section('page-title')
من وظّفنا
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'من وظّفنا'],
            ],
            'title' => 'من وظّفنا',
            'subtitle' => 'سجل التوظيف عبر المنصة',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-trophy-line',
                'label' => 'إجمالي التوظيف', 'value' => number_format($stats['total']),
                'hint' => 'منذ البداية',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-calendar-line',
                'label' => 'هذا الشهر', 'value' => number_format($stats['this_month']),
                'hint' => 'توظيفات جديدة',
            ])
        </div>

        @if(!$company)
            <div class="card custom-card"><div class="card-body text-center py-5 text-muted">أكمل ملف شركتك أولاً.</div></div>
        @elseif($hires->isEmpty())
            <div class="card custom-card"><div class="card-body text-center py-5 text-muted">لا يوجد سجل توظيف بعد.</div></div>
        @else
            <div class="card custom-card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>التقني</th>
                                <th>الوظيفة</th>
                                <th>المصدر</th>
                                <th>التاريخ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hires as $hire)
                                <tr>
                                    <td>{{ $hire->talent?->name ?? '—' }}</td>
                                    <td>{{ $hire->job?->title ?? '—' }}</td>
                                    <td><span class="badge bg-primary-transparent">{{ $hire->sourceLabel() }}</span></td>
                                    <td>{{ $hire->hired_at->translatedFormat('j M Y') }}</td>
                                    <td>
                                        @if($hire->talent)
                                            <a href="{{ route('company.talents.show', $hire->talent) }}" class="btn btn-sm btn-primary-light">الملف</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($hires->hasPages())
                    <div class="card-footer">{{ $hires->links() }}</div>
                @endif
            </div>
        @endif
    </div>
</div>
@stop
