@extends('admin.layouts.master')

@section('page-title')
سجل التوظيف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'سجل التوظيف'],
            ],
            'title' => 'سجل التوظيف',
            'subtitle' => 'جميع حالات التوظيف عبر المنصة',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'green', 'icon' => 'ri-trophy-line', 'label' => 'إجمالي', 'value' => number_format($stats['total']), 'hint' => 'كل السجلات'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'purple', 'icon' => 'ri-calendar-line', 'label' => 'هذا الشهر', 'value' => number_format($stats['this_month']), 'hint' => 'توظيفات'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'cyan', 'icon' => 'ri-file-list-line', 'label' => 'عبر التقديم', 'value' => number_format($stats['applications']), 'hint' => 'طلبات وظائف'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'orange', 'icon' => 'ri-send-plane-line', 'label' => 'عبر Pitch', 'value' => number_format($stats['pitches']), 'hint' => 'عروض موجّهة'])
        </div>

        <div class="filter-panel mb-4">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fs-12">المصدر</label>
                    <select name="source" class="form-select form-select-sm">
                        <option value="">الكل</option>
                        @foreach($sources as $value => $label)
                            <option value="{{ $value }}" @selected(request('source') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-12">الشركة</label>
                    <select name="company_id" class="form-select form-select-sm">
                        <option value="">الكل</option>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}" @selected(request('company_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12">من</label>
                    <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12">إلى</label>
                    <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">تصفية</button>
                </div>
            </form>
        </div>

        <div class="card custom-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>التقني</th>
                            <th>الشركة</th>
                            <th>الوظيفة</th>
                            <th>المصدر</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hires as $hire)
                            <tr>
                                <td>{{ $hire->talent?->name ?? '—' }}</td>
                                <td>{{ $hire->company?->name ?? '—' }}</td>
                                <td>{{ $hire->job?->title ?? '—' }}</td>
                                <td><span class="badge bg-primary-transparent">{{ $hire->sourceLabel() }}</span></td>
                                <td>{{ $hire->hired_at->translatedFormat('j M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">لا توجد سجلات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($hires->hasPages())
                <div class="card-footer">{{ $hires->links() }}</div>
            @endif
        </div>
    </div>
</div>
@stop
