@php
function formatBytesHelper($bytes) {
    if ($bytes === 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) { $bytes /= 1024; $i++; }
    return round($bytes, 1) . ' ' . $units[$i];
}
@endphp

@extends('admin.layouts.master')

@section('page-title')
    الملفات اليتيمة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الوسائط', 'url' => route('admin.media.index')],
                ['label' => 'اليتيمة'],
            ],
            'title' => 'الملفات اليتيمة',
            'subtitle' => 'ملفات بدون مراجع في النظام',
            'actions' => '<a href="' . route('admin.media.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-file-warning-line',
                'label' => 'ملفات يتيمة', 'value' => number_format($stats['total']),
                'hint' => 'بدون مراجع',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-hard-drive-2-line',
                'label' => 'الحجم الإجمالي', 'value' => formatBytesHelper($stats['total_size']),
                'hint' => 'مساحة مهدرة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-cloud-line',
                'label' => 'المزودون', 'value' => count($stats['by_provider']),
                'hint' => implode('، ', array_keys($stats['by_provider'])) ?: '—',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'الحالة', 'value' => $stats['total'] > 0 ? 'يحتاج تنظيف' : 'نظيف',
                'hint' => $stats['total'] > 0 ? 'يمكن الحذف' : 'لا يتيمة',
            ])
        </div>

        @if($stats['total'] > 0)
        <div class="mb-4">
            <form action="{{ route('admin.media.delete-orphans') }}" method="POST"
                  onsubmit="return confirm('هل أنت متأكد من حذف جميع الملفات اليتيمة؟')">
                @csrf
                <button class="btn btn-danger"><i class="ri-delete-bin-line me-1"></i> حذف جميع الملفات اليتيمة</button>
            </form>
        </div>
        @endif

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">قائمة الملفات اليتيمة</span>
                <span class="table-count-badge">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th><th>المسار</th><th>المزود</th><th>النوع</th>
                                <th>الحجم</th><th>الرافع</th><th>التاريخ</th><th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orphans as $file)
                            <tr>
                                <td><code class="fs-12">{{ $file->id }}</code></td>
                                <td><code class="small">{{ $file->path }}</code></td>
                                <td><span class="badge-soft badge-soft-primary">{{ $file->provider }}</span></td>
                                <td>{{ $file->extension }}</td>
                                <td><span class="meta-text">{{ $file->size_formatted() }}</span></td>
                                <td>{{ $file->uploader?->name ?? '—' }}</td>
                                <td><span class="meta-text">{{ $file->created_at->diffForHumans() }}</span></td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.media.show', $file) }}" class="action-btn action-btn--view"><i class="ri-eye-line"></i></a>
                                        <form action="{{ route('admin.media.destroy', $file) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('حذف نهائي؟')">
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
                                        <div class="empty-state-icon"><i class="ri-checkbox-circle-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد ملفات يتيمة</h5>
                                        <p class="text-muted mb-0">النظام نظيف.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orphans->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">{{ $orphans->links() }}</div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
