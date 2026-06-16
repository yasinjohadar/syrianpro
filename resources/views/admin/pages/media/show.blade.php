@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الملف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الوسائط', 'url' => route('admin.media.index')],
                ['label' => $medium->id],
            ],
            'title' => 'تفاصيل الملف',
            'subtitle' => '<code class="fs-13">' . e($medium->path) . '</code>',
            'actions' => '<a href="' . route('admin.media.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-4">
    {{-- File Info --}}
    <div class="col-lg-4">
        <div class="card custom-card form-card h-100">
            <div class="card-header"><h6 class="mb-0 fw-semibold fs-15"><i class="ri-information-line me-1 text-primary"></i> معلومات الملف</h6></div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">UUID</td><td><code class="small">{{ $medium->uuid }}</code></td></tr>
                    <tr><td class="text-muted">المسار</td><td><code class="small">{{ $medium->path }}</code></td></tr>
                    <tr><td class="text-muted">المزود</td><td><span class="badge bg-primary">{{ $medium->provider }}</span></td></tr>
                    <tr><td class="text-muted">النوع</td><td>{{ $medium->mime_type }}</td></tr>
                    <tr><td class="text-muted">الامتداد</td><td>{{ $medium->extension }}</td></tr>
                    <tr><td class="text-muted">الحجم</td><td>{{ $medium->size_formatted() }}</td></tr>
                    <tr><td class="text-muted">الرؤية</td><td><span class="badge bg-{{ $medium->visibility === 'public' ? 'success' : 'warning' }}">{{ $medium->visibility }}</span></td></tr>
                    <tr><td class="text-muted">Checksum</td><td><code class="small">{{ $medium->checksum }}</code></td></tr>
                    <tr><td class="text-muted">مرجع</td><td><span class="badge bg-info">{{ $medium->reference_count }}</span></td></tr>
                    <tr><td class="text-muted">مزامن</td><td>{{ $medium->is_synced ? '✅ نعم' : '⏳ لا' }}</td></tr>
                    <tr><td class="text-muted">حالة المزامنة</td><td><span class="badge bg-secondary">{{ $medium->sync_status }}</span></td></tr>
                    <tr><td class="text-muted">الرافع</td><td>{{ $medium->uploader?->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted">التاريخ</td><td>{{ $medium->created_at->diffForHumans() }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Variants --}}
    <div class="col-lg-4">
        <div class="card custom-card form-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-stack-line me-1 text-primary"></i> النسخ (Variants)</h6>
            </div>
            <div class="card-body p-0">
                @forelse($variants as $variant)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-info">{{ $variant->name }}</span>
                            <div class="small text-muted mt-1">{{ $variant->path }}</div>
                        </div>
                        <div class="text-end">
                            <div class="small">{{ $variant->size_formatted() }}</div>
                            @if($variant->is_generated)
                                <span class="badge bg-success">جاهز</span>
                            @else
                                <span class="badge bg-warning">قيد الإنشاء</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted">لا توجد نسخ</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Usages --}}
    <div class="col-lg-4">
        <div class="card custom-card form-card h-100">
            <div class="card-header"><h6 class="mb-0 fw-semibold fs-15"><i class="ri-link me-1 text-primary"></i> الاستخدامات</h6></div>
            <div class="card-body p-0">
                @forelse($usages as $usage)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-primary">{{ $usage->collection }}</span>
                            <span class="badge bg-secondary">{{ $usage->field }}</span>
                            <div class="small text-muted mt-1">{{ class_basename($usage->model_type) }} #{{ $usage->model_id }}</div>
                        </div>
                        <div class="small text-muted">{{ $usage->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted">لا توجد استخدامات</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Conversions --}}
    <div class="col-12">
        <div class="card custom-card data-table-card">
            <div class="card-header"><h6 class="mb-0 fw-semibold fs-15"><i class="ri-settings-3-line me-1 text-primary"></i> عمليات التحويل</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead><tr><th>النوع</th><th>الحالة</th><th>المحاولات</th><th>الخطأ</th><th>التاريخ</th><th>إجراء</th></tr></thead>
                        <tbody>
                            @forelse($conversions as $conv)
                            <tr>
                                <td><code>{{ $conv->type }}</code></td>
                                <td><span class="badge bg-{{ $conv->status === 'completed' ? 'success' : ($conv->status === 'failed' ? 'danger' : 'warning') }}">{{ $conv->status }}</span></td>
                                <td>{{ $conv->attempts }}</td>
                                <td class="text-truncate" style="max-width:300px">{{ $conv->error }}</td>
                                <td><small>{{ $conv->created_at->diffForHumans() }}</small></td>
                                <td>
                                    @if($conv->canRetry())
                                    <form action="{{ route('admin.media.retry-conversion', $conv) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-arrow-repeat"></i> إعادة</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">لا توجد تحويلات</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
        </div>

    </div>
</div>
@endsection
