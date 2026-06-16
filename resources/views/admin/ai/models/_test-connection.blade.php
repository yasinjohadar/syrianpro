{{-- مكوّن فحص اتصال AI (صفحة الإضافة + قائمة الموديلات) --}}
<div class="modal fade" id="aiTestConnectionModal" tabindex="-1" aria-labelledby="aiTestConnectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aiTestConnectionModalLabel">فحص اتصال الموديل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div id="ai-test-connection-result">
                    <div class="text-center text-muted py-3">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        جاري فحص الاتصال…
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
(function () {
    const testUrl = @json(route('admin.ai.models.test-connection'));
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    let modalInstance = null;

    function getModal() {
        const el = document.getElementById('aiTestConnectionModal');
        if (!el) return null;
        if (!modalInstance && typeof bootstrap !== 'undefined') {
            modalInstance = new bootstrap.Modal(el);
        }
        return modalInstance;
    }

    function renderResult(html) {
        const box = document.getElementById('ai-test-connection-result');
        if (box) {
            box.innerHTML = html;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    window.testAiModelConnection = async function (options) {
        const provider = options?.provider;
        const modelKey = options?.modelKey;
        const modelName = options?.modelName || modelKey;
        const providerKey = options?.providerKey || null;
        const triggerBtn = options?.button || null;

        if (!provider || !modelKey) {
            alert('اختر المزود وأدخل معرّف الموديل أولاً.');
            return;
        }

        const modal = getModal();
        if (modal) {
            renderResult(
                '<div class="text-center text-muted py-3">' +
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>' +
                'جاري فحص: <strong>' + escapeHtml(modelName) + '</strong>…' +
                '</div>'
            );
            modal.show();
        }

        if (triggerBtn) {
            triggerBtn.disabled = true;
        }

        try {
            const body = { provider, model_key: modelKey };
            if (providerKey) {
                body.provider_key = providerKey;
            }

            const response = await fetch(testUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(body),
            });

            const data = await response.json();

            if (data.success) {
                renderResult(
                    '<div class="alert alert-success mb-0">' +
                    '<i class="ri-checkbox-circle-line me-1"></i>' + escapeHtml(data.message) +
                    (data.reply ? '<hr class="my-2"><small class="text-muted">رد الموديل: ' + escapeHtml(data.reply) + '</small>' : '') +
                    '</div>'
                );
            } else {
                renderResult(
                    '<div class="alert alert-danger mb-0">' +
                    '<i class="ri-error-warning-line me-1"></i>' + escapeHtml(data.message || 'فشل الاتصال') +
                    '</div>'
                );
            }
        } catch (err) {
            renderResult(
                '<div class="alert alert-danger mb-0">' +
                '<i class="ri-error-warning-line me-1"></i>تعذّر إتمام الطلب: ' + escapeHtml(err.message) +
                '</div>'
            );
        } finally {
            if (triggerBtn) {
                triggerBtn.disabled = false;
            }
        }
    };

    window.testAiModelConnectionFromForm = function (button) {
        const form = document.getElementById('ai-model-form');
        const provider = form?.querySelector('[name="provider"]')?.value
            || document.getElementById('ai-model-provider')?.value;
        const modelKey = form?.querySelector('[name="model_key"]')?.value
            || document.getElementById('ai-model-key')?.value;
        const modelName = form?.querySelector('[name="name"]')?.value
            || document.getElementById('model-name')?.value;
        const providerKey = form?.querySelector('[name="provider_api_key"]')?.value
            || document.getElementById('provider-api-key')?.value;

        testAiModelConnection({
            provider,
            modelKey,
            modelName,
            providerKey: providerKey || null,
            button,
        });
    };
})();
</script>
@endpush
@endonce
