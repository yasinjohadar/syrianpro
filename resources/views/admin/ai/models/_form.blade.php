@php
    $entry = $entry ?? null;
    $mode = $mode ?? 'edit';
    $isEdit = $mode === 'edit';
    $isCustomOnly = ! $isEdit && $mode === 'custom';
    $isCatalogPage = ! $isEdit && $mode === 'catalog';
    $addMode = old('add_mode', $isCustomOnly ? 'custom' : 'catalog');
    $selectedCapabilities = old('capabilities', $entry['capabilities'] ?? []);
    $selectedDefaults = old('is_default', $entry['is_default'] ?? []);
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if($isCustomOnly)
    <input type="hidden" name="add_mode" id="add_mode" value="custom">
    <div class="alert alert-warning mb-4">
        <i class="ri-sparkling-line me-1"></i>
        <strong>موديل مخصص:</strong> أدخل <strong>اسم الموديل</strong> و<strong>معرّف API</strong> كما يظهر في وثائق المزود
        (OpenRouter، Ollama، Azure، إلخ).
        <a href="{{ route('admin.ai.models.create', ['mode' => 'catalog']) }}" class="alert-link d-block mt-2">أو اختر من الكتالوج الجاهز</a>
    </div>
@elseif($isCatalogPage)
    <input type="hidden" name="add_mode" id="add_mode" value="{{ $addMode }}">

    <ul class="nav nav-tabs mb-4" id="modelAddTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link {{ $addMode === 'catalog' ? 'active' : '' }}"
                    id="tab-catalog" data-add-mode="catalog" role="tab">
                <i class="ri-list-check me-1"></i> من الكتالوج
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ route('admin.ai.models.create', ['mode' => 'custom']) }}" class="nav-link">
                <i class="ri-edit-box-line me-1"></i> موديل مخصص
            </a>
        </li>
    </ul>

    <div id="catalog-mode-hint" class="alert alert-info mb-4">
        <i class="ri-information-line me-1"></i>
        اختر المزود ثم الموديل من القائمة. يمكنك تعديل <strong>اسم الموديل</strong> قبل الحفظ.
        <a href="{{ route('admin.ai.models.create', ['mode' => 'custom']) }}" class="alert-link ms-1">الموديل غير موجود؟ أضفه يدوياً</a>
    </div>
@endif

<div class="row g-3">
    <div class="col-12">
        <label for="model-name" class="form-label fw-semibold">
            اسم الموديل <span class="text-danger">*</span>
        </label>
        <input type="text" name="name" id="model-name" class="form-control form-control-lg" required
               value="{{ old('name', $entry['name'] ?? '') }}"
               placeholder="مثال: Claude للمقالات، أو GPT-4o Mini للشات">
        <div class="form-text">اسم وصفي يظهر في لوحة التحكم — يمكن أن يختلف عن معرّف API.</div>
    </div>

    <div class="col-md-6">
        <label for="ai-model-provider" class="form-label fw-semibold">المزود <span class="text-danger">*</span></label>
        <select name="provider" id="ai-model-provider" class="form-select" required>
            @foreach($supportedProviders as $slug)
                <option value="{{ $slug }}" @selected(old('provider', $entry['provider'] ?? 'openai') === $slug)>
                    {{ $providerLabels[$slug] ?? $slug }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <div class="border rounded p-3 bg-light" id="provider-api-key-box">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <label for="provider-api-key" class="form-label fw-semibold mb-0">
                    مفتاح API — <span id="provider-api-key-provider-name">{{ $providerLabels[old('provider', $entry['provider'] ?? 'openai')] ?? 'المزود' }}</span>
                </label>
                <span id="provider-key-status-badge" class="badge bg-secondary-transparent text-secondary">جاري التحقق…</span>
            </div>
            <input type="password" name="provider_api_key" id="provider-api-key" class="form-control"
                   autocomplete="new-password"
                   placeholder="sk-... أو مفتاح المزود">
            <div class="form-text mt-2">
                مطلوب لـ «فحص الاتصال» والاستخدام الفعلي. اتركه فارغاً إذا كان المفتاح محفوظاً مسبقاً في
                <a href="{{ route('admin.ai.settings.index') }}">إعدادات الذكاء الاصطناعي</a>.
                يُحفظ مشفّراً عند حفظ الموديل.
            </div>
        </div>
    </div>

    @if($isCatalogPage || $isEdit)
    <div class="col-md-6" id="catalog-picker-wrap">
        <label for="catalog-quick-add" class="form-label fw-semibold">اختيار من الكتالوج</label>
        <select id="catalog-quick-add" class="form-select">
            <option value="">— اختر موديلاً جاهزاً —</option>
        </select>
        <div class="form-text">قائمة موديلات شائعة للمزود المحدد.</div>
    </div>
    @endif

    <div class="col-md-6" id="model-key-wrap">
        <label for="ai-model-key" class="form-label fw-semibold">
            معرّف الموديل عند المزود (Model ID) <span class="text-danger">*</span>
        </label>
        <input type="text" name="model_key" id="ai-model-key" class="form-control" required
               value="{{ old('model_key', $entry['model_key'] ?? '') }}"
               placeholder="مثال: gpt-4o-mini أو meta-llama/llama-3-70b-instruct"
               @if($isCustomOnly) autofocus @endif>
        <div class="form-text" id="model-key-help">
            @if($isCustomOnly)
                أدخل المعرّف بالضبط كما في لوحة المزود (مثال: anthropic/claude-3.5-sonnet على OpenRouter).
            @else
                المعرّف الذي يرسل للـ API — يُملأ من الكتالوج أو أدخله يدوياً.
            @endif
        </div>
    </div>

    @if($isCatalogPage)
    <div class="col-md-6" id="override-model-key-wrap">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" id="override-model-key">
            <label class="form-check-label" for="override-model-key">
                تعديل معرّف API يدوياً (بعد الاختيار من الكتالوج)
            </label>
        </div>
    </div>
    @endif

    <div class="col-md-6">
        <label class="form-label">الحالة</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   @checked(old('is_active', $entry['is_active'] ?? true))>
            <label class="form-check-label" for="is_active">مفعّل</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label d-block fw-semibold">القدرات <span class="text-danger">*</span></label>
        <div class="row">
            @foreach($capabilityLabels as $key => $label)
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input capability-check" type="checkbox"
                               name="capabilities[]" value="{{ $key }}" id="cap_{{ $key }}"
                               @checked(in_array($key, $selectedCapabilities, true))>
                        <label class="form-check-label" for="cap_{{ $key }}">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12">
        <label class="form-label d-block fw-semibold">افتراضي لـ</label>
        <p class="text-muted fs-12 mb-2">عند التفعيل يُحدَّث ملف الإعدادات تلقائياً لهذه القدرة</p>
        <div class="row">
            @foreach($capabilityLabels as $key => $label)
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="is_default[]" value="{{ $key }}" id="def_{{ $key }}"
                               @checked(in_array($key, $selectedDefaults, true))>
                        <label class="form-check-label" for="def_{{ $key }}">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
