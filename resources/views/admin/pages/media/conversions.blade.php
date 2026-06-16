@extends('admin.layouts.master')

@section('page-title')
    عمليات التحويل
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الوسائط', 'url' => route('admin.media.index')],
                ['label' => 'التحويلات'],
            ],
            'title' => 'عمليات التحويل',
            'subtitle' => 'مصغّرات، تحسين صور، وتحويلات أخرى',
            'actions' => '<a href="' . route('admin.media.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'purple', 'icon' => 'ri-list-check-2', 'label' => 'الإجمالي', 'value' => number_format($stats['total']), 'hint' => 'كل العمليات', 'col' => 'col-6 col-md'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'orange', 'icon' => 'ri-time-line', 'label' => 'قيد الانتظار', 'value' => number_format($stats['pending']), 'hint' => 'pending', 'col' => 'col-6 col-md'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'cyan', 'icon' => 'ri-loader-4-line', 'label' => 'قيد المعالجة', 'value' => number_format($stats['processing']), 'hint' => 'processing', 'col' => 'col-6 col-md'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'green', 'icon' => 'ri-checkbox-circle-line', 'label' => 'مكتمل', 'value' => number_format($stats['completed']), 'hint' => 'completed', 'col' => 'col-6 col-md'])
            @include('admin.partials.ui.stat-card-gradient', ['variant' => 'orange', 'icon' => 'ri-close-circle-line', 'label' => 'فاشل', 'value' => number_format($stats['failed']), 'hint' => 'failed', 'col' => 'col-6 col-md'])
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">سجل التحويلات</span>
                <span class="table-count-badge">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th><th>Media</th><th>النوع</th><th>الحالة</th>
                                <th>المحاولات</th><th>الخطأ</th><th>التاريخ</th><th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($conversions as $conv)
                            @php
                                $statusMap = ['pending' => 'warning', 'processing' => 'primary', 'completed' => 'success', 'failed' => 'danger'];
                                $badge = 'badge-soft-' . ($statusMap[$conv->status] ?? 'secondary');
                            @endphp
                            <tr>
                                <td><code class="fs-12">{{ $conv->id }}</code></td>
                                <td><a href="{{ route('admin.media.show', $conv->media_id) }}" class="row-title-link">{{ $conv->media_id }}</a></td>
                                <td><code class="small">{{ $conv->type }}</code></td>
                                <td><span class="badge-soft {{ $badge }}">{{ $conv->status }}</span></td>
                                <td>{{ $conv->attempts }}</td>
                                <td class="text-truncate" style="max-width:220px">{{ $conv->error }}</td>
                                <td><span class="meta-text">{{ $conv->created_at->diffForHumans() }}</span></td>
                                <td>
                                    <div class="action-btn-group">
                                        @if($conv->canRetry())
                                        <form action="{{ route('admin.media.retry-conversion', $conv) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn--edit" title="إعادة"><i class="ri-refresh-line"></i></button>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.media.delete-conversion', $conv) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('حذف؟')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn action-btn--delete"><i class="ri-delete-bin-line"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state py-5">
                                        <p class="text-muted mb-0">لا توجد تحويلات</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($conversions->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">{{ $conversions->links() }}</div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
