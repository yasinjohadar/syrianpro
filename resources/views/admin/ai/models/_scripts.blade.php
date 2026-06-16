<script>
document.addEventListener('DOMContentLoaded', function () {
    const catalog = @json($catalog ?? []);
    const providerConfigs = @json($providerConfigs ?? []);
    const providerLabels = @json($providerLabels ?? []);
    const pageMode = @json($mode ?? 'edit');
    const isEdit = pageMode === 'edit';
    const isCustomOnly = pageMode === 'custom';

    const providerSelect = document.getElementById('ai-model-provider');
    const modelKeyInput = document.getElementById('ai-model-key');
    const modelNameInput = document.getElementById('model-name');
    const quickAdd = document.getElementById('catalog-quick-add');
    const addModeInput = document.getElementById('add_mode');
    const overrideCheckbox = document.getElementById('override-model-key');
    const modelKeyHelp = document.getElementById('model-key-help');
    const form = document.getElementById('ai-model-form');

    if (!providerSelect || !modelKeyInput) {
        return;
    }

    function ensureModelKeyEditable() {
        modelKeyInput.readOnly = false;
        modelKeyInput.disabled = false;
        modelKeyInput.classList.remove('bg-light');
    }

    function setCatalogReadOnly(locked) {
        if (isCustomOnly || isEdit) {
            ensureModelKeyEditable();
            return;
        }

        modelKeyInput.readOnly = locked;
        modelKeyInput.classList.toggle('bg-light', locked);

        if (modelKeyHelp) {
            modelKeyHelp.textContent = locked
                ? 'يُملأ تلقائياً من الكتالوج. فعّل «تعديل يدوي» لتغييره.'
                : 'أدخل أو عدّل معرّف API يدوياً.';
        }
    }

    function refreshCatalogOptions() {
        if (!quickAdd) {
            return;
        }

        const provider = providerSelect.value;
        const items = catalog[provider] || [];
        const currentKey = modelKeyInput.value;

        quickAdd.innerHTML = '<option value="">— اختر موديلاً جاهزاً —</option>';

        if (items.length === 0) {
            const empty = document.createElement('option');
            empty.value = '';
            empty.textContent = 'لا توجد موديلات جاهزة — استخدم صفحة موديل مخصص';
            empty.disabled = true;
            quickAdd.appendChild(empty);
            return;
        }

        items.forEach(function (item) {
            const opt = document.createElement('option');
            opt.value = item.model_key;
            opt.textContent = item.name + ' (' + item.model_key + ')';
            opt.dataset.name = item.name;
            opt.dataset.capabilities = JSON.stringify(item.capabilities || []);
            if (item.model_key === currentKey) {
                opt.selected = true;
            }
            quickAdd.appendChild(opt);
        });
    }

    function applyCatalogSelection(selected) {
        if (!selected || !selected.value) {
            return;
        }

        modelKeyInput.value = selected.value;

        if (modelNameInput && (!modelNameInput.value || modelNameInput.dataset.autoFilled === '1')) {
            modelNameInput.value = selected.dataset.name || '';
            modelNameInput.dataset.autoFilled = '1';
        }

        const caps = JSON.parse(selected.dataset.capabilities || '[]');
        document.querySelectorAll('.capability-check').forEach(function (cb) {
            cb.checked = caps.includes(cb.value);
        });

        setCatalogReadOnly(!overrideCheckbox || !overrideCheckbox.checked);
    }

    if (isCustomOnly) {
        ensureModelKeyEditable();
    } else if (pageMode === 'catalog') {
        setCatalogReadOnly(!overrideCheckbox || !overrideCheckbox.checked);
        refreshCatalogOptions();

        if (quickAdd) {
            quickAdd.addEventListener('change', function () {
                applyCatalogSelection(quickAdd.options[quickAdd.selectedIndex]);
            });
        }

        if (overrideCheckbox) {
            overrideCheckbox.addEventListener('change', function () {
                setCatalogReadOnly(!overrideCheckbox.checked);
                if (overrideCheckbox.checked) {
                    modelKeyInput.focus();
                }
            });
        }
    }

    function updateProviderKeyStatus() {
        const badge = document.getElementById('provider-key-status-badge');
        const nameEl = document.getElementById('provider-api-key-provider-name');
        if (!providerSelect || !badge) {
            return;
        }

        const slug = providerSelect.value;
        if (nameEl) {
            nameEl.textContent = providerLabels[slug] || slug;
        }

        const cfg = providerConfigs[slug] || {};
        if (cfg.has_key) {
            badge.textContent = 'مفتاح محفوظ';
            badge.className = 'badge bg-success-transparent text-success';
        } else {
            badge.textContent = 'لا يوجد مفتاح';
            badge.className = 'badge bg-warning-transparent text-warning';
        }
    }

    if (providerSelect && quickAdd) {
        providerSelect.addEventListener('change', function () {
            refreshCatalogOptions();
            updateProviderKeyStatus();
        });
    }

    if (providerSelect) {
        updateProviderKeyStatus();
    }

    if (modelNameInput) {
        modelNameInput.addEventListener('input', function () {
            if (modelNameInput.value.trim() !== '') {
                modelNameInput.dataset.autoFilled = '0';
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function () {
            ensureModelKeyEditable();
        });
    }
});
</script>
