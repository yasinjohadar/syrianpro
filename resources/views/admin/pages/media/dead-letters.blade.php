@extends('admin.layouts.master')

@section('page-title')
    Dead Letters
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الوسائط', 'url' => route('admin.media.index')],
                ['label' => 'Dead Letters'],
            ],
            'title' => 'الملفات الفاشلة (Dead Letters)',
            'subtitle' => 'سجلات المزامنة التي فشلت بعد كل المحاولات',
            'actions' => '<a href="' . route('admin.media.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-file-list-3-line',
                'label' => 'الإجمالي', 'value' => number_format($stats['total']), 'hint' => 'كل السجلات',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-error-warning-line',
                'label' => 'غير محلولة', 'value' => number_format($stats['unresolved']), 'hint' => 'تحتاج إجراء',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'محلولة', 'value' => number_format($stats['resolved']), 'hint' => 'تمت المعالجة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-refresh-line',
                'label' => 'قابلة للإعادة', 'value' => number_format($stats['unresolved']),
                'hint' => 'retry متاح',
            ])
        </div>

        <div class="mb-4">
            <form action="{{ route('admin.media.dead-letters.resolve-all') }}" method="POST">
                @csrf
                <button class="btn btn-success btn-sm"><i class="ri-check-double-line me-1"></i> تحديد الكل كمحلول</button>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">سجل Dead Letters</span>
                <span class="table-count-badge">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th><th>المسار</th><th>الـ Disk</th><th>الخطأ</th>
                                <th>المحاولات</th><th>الحالة</th><th>التاريخ</th><th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deadLetters as $dl)
                            <tr>
                                <td><code class="fs-12">{{ $dl->id }}</code></td>
                                <td><code class="small">{{ $dl->file_path }}</code></td>
                                <td><span class="badge-soft badge-soft-info">{{ $dl->target_disk }}</span></td>
                                <td class="text-truncate" style="max-width:240px">{{ $dl->error }}</td>
                                <td>{{ $dl->attempts }}</td>
                                <td>
                                    @if($dl->resolved)
                                        <span class="badge-soft badge-soft-success">محلولة</span>
                                    @else
                                        <span class="badge-soft badge-soft-danger">غير محلولة</span>
                                    @endif
                                </td>
                                <td><span class="meta-text">{{ $dl->created_at->diffForHumans() }}</span></td>
                                <td>
                                    <div class="action-btn-group">
                                        @if(!$dl->resolved)
                                        <form action="{{ route('admin.media.dead-letters.retry', $dl) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn--edit" title="إعادة"><i class="ri-refresh-line"></i></button>
                                        </form>
                                        @endif
                                        <form action="{{ route('admin.media.dead-letters.delete', $dl) }}" method="POST" class="d-inline"
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
                                        <p class="text-muted mb-0">لا توجد ملفات فاشلة</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($deadLetters->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">{{ $deadLetters->links() }}</div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
