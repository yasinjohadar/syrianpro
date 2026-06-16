@extends('admin.layouts.master')

@section('page-title', 'إعدادات التخزين')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'التخزين السحابي'],
                ['label' => 'إعدادات التخزين'],
            ],
            'title' => 'إعدادات التخزين',
            'subtitle' => 'وضع السحابة، المزامنة، والرفع — من لوحة التحكم',
            'actions' => '<a href="' . route('admin.storage.index') . '" class="btn btn-light border btn-wave"><i class="ri-hard-drive-3-line me-1"></i> أماكن التخزين</a>',
        ])

        <form action="{{ route('admin.settings.storage.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-cloud-line me-1 text-primary"></i> وضع التخزين
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">وضع التخزين</label>
                                <select name="storage_driver_mode" class="form-select form-input-enhanced">
                                    @foreach($driverModes as $value => $label)
                                        <option value="{{ $value }}" @selected(old('storage_driver_mode', $settings['storage_driver_mode']) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted fs-12 d-block mt-2">الافتراضي الموصى به: سحابة أولاً مع fallback محلي عند عدم توفر السحابة.</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">القرص المحلي الاحتياطي</label>
                                    <input type="text" name="storage_fallback_disk" class="form-control form-input-enhanced"
                                        value="{{ old('storage_fallback_disk', $settings['storage_fallback_disk']) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">قرص سحابي افتراضي (اختياري)</label>
                                    <input type="text" name="storage_default_cloud_disk" class="form-control form-input-enhanced"
                                        value="{{ old('storage_default_cloud_disk', $settings['storage_default_cloud_disk']) }}"
                                        placeholder="images">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-refresh-line me-1 text-primary"></i> المزامنة والرفع
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">طابور المزامنة</label>
                                    <input type="text" name="storage_sync_queue" class="form-control form-input-enhanced"
                                        value="{{ old('storage_sync_queue', $settings['storage_sync_queue']) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">إعادات المحاولة</label>
                                    <input type="number" name="storage_sync_retries" class="form-control form-input-enhanced" min="0" max="10"
                                        value="{{ old('storage_sync_retries', $settings['storage_sync_retries']) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">تأخير backoff (ثانية)</label>
                                    <input type="number" name="storage_sync_backoff_seconds" class="form-control form-input-enhanced" min="5" max="600"
                                        value="{{ old('storage_sync_backoff_seconds', $settings['storage_sync_backoff_seconds']) }}">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">أقصى حجم رفع (MB)</label>
                                    <input type="number" name="storage_max_upload_size_mb" class="form-control form-input-enhanced" min="1" max="5000"
                                        value="{{ old('storage_max_upload_size_mb', $settings['storage_max_upload_size_mb']) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">أيام الاحتفاظ (حذف ناعم)</label>
                                    <input type="number" name="storage_retention_days" class="form-control form-input-enhanced" min="1" max="365"
                                        value="{{ old('storage_retention_days', $settings['storage_retention_days']) }}">
                                </div>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="storage_media_presigned_urls" value="1"
                                    @checked(old('storage_media_presigned_urls', $settings['storage_media_presigned_urls']))>
                                <label class="form-check-label">روابط موقّعة للملفات الخاصة على S3</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="storage_deduplication_enabled" value="1"
                                    @checked(old('storage_deduplication_enabled', $settings['storage_deduplication_enabled']))>
                                <label class="form-check-label">منع تكرار الملفات (checksum)</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="storage_auto_generate_thumbnails" value="1"
                                    @checked(old('storage_auto_generate_thumbnails', $settings['storage_auto_generate_thumbnails']))>
                                <label class="form-check-label">توليد مصغّرات تلقائياً</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="storage_auto_optimize_images" value="1"
                                    @checked(old('storage_auto_optimize_images', $settings['storage_auto_optimize_images']))>
                                <label class="form-check-label">تحسين الصور تلقائياً</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="storage_virus_scan_enabled" value="1"
                                    @checked(old('storage_virus_scan_enabled', $settings['storage_virus_scan_enabled']))>
                                <label class="form-check-label">فحص فيروسات (يتطلب إعداد مزود)</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="storage_log_uploads" value="1"
                                    @checked(old('storage_log_uploads', $settings['storage_log_uploads']))>
                                <label class="form-check-label">تسجيل عمليات الرفع</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="ri-save-line me-1"></i> حفظ الإعدادات
                    </button>
                </div>

                <div class="col-lg-4">
                    <div class="card custom-card form-card border-info">
                        <div class="card-body">
                            <h6 class="mb-3 fw-bold"><i class="ri-information-line me-1 text-info"></i> روابط سريعة</h6>
                            <ul class="list-unstyled mb-0 fs-13">
                                <li class="mb-2"><a href="{{ route('admin.storage.index') }}" class="text-decoration-none"><i class="ri-hard-drive-3-line me-1"></i> أماكن التخزين</a></li>
                                <li class="mb-2"><a href="{{ route('admin.storage-disk-mappings.index') }}" class="text-decoration-none"><i class="ri-links-line me-1"></i> ربط الأقراص</a></li>
                                <li class="mb-2"><a href="{{ route('admin.storage-migration.index') }}" class="text-decoration-none"><i class="ri-upload-cloud-2-line me-1"></i> ترحيل للسحابة</a></li>
                                <li><a href="{{ route('admin.storage.analytics') }}" class="text-decoration-none"><i class="ri-bar-chart-box-line me-1"></i> الإحصائيات</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
