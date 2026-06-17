@extends('company.layouts.master')

@section('page-title')
طلبات التوظيف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'طلبات التوظيف'],
            ],
            'title' => 'طلبات التوظيف',
            'subtitle' => 'تقنيون يبحثون عن فرص — طلبات عامة وعروض موجهة لشركتك',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-inbox-line',
                'label' => 'إجمالي الطلبات',
                'value' => number_format($stats['total']),
                'hint' => 'عامة + موجهة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-megaphone-line',
                'label' => 'طلبات عامة',
                'value' => number_format($stats['public']),
                'hint' => 'من كل التقنيين',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-mail-send-line',
                'label' => 'موجهة لنا',
                'value' => number_format($stats['pitches']),
                'hint' => 'Pitch مباشر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-thumb-up-line',
                'label' => 'اهتماماتنا',
                'value' => number_format($stats['interested']),
                'hint' => 'ردود مسجّلة',
            ])
        </div>

        <div class="filter-panel mb-4">
            <div class="filter-panel__title">تصفية الطلبات</div>
            <form action="{{ route('company.hiring-requests.index') }}" method="GET">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="query" class="form-control" placeholder="اسم التقني أو الدور..."
                                   value="{{ request('query') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">نوع العمل</label>
                        <select name="employment_type" class="form-select">
                            <option value="">الكل</option>
                            @foreach($employmentTypes as $value => $label)
                                <option value="{{ $value }}" @selected(request('employment_type') === $value)>{{ $label }}</option>
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
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill"><i class="ri-filter-3-line me-1"></i> تطبيق</button>
                    </div>
                </div>
            </form>
        </div>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'public' ? 'active' : '' }}" href="{{ route('company.hiring-requests.index', array_merge(request()->except('tab'), ['tab' => 'public'])) }}">
                    طلبات عامة <span class="badge bg-primary-transparent ms-1">{{ $stats['public'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tab === 'pitches' ? 'active' : '' }}" href="{{ route('company.hiring-requests.index', array_merge(request()->except('tab'), ['tab' => 'pitches'])) }}">
                    موجهة لنا <span class="badge bg-warning-transparent ms-1">{{ $stats['pitches'] }}</span>
                </a>
            </li>
        </ul>

        @php $requests = $tab === 'pitches' ? $pitchRequests : $publicRequests; @endphp

        <div class="card custom-card">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ $tab === 'pitches' ? 'عروض موجهة لشركتك' : 'طلبات توظيف عامة' }}</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th>التقني</th>
                                <th>الدور المطلوب</th>
                                <th>نوع العمل</th>
                                <th>المعدل</th>
                                <th>التاريخ</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $hiringRequest)
                                @php $talent = $hiringRequest->talent; @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="row-avatar row-avatar--alt">{{ mb_substr($talent?->name ?? '?', 0, 1) }}</span>
                                            <div>
                                                <div class="fw-bold">{{ $talent?->name ?? '—' }}</div>
                                                <span class="text-muted fs-11">{{ $talent?->title }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $hiringRequest->headline }}</span>
                                        @if($hiringRequest->responses->isNotEmpty())
                                            <span class="badge-soft badge-soft-success ms-1">مهتم</span>
                                        @endif
                                    </td>
                                    <td>{{ $hiringRequest->employmentTypeLabel() }}</td>
                                    <td class="text-muted fs-12">
                                        @if($hiringRequest->rate_min && $hiringRequest->rate_max)
                                            ${{ $hiringRequest->rate_min }}–{{ $hiringRequest->rate_max }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-muted fs-12">{{ $hiringRequest->published_at?->diffForHumans() ?? $hiringRequest->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('company.hiring-requests.show', $hiringRequest) }}" class="action-btn action-btn--view" title="عرض">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="ri-user-search-line"></i></div>
                                            <h5 class="fw-bold mb-2">لا توجد طلبات</h5>
                                            <p class="text-muted mb-0">{{ $tab === 'pitches' ? 'لم يصلك أي Pitch بعد.' : 'لا يوجد تقنيون نشطون يبحثون عن عمل حالياً.' }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($requests->hasPages())
                <div class="card-footer border-top bg-transparent py-3">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
</div>
@stop
