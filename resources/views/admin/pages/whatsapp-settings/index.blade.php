@extends('admin.layouts.master')

@section('page-title', 'إعدادات WhatsApp')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الإعدادات'],
                ['label' => 'واتساب'],
            ],
            'title' => 'إعدادات WhatsApp',
            'subtitle' => 'إدارة تكامل WhatsApp والمزود والـ Webhook',
            'actions' => '<div class="d-flex gap-3 flex-wrap">'
                . '<a href="' . route('admin.whatsapp-web-settings.index') . '" class="btn btn-link text-muted fw-bold text-decoration-none p-0"><i class="ri-qr-code-line me-1 fs-18"></i> WhatsApp Web</a>'
                . '<a href="' . route('admin.whatsapp-web.connect') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-link me-1 fs-18"></i> ربط الجهاز</a>'
                . '</div>',
        ])

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card form-card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-whatsapp-line me-1 text-primary"></i> إعدادات WhatsApp</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.whatsapp-settings.update') }}" method="POST" id="whatsapp-settings-form">
                            @csrf
                            @method('POST')

                            @php
                                $provider = old('whatsapp_provider', $settings['whatsapp_provider'] ?? 'meta');
                                $providerHints = [
                                    'meta' => 'ستظهر إعدادات Meta Cloud API و Webhook والرد التلقائي.',
                                    'custom_api' => 'ستظهر إعدادات رابط API المخصص فقط.',
                                    'whatsapp_web' => 'ستظهر روابط ربط WhatsApp Web والرد التلقائي.',
                                ];
                            @endphp

                            <!-- General Settings -->
                            <div class="card custom-card form-card border mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0 fw-semibold fs-15">
                                        <i class="ri-settings-3-line me-1 text-primary"></i> الإعدادات العامة
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">تفعيل WhatsApp <span class="text-danger">*</span></label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="whatsapp_enabled" 
                                                       id="whatsapp_enabled"
                                                       value="1"
                                                       {{ ($settings['whatsapp_enabled'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="whatsapp_enabled">
                                                    تفعيل خدمة WhatsApp
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">المزود <span class="text-danger">*</span></label>
                                            <select class="form-select" name="whatsapp_provider" id="whatsapp_provider" required>
                                                <option value="meta" {{ $provider === 'meta' ? 'selected' : '' }}>Meta (WhatsApp Cloud API)</option>
                                                <option value="custom_api" {{ $provider === 'custom_api' ? 'selected' : '' }}>Custom API</option>
                                                <option value="whatsapp_web" {{ $provider === 'whatsapp_web' ? 'selected' : '' }}>WhatsApp Web (QR Code)</option>
                                            </select>
                                            <small class="text-muted d-block mt-1" id="provider-hint">{{ $providerHints[$provider] ?? '' }}</small>
                                            @error('whatsapp_provider')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Meta Provider Settings -->
                            <div class="card custom-card form-card border mb-4 provider-section" id="meta-settings" data-providers="meta" style="display: {{ $provider === 'meta' ? 'block' : 'none' }};">
                                <div class="card-header">
                                    <h6 class="mb-0 fw-semibold fs-15">
                                        <i class="ri-facebook-box-line me-1 text-primary"></i> إعدادات Meta (WhatsApp Cloud API)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">إصدار API <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="api_version" 
                                                   id="api_version"
                                                   value="{{ old('api_version', $settings['api_version'] ?? 'v20.0') }}"
                                                   placeholder="v20.0"
                                                   required>
                                            @error('api_version')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number ID <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="phone_number_id" 
                                                   id="phone_number_id"
                                                   value="{{ old('phone_number_id', $settings['phone_number_id'] ?? '') }}"
                                                   placeholder="رقم معرف رقم الهاتف">
                                            @error('phone_number_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">WABA ID</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="waba_id" 
                                                   id="waba_id"
                                                   value="{{ old('waba_id', $settings['waba_id'] ?? '') }}"
                                                   placeholder="معرف WhatsApp Business Account">
                                            @error('waba_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Access Token</label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   name="access_token" 
                                                   id="access_token"
                                                   value=""
                                                   placeholder="اتركه فارغاً للحفاظ على القيمة الحالية">
                                            <small class="text-muted">اتركه فارغاً إذا كنت لا تريد تغييره</small>
                                            @error('access_token')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Verify Token <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="verify_token" 
                                                   id="verify_token"
                                                   value="{{ old('verify_token', $settings['verify_token'] ?? '') }}"
                                                   placeholder="رمز التحقق للـ Webhook"
                                                   required>
                                            @error('verify_token')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">App Secret</label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   name="app_secret" 
                                                   id="app_secret"
                                                   value=""
                                                   placeholder="اتركه فارغاً للحفاظ على القيمة الحالية">
                                            <small class="text-muted">للتوقيع الرقمي للـ Webhook</small>
                                            @error('app_secret')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom API Settings -->
                            <div class="card custom-card form-card border mb-4 provider-section" id="custom-api-settings" data-providers="custom_api" style="display: {{ $provider === 'custom_api' ? 'block' : 'none' }};">
                                <div class="card-header">
                                    <h6 class="mb-0 fw-semibold fs-15">
                                        <i class="ri-code-s-slash-line me-1 text-primary"></i> إعدادات Custom API
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">API URL <span class="text-danger">*</span></label>
                                            <input type="url" 
                                                   class="form-control" 
                                                   name="custom_api_url" 
                                                   id="custom_api_url"
                                                   value="{{ old('custom_api_url', $settings['custom_api_url'] ?? '') }}"
                                                   placeholder="https://api.example.com/send">
                                            @error('custom_api_url')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">HTTP Method</label>
                                            <select class="form-select" name="custom_api_method" id="custom_api_method">
                                                <option value="POST" {{ ($settings['custom_api_method'] ?? 'POST') == 'POST' ? 'selected' : '' }}>POST</option>
                                                <option value="GET" {{ ($settings['custom_api_method'] ?? '') == 'GET' ? 'selected' : '' }}>GET</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">API Key</label>
                                            <input type="password" 
                                                   class="form-control" 
                                                   name="custom_api_key" 
                                                   id="custom_api_key"
                                                   value=""
                                                   placeholder="اتركه فارغاً للحفاظ على القيمة الحالية">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Custom Headers (JSON)</label>
                                            <textarea class="form-control" 
                                                      name="custom_api_headers" 
                                                      id="custom_api_headers"
                                                      rows="4"
                                                      placeholder='{"Authorization": "Bearer token", "Content-Type": "application/json"}'>{{ old('custom_api_headers', is_array($settings['custom_api_headers'] ?? []) ? json_encode($settings['custom_api_headers'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : ($settings['custom_api_headers'] ?? '{}')) }}</textarea>
                                            <small class="text-muted">أدخل headers كـ JSON object</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Webhook Settings -->
                            <div class="card custom-card form-card border mb-4 provider-section" id="webhook-settings" data-providers="meta" style="display: {{ $provider === 'meta' ? 'block' : 'none' }};">
                                <div class="card-header">
                                    <h6 class="mb-0 fw-semibold fs-15">
                                        <i class="ri-webhook-line me-1 text-primary"></i> إعدادات Webhook
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Webhook Path</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="webhook_path" 
                                                   id="webhook_path"
                                                   value="{{ old('webhook_path', $settings['webhook_path'] ?? '/api/webhooks/whatsapp') }}"
                                                   placeholder="/api/webhooks/whatsapp">
                                            <small class="text-muted">مسار Webhook في تطبيقك</small>
                                            @error('webhook_path')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Default From</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="default_from" 
                                                   id="default_from"
                                                   value="{{ old('default_from', $settings['default_from'] ?? '') }}"
                                                   placeholder="رقم الهاتف الافتراضي">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="strict_signature" 
                                                       id="strict_signature"
                                                       value="1"
                                                       {{ ($settings['strict_signature'] ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="strict_signature">
                                                    <strong>تفعيل التحقق الصارم من التوقيع الرقمي</strong>
                                                </label>
                                            </div>
                                            <small class="text-muted">يُنصح بتركه مفعّل للأمان</small>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <div class="alert alert-info mb-0">
                                                <i class="ri-information-line me-2"></i>
                                                <strong>Webhook URL:</strong> 
                                                <code>{{ url($settings['webhook_path'] ?? '/api/webhooks/whatsapp') }}</code>
                                                <br>
                                                استخدم هذا الرابط عند إعداد Webhook في Meta Developer Console
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Auto Reply Settings -->
                            <div class="card custom-card form-card border mb-4 provider-section" id="auto-reply-settings" data-providers="meta,whatsapp_web" style="display: {{ in_array($provider, ['meta', 'whatsapp_web'], true) ? 'block' : 'none' }};">
                                <div class="card-header">
                                    <h6 class="mb-0 fw-semibold fs-15">
                                        <i class="ri-reply-line me-1 text-primary"></i> إعدادات الرد التلقائي
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="auto_reply" 
                                                       id="auto_reply"
                                                       value="1"
                                                       {{ ($settings['auto_reply'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="auto_reply">
                                                    <strong>تفعيل الرد التلقائي</strong>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">رسالة الرد التلقائي</label>
                                            <textarea class="form-control" 
                                                      name="auto_reply_message" 
                                                      id="auto_reply_message"
                                                      rows="3"
                                                      placeholder="شكراً لك، تم استلام رسالتك. سنرد عليك قريباً.">{{ old('auto_reply_message', $settings['auto_reply_message'] ?? 'شكراً لك، تم استلام رسالتك. سنرد عليك قريباً.') }}</textarea>
                                            @error('auto_reply_message')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- WhatsApp Web Info -->
                            <div class="card custom-card form-card border mb-4 provider-section" id="whatsapp-web-settings" data-providers="whatsapp_web" style="display: {{ $provider === 'whatsapp_web' ? 'block' : 'none' }};">
                                <div class="card-header">
                                    <h6 class="mb-0 fw-semibold fs-15">
                                        <i class="ri-qr-code-line me-1 text-primary"></i> WhatsApp Web
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="ri-information-line me-2"></i>
                                        <strong>ملاحظة:</strong> لإعداد WhatsApp Web، يرجى الانتقال إلى صفحة الإعدادات المخصصة.
                                        <div class="mt-2">
                                            <a href="{{ route('admin.whatsapp-web-settings.index') }}" class="btn btn-sm btn-primary">
                                                <i class="ri-settings-3-line me-1"></i>فتح إعدادات WhatsApp Web
                                            </a>
                                            <a href="{{ route('admin.whatsapp-web.connect') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-qr-code-line me-1"></i>ربط WhatsApp Web
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Advanced Settings -->
                            <div class="card custom-card form-card border mb-4 provider-section" id="advanced-settings" data-providers="meta,custom_api,whatsapp_web">
                                <div class="card-header">
                                    <h6 class="mb-0 fw-semibold fs-15">
                                        <i class="ri-settings-4-line me-1 text-primary"></i> إعدادات متقدمة
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Timeout (بالثواني)</label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="timeout" 
                                                   id="timeout"
                                                   value="{{ old('timeout', $settings['timeout'] ?? 30) }}"
                                                   min="1"
                                                   max="300"
                                                   placeholder="30">
                                            <small class="text-muted">المهلة الزمنية لانتظار استجابة API</small>
                                            @error('timeout')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between flex-wrap gap-2 pt-2">
                                <button type="button" class="btn btn-light border" id="test-connection-btn">
                                    <i class="ri-plug-line me-1"></i> اختبار الاتصال
                                </button>
                                <button type="submit" class="btn btn-primary btn-wave">
                                    <i class="ri-save-line me-1"></i> حفظ الإعدادات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Connection Modal -->
<div class="modal fade modal-user-action" id="testConnectionModal" tabindex="-1" aria-labelledby="testConnectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testConnectionModalLabel">اختبار الاتصال</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div id="test-connection-result"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="close-test-modal-btn">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const providerSelect = document.getElementById('whatsapp_provider');
    const providerHint = document.getElementById('provider-hint');
    const providerSections = document.querySelectorAll('.provider-section');
    const testConnectionBtn = document.getElementById('test-connection-btn');
    const providerHints = @json($providerHints);

    let testConnectionModal = null;
    const modalElement = document.getElementById('testConnectionModal');
    if (modalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        testConnectionModal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true,
            focus: true
        });

        modalElement.querySelectorAll('[data-bs-dismiss="modal"], #close-test-modal-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                testConnectionModal?.hide();
            });
        });

        modalElement.addEventListener('click', function(e) {
            if (e.target === modalElement) {
                testConnectionModal?.hide();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalElement.classList.contains('show')) {
                testConnectionModal?.hide();
            }
        });
    }

    function applyProviderVisibility() {
        if (!providerSelect) {
            return;
        }

        const provider = providerSelect.value;

        if (providerHint && providerHints[provider]) {
            providerHint.textContent = providerHints[provider];
        }

        providerSections.forEach(function(section) {
            const allowed = (section.dataset.providers || '').split(',').map(function(s) {
                return s.trim();
            });
            section.style.display = allowed.includes(provider) ? 'block' : 'none';
        });

        const apiVersion = document.getElementById('api_version');
        const phoneNumberId = document.getElementById('phone_number_id');
        const verifyToken = document.getElementById('verify_token');
        const customApiUrl = document.getElementById('custom_api_url');

        if (apiVersion) {
            apiVersion.required = provider === 'meta';
        }
        if (phoneNumberId) {
            phoneNumberId.required = provider === 'meta';
        }
        if (verifyToken) {
            verifyToken.required = provider === 'meta';
        }
        if (customApiUrl) {
            customApiUrl.required = provider === 'custom_api';
        }
    }

    if (providerSelect) {
        providerSelect.addEventListener('change', applyProviderVisibility);
        applyProviderVisibility();
    }

    let isTesting = false;
    if (testConnectionBtn) {
        testConnectionBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (isTesting) {
                return;
            }

            isTesting = true;
            testConnectionBtn.disabled = true;

            const form = document.getElementById('whatsapp-settings-form');
            if (!form) {
                alert('خطأ: لم يتم العثور على النموذج');
                isTesting = false;
                testConnectionBtn.disabled = false;
                return;
            }

            const formData = new FormData(form);
            const resultDiv = document.getElementById('test-connection-result');

            if (resultDiv) {
                resultDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">جاري الاختبار...</span></div><p class="mt-2">جاري اختبار الاتصال...</p></div>';
            }

            testConnectionModal?.show();

            fetch('{{ route("admin.whatsapp-settings.test-connection") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(function(data) {
                if (resultDiv) {
                    if (data.success) {
                        resultDiv.innerHTML = '<div class="alert alert-success"><i class="ri-check-line me-2"></i>' + (data.message || 'تم الاتصال بنجاح!') + '</div>';
                    } else {
                        resultDiv.innerHTML = '<div class="alert alert-danger"><i class="ri-error-warning-line me-2"></i>' + (data.message || 'فشل الاتصال') + '</div>';
                    }
                }
            })
            .catch(function(error) {
                if (resultDiv) {
                    resultDiv.innerHTML = '<div class="alert alert-danger"><i class="ri-error-warning-line me-2"></i>حدث خطأ أثناء الاختبار: ' + error.message + '</div>';
                }
            })
            .finally(function() {
                isTesting = false;
                testConnectionBtn.disabled = false;
            });
        });
    }
});
</script>
@endpush


