@extends('admin.layouts.master')

@section('page-title')
    إعدادات الذكاء الاصطناعي
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الذكاء الاصطناعي'],
                ['label' => 'الإعدادات'],
            ],
            'title' => 'إعدادات الذكاء الاصطناعي',
            'subtitle' => 'تُحفظ الإعدادات في <code>config/ai-panel.php</code> (وليس في ملف <code>.env</code>)',
            'actions' => '<a href="' . route('admin.ai.models.index') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-cpu-line me-1 fs-18"></i> إدارة الموديلات</a>',
        ])

        @if (!$panelExists)
            <div class="alert alert-info mb-4">
                <i class="ri-information-line me-1"></i>
                سيتم إنشاء ملف <code>config/ai-panel.php</code> عند أول حفظ.
            </div>
        @endif

        <form action="{{ route('admin.ai.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-xl-8">

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-cloud-line me-1 text-primary"></i> المزودون الافتراضيون
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach([
                                    'default' => 'النص والمحادثة',
                                    'default_for_images' => 'الصور',
                                    'default_for_embeddings' => 'التضمينات',
                                    'default_for_audio' => 'الصوت',
                                ] as $field => $label)
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ $label }}</label>
                                        <select name="{{ $field }}" class="form-select form-input-enhanced" required>
                                            @foreach($supportedProviders as $slug)
                                                <option value="{{ $slug }}"
                                                    @selected(old($field, $panel[$field] ?? $defaults[$field] ?? '') === $slug)>
                                                    {{ $providerLabels[$slug] ?? $slug }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">مهلة الطلب (ثانية)</label>
                                    <input type="number" name="request_timeout" class="form-control form-input-enhanced" min="30" max="600"
                                           value="{{ old('request_timeout', $panel['request_timeout'] ?? $defaults['request_timeout'] ?? 300) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-cpu-line me-1 text-primary"></i> موديلات افتراضية (سريعة)
                            </h6>
                            <p class="text-muted fs-12 mb-0 mt-1">للتحكم الدقيق استخدم <a href="{{ route('admin.ai.models.index') }}">سجل الموديلات</a></p>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($capabilityLabels as $key => $label)
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">{{ $label }}</label>
                                        <input type="text" name="models[{{ $key }}]" class="form-control form-input-enhanced"
                                               value="{{ old('models.'.$key, $panel['models'][$key] ?? $defaults['models'][$key] ?? '') }}" required>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-key-2-line me-1 text-primary"></i> مفاتيح API للمزودين
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted fs-13 mb-3">اترك الحقل فارغاً للإبقاء على المفتاح المحفوظ. المفاتيح تُخزَّن مشفّرة في ملف الإعدادات.</p>
                            <div class="accordion" id="providersAccordion">
                                @foreach($supportedProviders as $slug)
                                    @php
                                        $cfg = $providerConfigs[$slug] ?? [];
                                        $hasKey = !empty($cfg['has_key']);
                                    @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#provider-{{ $slug }}">
                                                {{ $providerLabels[$slug] ?? $slug }}
                                                @if($hasKey)
                                                    <span class="badge-soft badge-soft-success ms-2">مفتاح محفوظ</span>
                                                @endif
                                            </button>
                                        </h2>
                                        <div id="provider-{{ $slug }}" class="accordion-collapse collapse"
                                             data-bs-parent="#providersAccordion">
                                            <div class="accordion-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">مفتاح API</label>
                                                    <input type="password" name="providers[{{ $slug }}][key]"
                                                           class="form-control form-input-enhanced" autocomplete="new-password"
                                                           placeholder="{{ $hasKey ? '•••••••• (محفوظ — اتركه فارغاً للإبقاء)' : 'أدخل المفتاح' }}">
                                                </div>
                                                @if(!empty($defaults['providers'][$slug]['url']))
                                                    <div class="mb-0">
                                                        <label class="form-label fw-semibold">عنوان API (اختياري)</label>
                                                        <input type="text" name="providers[{{ $slug }}][url]"
                                                               class="form-control form-input-enhanced"
                                                               value="{{ old('providers.'.$slug.'.url', $cfg['url'] ?? $defaults['providers'][$slug]['url'] ?? '') }}">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg px-4">
                        <i class="ri-save-line me-1"></i> حفظ الإعدادات
                    </button>
                </div>

                <div class="col-xl-4">
                    <div class="card custom-card form-card border-info">
                        <div class="card-body">
                            <h6 class="mb-3 fw-bold"><i class="ri-file-code-line me-1 text-info"></i> ملف الإعدادات</h6>
                            <p class="fs-13 text-muted mb-2">المسار:</p>
                            <code class="d-block mb-3 small">{{ $panelPath }}</code>
                            <ul class="fs-13 text-muted mb-0 ps-3">
                                <li class="mb-2">لا حاجة لتعديل <code>.env</code> لمفاتيح AI</li>
                                <li class="mb-2">أضف الموديلات من صفحة «إدارة الموديلات»</li>
                                <li>يمكن استيراد موديلات جاهزة من الكتالوج</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
