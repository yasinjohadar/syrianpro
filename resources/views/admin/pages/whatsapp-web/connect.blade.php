@extends('admin.layouts.master')

@section('page-title', 'ربط WhatsApp Web')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'واتساب', 'url' => route('admin.whatsapp-settings.index')],
                ['label' => 'ربط WhatsApp Web'],
            ],
            'title' => 'ربط WhatsApp Web',
            'subtitle' => 'اربط جهازك الشخصي مع النظام عبر QR Code',
            'actions' => '<a href="' . route('admin.whatsapp-settings.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> العودة للإعدادات</a>',
        ])

        <div class="row">
            <div class="col-xl-8 col-lg-10 col-md-12 mx-auto">
                <div class="card custom-card form-card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-qr-code-line me-1 text-primary"></i> حالة الاتصال</h6>
                    </div>
                    <div class="card-body">
                        @if($session && $session->isConnected())
                            <div class="text-center py-4">
                                <div class="empty-state-icon mx-auto mb-3" style="width:80px;height:80px;font-size:2.5rem;background:rgba(25,135,84,.1);color:#198754;">
                                    <i class="ri-checkbox-circle-fill"></i>
                                </div>
                                <h4 class="text-success mb-2 fw-bold">متصل بنجاح</h4>
                                <div class="row g-3 text-start mt-3 mb-4">
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-3 bg-light h-100">
                                            <div class="text-muted fs-12 mb-1">الاسم</div>
                                            <div class="fw-bold">{{ $session->name ?? 'غير محدد' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-3 bg-light h-100">
                                            <div class="text-muted fs-12 mb-1">رقم الهاتف</div>
                                            <div class="fw-bold" dir="ltr">{{ $session->phone_number ?? 'غير محدد' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 rounded-3 bg-light h-100">
                                            <div class="text-muted fs-12 mb-1">تاريخ الاتصال</div>
                                            <div class="fw-medium">{{ $session->connected_at?->format('Y-m-d H:i:s') ?? 'غير محدد' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-wave" data-bs-toggle="modal" data-bs-target="#disconnectModal">
                                    <i class="ri-link-unlink me-1"></i> قطع الاتصال
                                </button>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div id="qr-container" class="mb-4" style="display: none;">
                                    <h5 class="mb-3 fw-bold">امسح QR Code باستخدام WhatsApp</h5>
                                    <div class="d-flex justify-content-center mb-3">
                                        <div id="qr-code-display" class="border rounded-3 p-3 bg-white shadow-sm">
                                        </div>
                                    </div>
                                    <p class="text-muted small">
                                        <i class="ri-information-line me-1"></i>
                                        افتح WhatsApp على هاتفك → الإعدادات → الأجهزة المرتبطة → ربط جهاز
                                    </p>
                                </div>

                                <div id="loading-container" class="text-center py-4" style="display: none;">
                                    <div class="spinner-border text-primary mb-3" role="status">
                                        <span class="visually-hidden">جاري التحميل...</span>
                                    </div>
                                    <p>جاري إعداد الاتصال...</p>
                                </div>

                                <div id="error-container" class="alert alert-danger border-0 rounded-3" style="display: none;">
                                    <i class="ri-error-warning-line me-2"></i>
                                    <span id="error-message"></span>
                                    <div class="mt-3">
                                        <small>
                                            <strong>ملاحظة:</strong> يجب أن يكون Node.js service يعمل على:
                                            <code>{{ $nodejsUrl ?? 'http://localhost:3000' }}</code>
                                            <br>
                                            راجع ملف <code>whatsapp-web-service-README.md</code> لمعرفة كيفية إعداد الخدمة.
                                        </small>
                                    </div>
                                </div>

                                <div id="action-buttons" class="mt-4">
                                    <button type="button" class="btn btn-primary btn-wave" onclick="startConnection()">
                                        <i class="ri-qr-code-line me-1"></i> بدء الربط
                                    </button>
                                </div>

                                <div class="alert alert-info border-0 rounded-3 mt-4 text-start">
                                    <i class="ri-information-line me-2"></i>
                                    <strong>مهم:</strong> يجب إعداد Node.js service أولاً قبل استخدام هذه الميزة.
                                    <br>
                                    <small>راجع ملف <code>whatsapp-web-service-README.md</code> في المجلد الرئيسي للمشروع.</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@if($session && $session->isConnected())
<div class="modal fade modal-user-action" id="disconnectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">قطع الاتصال</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-0">هل أنت متأكد من قطع الاتصال؟</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger" id="confirmDisconnectBtn">قطع الاتصال</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
let currentSessionId = null;
let statusCheckInterval = null;
const disconnectSessionId = @json($session && $session->isConnected() ? $session->session_id : null);

function startConnection() {
    const loadingContainer = document.getElementById('loading-container');
    const qrContainer = document.getElementById('qr-container');
    const errorContainer = document.getElementById('error-container');
    const actionButtons = document.getElementById('action-buttons');

    if (loadingContainer) loadingContainer.style.display = 'block';
    if (qrContainer) qrContainer.style.display = 'none';
    if (errorContainer) errorContainer.style.display = 'none';
    if (actionButtons) actionButtons.style.display = 'none';

    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 10000);

    fetch('{{ route("admin.whatsapp-web.start-connection") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        signal: controller.signal
    })
    .then(response => {
        clearTimeout(timeoutId);
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            currentSessionId = data.session_id;
            if (data.qr_code) {
                displayQrCode(data.qr_code);
                startStatusCheck(data.session_id);
            } else {
                showError('لم يتم الحصول على QR Code. تأكد من أن Node.js service يعمل.');
            }
        } else {
            showError(data.message || 'فشل بدء عملية الربط');
        }
    })
    .catch(error => {
        clearTimeout(timeoutId);
        let errorMessage = 'حدث خطأ أثناء الاتصال';
        if (error.name === 'AbortError') {
            errorMessage = 'انتهت مهلة الاتصال. تأكد من أن Node.js service يعمل على: {{ $nodejsUrl ?? "http://localhost:3000" }}';
        } else if (error.message) {
            errorMessage = error.message;
        }
        showError(errorMessage);
    })
    .finally(() => {
        if (loadingContainer) loadingContainer.style.display = 'none';
    });
}

function displayQrCode(qrCodeData) {
    const qrContainer = document.getElementById('qr-container');
    const qrDisplay = document.getElementById('qr-code-display');

    if (qrContainer && qrDisplay) {
        if (qrCodeData.startsWith('data:image')) {
            qrDisplay.innerHTML = `<img src="${qrCodeData}" alt="QR Code" style="max-width: 300px;">`;
        } else if (qrCodeData.startsWith('<svg')) {
            qrDisplay.innerHTML = qrCodeData;
        } else {
            qrDisplay.innerHTML = `<img src="data:image/png;base64,${qrCodeData}" alt="QR Code" style="max-width: 300px;">`;
        }
        qrContainer.style.display = 'block';
    }
}

function startStatusCheck(sessionId) {
    if (statusCheckInterval) {
        clearInterval(statusCheckInterval);
    }
    statusCheckInterval = setInterval(() => checkStatus(sessionId), 3000);
    checkStatus(sessionId);
}

function checkStatus(sessionId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 5000);

    fetch(`{{ url('admin/whatsapp-web/status') }}/${sessionId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        signal: controller.signal
    })
    .then(response => {
        clearTimeout(timeoutId);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return response.json();
    })
    .then(data => {
        if (data.success && data.connected) {
            if (statusCheckInterval) clearInterval(statusCheckInterval);
            window.location.reload();
        } else if (data.success && data.status === 'connecting' && data.qr_code) {
            displayQrCode(data.qr_code);
        }
    })
    .catch(() => {});
}

function performDisconnect(sessionId) {
    fetch(`{{ url('admin/whatsapp-web/disconnect') }}/${sessionId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('فشل قطع الاتصال: ' + (data.message || 'خطأ غير معروف'));
        }
    })
    .catch(() => alert('حدث خطأ أثناء قطع الاتصال'));
}

function showError(message) {
    const errorContainer = document.getElementById('error-container');
    const errorMessage = document.getElementById('error-message');
    const actionButtons = document.getElementById('action-buttons');
    const loadingContainer = document.getElementById('loading-container');

    if (errorContainer && errorMessage) {
        errorMessage.textContent = message;
        errorContainer.style.display = 'block';
    }
    if (actionButtons) actionButtons.style.display = 'block';
    if (loadingContainer) loadingContainer.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    const confirmBtn = document.getElementById('confirmDisconnectBtn');
    if (confirmBtn && disconnectSessionId) {
        confirmBtn.addEventListener('click', function () {
            performDisconnect(disconnectSessionId);
        });
    }
});

window.addEventListener('beforeunload', function () {
    if (statusCheckInterval) clearInterval(statusCheckInterval);
});
</script>
@endpush
