@extends('admin.layouts.master')

@section('page-title', 'تعديل إعدادات البريد الإلكتروني')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الإعدادات'],
                ['label' => 'البريد', 'url' => route('admin.settings.email.index')],
                ['label' => 'تعديل'],
            ],
            'title' => 'تعديل إعدادات البريد الإلكتروني',
            'subtitle' => 'تحديث تكوين SMTP',
            'actions' => '<a href="' . route('admin.settings.email.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form action="{{ route('admin.settings.email.update', $emailSetting->id) }}" method="POST" id="emailSettingsForm">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-mail-settings-line me-1 text-primary"></i> اختر المزود
                            </h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label fw-semibold">مزود البريد <span class="text-danger">*</span></label>
                            <select name="provider" id="provider" class="form-select form-input-enhanced @error('provider') is-invalid @enderror" required>
                                <option value="">-- اختر المزود --</option>
                                @foreach($providers as $key => $provider)
                                <option value="{{ $key }}" {{ old('provider', $emailSetting->provider) == $key ? 'selected' : '' }}>
                                    {{ $provider['name'] }}
                                </option>
                                @endforeach
                            </select>
                            @error('provider')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-server-line me-1 text-primary"></i> إعدادات SMTP
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">SMTP Host <span class="text-danger">*</span></label>
                                <input type="text" name="mail_host" id="mail_host"
                                       class="form-control form-input-enhanced @error('mail_host') is-invalid @enderror"
                                       value="{{ old('mail_host', $emailSetting->mail_host) }}" dir="ltr" required>
                                @error('mail_host')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Port <span class="text-danger">*</span></label>
                                    <input type="number" name="mail_port" id="mail_port"
                                           class="form-control form-input-enhanced @error('mail_port') is-invalid @enderror"
                                           value="{{ old('mail_port', $emailSetting->mail_port) }}" required>
                                    @error('mail_port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">التشفير <span class="text-danger">*</span></label>
                                    <select name="mail_encryption" id="mail_encryption" class="form-select form-input-enhanced @error('mail_encryption') is-invalid @enderror" required>
                                        <option value="tls" {{ old('mail_encryption', $emailSetting->mail_encryption) == 'tls' ? 'selected' : '' }}>TLS (موصى به)</option>
                                        <option value="ssl" {{ old('mail_encryption', $emailSetting->mail_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ old('mail_encryption', $emailSetting->mail_encryption) == 'none' ? 'selected' : '' }}>بدون تشفير</option>
                                    </select>
                                    @error('mail_encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">اسم المستخدم/البريد <span class="text-danger">*</span></label>
                                <input type="text" name="mail_username" id="mail_username"
                                       class="form-control form-input-enhanced @error('mail_username') is-invalid @enderror"
                                       value="{{ old('mail_username', $emailSetting->mail_username) }}" dir="ltr" required>
                                @error('mail_username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold">كلمة المرور</label>
                                <div class="password-input-wrap">
                                    <input type="password" name="mail_password" id="mail_password"
                                           class="form-control form-input-enhanced @error('mail_password') is-invalid @enderror"
                                           placeholder="اتركه فارغاً للاحتفاظ بكلمة المرور الحالية">
                                    <button type="button" class="password-toggle-btn" data-target="mail_password" aria-label="إظهار كلمة المرور">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>
                                @error('mail_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted fs-12 d-block mt-2">اترك الحقل فارغاً للاحتفاظ بكلمة المرور الحالية</small>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-mail-send-line me-1 text-primary"></i> إعدادات البريد المرسل
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">البريد المرسل <span class="text-danger">*</span></label>
                                <input type="email" name="mail_from_address" id="mail_from_address"
                                       class="form-control form-input-enhanced @error('mail_from_address') is-invalid @enderror"
                                       value="{{ old('mail_from_address', $emailSetting->mail_from_address) }}" dir="ltr" required>
                                @error('mail_from_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">اسم المرسل <span class="text-danger">*</span></label>
                                <input type="text" name="mail_from_name" id="mail_from_name"
                                       class="form-control form-input-enhanced @error('mail_from_name') is-invalid @enderror"
                                       value="{{ old('mail_from_name', $emailSetting->mail_from_name) }}" required>
                                @error('mail_from_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-sticky">
                        @if($emailSetting->is_active)
                        <div class="preview-success-banner mb-4">
                            <i class="ri-checkbox-circle-line"></i>
                            <span>هذه الإعدادات <strong>نشطة حالياً</strong> في النظام</span>
                        </div>
                        @endif

                        <div class="card custom-card form-card sidebar-tip-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold fs-15">
                                    <i class="ri-lightbulb-line me-1 text-primary"></i> نصائح سريعة
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="sidebar-tip-list">
                                    <li><i class="ri-check-line"></i> اختبر بعد أي تغيير في SMTP</li>
                                    <li><i class="ri-check-line"></i> بدون كلمة مرور جديدة يُستخدم المحفوظ</li>
                                    <li><i class="ri-check-line"></i> Gmail: App Password مطلوب</li>
                                </ul>
                            </div>
                        </div>

                        <div class="card custom-card form-card sidebar-submit-card">
                            <div class="card-body d-grid gap-2">
                                <button type="button" class="btn btn-light border" onclick="testConnectionBeforeSave(event)">
                                    <i class="ri-test-tube-line me-1"></i> اختبار الاتصال
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('admin.settings.email.index') }}" class="btn btn-light border">
                                    <i class="ri-close-line me-1"></i> إلغاء
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var providerSelect = document.getElementById('provider');
    if (providerSelect) {
        providerSelect.addEventListener('change', async function () {
            var provider = this.value;
            if (!provider || provider === 'custom') return;
            try {
                var response = await fetch('/admin/settings/email/provider/' + provider);
                var data = await response.json();
                document.getElementById('mail_host').value = data.mail_host || '';
                document.getElementById('mail_port').value = data.mail_port || 587;
                document.getElementById('mail_encryption').value = data.mail_encryption || 'tls';
            } catch (error) {
                console.error('Error loading provider preset:', error);
            }
        });
    }
});

async function testConnectionBeforeSave(event) {
    var mailHost = document.getElementById('mail_host').value;
    var mailPort = document.getElementById('mail_port').value;
    var mailUsername = document.getElementById('mail_username').value;
    var mailPassword = document.getElementById('mail_password').value;
    var mailEncryption = document.getElementById('mail_encryption').value;
    var mailFromAddress = document.getElementById('mail_from_address').value;
    var settingId = {{ $emailSetting->id }};

    if (!mailHost || !mailPort || !mailUsername || !mailFromAddress) {
        alert('يرجى ملء جميع الحقول المطلوبة قبل الاختبار');
        return;
    }

    var testEmail = prompt('أدخل البريد الإلكتروني لإرسال بريد اختباري إليه:', mailFromAddress);
    if (!testEmail) return;

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(testEmail)) {
        alert('يرجى إدخال بريد إلكتروني صحيح');
        return;
    }

    var testBtn = event.target.closest('button');
    var originalText = testBtn.innerHTML;
    testBtn.disabled = true;
    testBtn.innerHTML = '<i class="ri-loader-4-line ri-spin me-1"></i> جاري الاختبار...';

    try {
        var url, body;
        if (!mailPassword) {
            url = '/admin/settings/email/' + settingId + '/test';
            body = { test_email: testEmail };
        } else {
            url = '{{ route("admin.settings.email.test-temp") }}';
            body = {
                mail_host: mailHost,
                mail_port: mailPort,
                mail_username: mailUsername,
                mail_password: mailPassword,
                mail_encryption: mailEncryption,
                mail_from_address: mailFromAddress,
                mail_from_name: document.getElementById('mail_from_name').value || 'Test',
                test_email: testEmail
            };
        }

        var response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify(body)
        });

        var result = await response.json();
        if (result.success) {
            if (window.adminUiToast) window.adminUiToast(result.message, 'success');
            else alert('✅ ' + result.message);
        } else if (window.adminUiToast) {
            window.adminUiToast(result.message || 'فشل الاختبار', 'error');
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ حدث خطأ أثناء اختبار الاتصال');
    } finally {
        testBtn.disabled = false;
        testBtn.innerHTML = originalText;
    }
}
</script>
@endpush
