@extends('admin.layouts.master')

@section('page-title', 'إعدادات النسخ الاحتياطي')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'النسخ الاحتياطي'],
                ['label' => 'الإعدادات'],
            ],
            'title' => 'إعدادات النسخ الاحتياطي',
            'subtitle' => 'التنفيذ، الإشعارات، والاحتفاظ — تُدار من لوحة التحكم',
            'actions' => '<a href="' . route('admin.backups.index') . '" class="btn btn-light border btn-wave"><i class="ri-database-2-line me-1"></i> النسخ</a>',
        ])

        <form action="{{ route('admin.settings.backup.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-play-circle-line me-1 text-primary"></i> التنفيذ
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="use_queue" id="use_queue" value="1"
                                    @checked(old('use_queue', $settings['use_queue']))>
                                <label class="form-check-label" for="use_queue">
                                    <strong>تشغيل النسخ عبر Queue</strong>
                                    <br><small class="text-muted">يُفضّل على السيرفر الحقيقي. يتطلب: <code>php artisan queue:work</code></small>
                                </label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="sync_in_local" id="sync_in_local" value="1"
                                    @checked(old('sync_in_local', $settings['sync_in_local']))>
                                <label class="form-check-label" for="sync_in_local">
                                    <strong>تنفيذ متزامن على بيئة local</strong>
                                    <br><small class="text-muted">عند التطوير المحلي، نفّذ النسخ فوراً بدون Queue</small>
                                </label>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">مهلة المهمة (ثانية)</label>
                                    <input type="number" name="job_timeout" class="form-control form-input-enhanced" min="60" max="3600"
                                        value="{{ old('job_timeout', $settings['job_timeout']) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">أيام الاحتفاظ الافتراضية</label>
                                    <input type="number" name="default_retention_days" class="form-control form-input-enhanced" min="1" max="365"
                                        value="{{ old('default_retention_days', $settings['default_retention_days']) }}">
                                </div>
                            </div>
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="prefer_mysqldump" id="prefer_mysqldump" value="1"
                                    @checked(old('prefer_mysqldump', $settings['prefer_mysqldump']))>
                                <label class="form-check-label" for="prefer_mysqldump">استخدام mysqldump لنسخ MySQL عند توفره</label>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-notification-3-line me-1 text-primary"></i> الإشعارات
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="notifications_enabled" id="notifications_enabled" value="1"
                                    @checked(old('notifications_enabled', $settings['notifications_enabled']))>
                                <label class="form-check-label" for="notifications_enabled">تفعيل إشعارات النجاح والفشل</label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">بريد التنبيهات</label>
                                <input type="email" name="notify_email" class="form-control form-input-enhanced @error('notify_email') is-invalid @enderror"
                                    value="{{ old('notify_email', $settings['notify_email']) }}" placeholder="admin@example.com">
                                <small class="text-muted fs-12 d-block mt-1">إن تُرك فارغاً يُستخدم بريد منشئ النسخة.</small>
                                @error('notify_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">رابط Webhook</label>
                                <input type="url" name="webhook_url" class="form-control form-input-enhanced @error('webhook_url') is-invalid @enderror"
                                    value="{{ old('webhook_url', $settings['webhook_url']) }}" placeholder="https://hooks.slack.com/...">
                                <small class="text-muted fs-12 d-block mt-1">يُرسل POST عند اكتمال أو فشل النسخ.</small>
                                @error('webhook_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="ri-save-line me-1"></i> حفظ الإعدادات
                    </button>
                </div>

                <div class="col-lg-4">
                    <div class="card custom-card form-card border-info mb-4">
                        <div class="card-body">
                            <h6 class="mb-3 fw-bold"><i class="ri-information-line me-1 text-info"></i> روابط سريعة</h6>
                            <ul class="list-unstyled mb-0 fs-13">
                                <li class="mb-2"><a href="{{ route('admin.backups.index') }}" class="text-decoration-none"><i class="ri-database-2-line me-1"></i> النسخ الاحتياطية</a></li>
                                <li class="mb-2"><a href="{{ route('admin.backups.create') }}" class="text-decoration-none"><i class="ri-add-circle-line me-1"></i> نسخة جديدة</a></li>
                                <li class="mb-2"><a href="{{ route('admin.backup-schedules.index') }}" class="text-decoration-none"><i class="ri-calendar-schedule-line me-1"></i> الجدولة</a></li>
                                <li><a href="{{ route('admin.storage.index') }}" class="text-decoration-none"><i class="ri-hard-drive-3-line me-1"></i> أماكن التخزين</a></li>
                            </ul>
                        </div>
                    </div>

                    @can('backup-settings-edit')
                    <form action="{{ route('admin.settings.backup.test-webhook') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light border w-100">
                            <i class="ri-link me-1"></i> اختبار Webhook
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
