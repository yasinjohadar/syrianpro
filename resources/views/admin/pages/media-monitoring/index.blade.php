@extends('admin.layouts.master')

@section('page-title')
    مراقبة الوسائط
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'التخزين السحابي'],
                ['label' => 'مراقبة الوسائط'],
            ],
            'title' => 'مراقبة نظام الوسائط',
            'subtitle' => 'نظرة عامة على صحة التخزين والمزامنة والتحويلات',
            'actions' => '<a href="' . route('admin.media.index') . '" class="btn btn-light border btn-wave"><i class="ri-folder-3-line me-1"></i> الوسائط</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-file-list-3-line',
                'label' => 'إجمالي الملفات', 'value' => number_format($data['overview']['total_files']),
                'hint' => 'في النظام',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-hard-drive-2-line',
                'label' => 'الحجم الإجمالي', 'value' => $data['overview']['total_size_formatted'],
                'hint' => 'مساحة مستخدمة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-refresh-line',
                'label' => 'في انتظار المزامنة', 'value' => number_format($data['overview']['pending_sync']),
                'hint' => 'بانتظار الرفع',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-alert-line',
                'label' => 'ملفات يتيمة', 'value' => number_format($data['overview']['orphaned_files']),
                'hint' => 'بدون مراجع',
                'col' => 'col-sm-6 col-xl-3',
            ])
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card custom-card form-card h-100">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-calendar-check-line me-1 text-primary"></i> إحصائيات اليوم</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted fs-12 mb-1">الرفع اليوم</div>
                                    <div class="fs-3 fw-bold">{{ number_format($data['today']['uploads']) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted fs-12 mb-1">حجم الرفع</div>
                                    <div class="fs-3 fw-bold">{{ $data['today']['upload_size_formatted'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card custom-card form-card h-100">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-heart-pulse-line me-1 text-primary"></i> صحة المزامنة</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 text-center">
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded-3 border">
                                    <div class="text-muted fs-11">نسبة النجاح</div>
                                    <div class="fs-5 fw-bold text-success">{{ $data['sync_health']['success_rate'] }}%</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded-3 border">
                                    <div class="text-muted fs-11">تحويلات فاشلة</div>
                                    <div class="fs-5 fw-bold text-danger">{{ $data['sync_health']['failed_conversions'] }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded-3 border">
                                    <div class="text-muted fs-11">قيد الانتظار</div>
                                    <div class="fs-5 fw-bold text-warning">{{ $data['sync_health']['pending_conversions'] }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded-3 border">
                                    <div class="text-muted fs-11">Dead Letters</div>
                                    <div class="fs-5 fw-bold text-danger">{{ $data['sync_health']['dead_letters'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card custom-card data-table-card h-100">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-cloud-line me-1 text-primary"></i> التخزين حسب المزود</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead><tr><th>المزود</th><th>الملفات</th><th>الحجم</th></tr></thead>
                                <tbody>
                                    @forelse($data['storage_by_provider'] as $provider)
                                    <tr>
                                        <td><span class="badge-soft badge-soft-primary">{{ $provider['provider'] }}</span></td>
                                        <td>{{ number_format($provider['count']) }}</td>
                                        <td>{{ $provider['total_size_formatted'] }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center text-muted py-4">لا توجد بيانات</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card custom-card form-card h-100">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-tools-line me-1 text-primary"></i> إجراءات الصيانة</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.media-monitoring.cleanup-orphans') }}" method="POST"
                                  onsubmit="return confirm('حذف جميع الملفات اليتيمة؟')">
                                @csrf
                                <button class="btn btn-outline-danger w-100">
                                    <i class="ri-delete-bin-line me-1"></i>
                                    حذف الملفات اليتيمة ({{ $data['overview']['orphaned_files'] }})
                                </button>
                            </form>
                            <form action="{{ route('admin.media-monitoring.cleanup-soft-deleted') }}" method="POST"
                                  onsubmit="return confirm('حذف نهائي للملفات المحذوفة؟')">
                                @csrf
                                <button class="btn btn-outline-warning w-100">
                                    <i class="ri-archive-line me-1"></i>
                                    حذف نهائي للمحذوفات ({{ $data['overview']['soft_deleted'] }})
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card custom-card data-table-card mb-4">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-error-warning-line me-1 text-danger"></i> آخر الإخفاقات</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th>Media ID</th>
                                <th>النوع</th>
                                <th>الخطأ</th>
                                <th>المحاولات</th>
                                <th>منذ</th>
                                <th>إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recent_failures'] as $failure)
                            <tr>
                                <td><code>{{ $failure['media_id'] }}</code></td>
                                <td><span class="badge-soft badge-soft-secondary">{{ $failure['type'] }}</span></td>
                                <td class="text-truncate" style="max-width:280px">{{ $failure['error'] }}</td>
                                <td>{{ $failure['attempts'] }}</td>
                                <td><span class="meta-text">{{ $failure['created_at'] }}</span></td>
                                <td>
                                    <a href="{{ route('admin.media-monitoring.retry-conversion', ['conversion' => $failure['media_id']]) }}"
                                       class="action-btn action-btn--edit" title="إعادة محاولة">
                                        <i class="ri-refresh-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state py-4">
                                        <p class="text-muted mb-0">لا توجد إخفاقات حديثة</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold fs-15"><i class="ri-list-check-2 me-1 text-primary"></i> دفعات الترحيل</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead><tr><th>#</th><th>الاسم</th><th>الحالة</th><th>التقدم</th><th>منذ</th></tr></thead>
                        <tbody>
                            @forelse($data['migration_batches'] as $batch)
                            <tr>
                                <td>{{ $batch['id'] }}</td>
                                <td class="fw-medium">{{ $batch['name'] }}</td>
                                <td>
                                    <span class="badge-soft {{ $batch['status'] === 'completed' ? 'badge-soft-success' : 'badge-soft-primary' }}">
                                        {{ $batch['status'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress" style="height:6px;width:100px">
                                        <div class="progress-bar bg-success" style="width:{{ $batch['progress'] }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $batch['progress'] }}%</small>
                                </td>
                                <td><span class="meta-text">{{ $batch['created_at'] }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state py-4">
                                        <p class="text-muted mb-0">لا توجد دفعات ترحيل</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
