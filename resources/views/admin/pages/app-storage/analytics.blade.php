@extends('admin.layouts.master')

@section('page-title')
    تحليلات التخزين
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
                ['label' => 'الإحصائيات'],
            ],
            'title' => 'تحليلات التخزين',
            'subtitle' => 'استهلاك التخزين والتكلفة حسب مكان التخزين',
            'actions' => '<a href="' . route('admin.storage.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> أماكن التخزين</a>',
        ])

        @if(isset($budgetAlert) && $budgetAlert)
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                <i class="ri-alarm-warning-line me-1"></i>
                <strong>تنبيه!</strong> {{ $budgetAlert['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="filter-panel mb-4">
            <div class="filter-panel__title">تصفية الإحصائيات</div>
            <div class="filter-panel__subtitle">اختر مكان التخزين والفترة ونوع الملف</div>
            <form method="GET" action="{{ route('admin.storage.analytics') }}" id="storageAnalyticsForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">مكان التخزين</label>
                        <select name="config_id" class="form-select" required>
                            <option value="">اختر مكان التخزين</option>
                            @foreach($configs as $config)
                                <option value="{{ $config->id }}" {{ request('config_id') == $config->id ? 'selected' : '' }}>
                                    {{ $config->name }} ({{ App\Models\AppStorageConfig::DRIVERS[$config->driver] ?? $config->driver }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الفترة</label>
                        <select name="period" class="form-select">
                            <option value="day" {{ $period == 'day' ? 'selected' : '' }}>اليوم</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>هذا الشهر</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>هذه السنة</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">نوع الملف</label>
                        <select name="file_type" class="form-select">
                            <option value="">الكل</option>
                            <option value="image" {{ $fileType == 'image' ? 'selected' : '' }}>صور</option>
                            <option value="document" {{ $fileType == 'document' ? 'selected' : '' }}>وثائق</option>
                            <option value="video" {{ $fileType == 'video' ? 'selected' : '' }}>فيديو</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary w-100" id="analyticsSubmitBtn">
                            <i class="ri-bar-chart-box-line me-1"></i> عرض
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($stats)
            <div class="row g-3 mb-4">
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'purple', 'icon' => 'ri-database-2-line',
                    'label' => 'إجمالي التخزين',
                    'value' => number_format($stats['total_bytes_stored'] / (1024**3), 2) . ' GB',
                    'hint' => 'مساحة مستخدمة',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'green', 'icon' => 'ri-upload-cloud-2-line',
                    'label' => 'إجمالي الرفع',
                    'value' => number_format($stats['total_bytes_uploaded'] / (1024**3), 2) . ' GB',
                    'hint' => 'بيانات مرفوعة',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'cyan', 'icon' => 'ri-download-cloud-2-line',
                    'label' => 'إجمالي التحميل',
                    'value' => number_format($stats['total_bytes_downloaded'] / (1024**3), 2) . ' GB',
                    'hint' => 'بيانات محمّلة',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'orange', 'icon' => 'ri-money-dollar-circle-line',
                    'label' => 'إجمالي التكلفة',
                    'value' => '$' . number_format($stats['total_cost'], 2),
                    'hint' => 'تقدير التكلفة',
                ])
            </div>
        @else
            <div class="card custom-card">
                <div class="card-body">
                    <div class="empty-state py-5">
                        <div class="empty-state-icon"><i class="ri-bar-chart-box-line"></i></div>
                        <h5 class="fw-bold mb-2">اختر مكان تخزين</h5>
                        <p class="text-muted mb-0">حدّد مكان التخزين والفترة لعرض الإحصائيات.</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('storageAnalyticsForm');
    var btn = document.getElementById('analyticsSubmitBtn');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري التحميل...';
        });
    }
});
</script>
@endpush
