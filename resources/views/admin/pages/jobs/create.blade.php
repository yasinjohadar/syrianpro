@extends('admin.layouts.master')

@section('page-title')
    {{ isset($job) ? 'تعديل وظيفة' : 'إضافة وظيفة' }}
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الوظائف', 'url' => route('admin.jobs.index')],
                ['label' => isset($job) ? 'تعديل' : 'إضافة'],
            ],
            'title' => isset($job) ? 'تعديل الوظيفة' : 'إضافة وظيفة جديدة',
            'subtitle' => 'إدارة تفاصيل الوظيفة المعروضة في المنصة',
            'actions' => '<a href="' . route('admin.jobs.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST"
              action="{{ isset($job) ? route('admin.jobs.update', $job) : route('admin.jobs.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($job))
                @method('PUT')
            @endif

            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-image-line me-1 text-primary"></i> الشعار</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $previewLogo = old('logo', $job->logo ?? '💼');
                                $previewImage = isset($job) && $job->logo_image ? $job->logoUrl() : null;
                            @endphp
                            <div class="user-avatar-upload-wrap">
                                <div class="user-avatar-preview-wrap">
                                    <img id="job-logo-preview" class="user-avatar-preview {{ $previewImage ? '' : 'd-none' }}"
                                         src="{{ $previewImage }}" alt="">
                                    <span id="job-icon-preview" class="user-avatar-initial {{ $previewImage ? 'd-none' : '' }}" style="font-size:2rem;">
                                        {{ $previewLogo }}
                                    </span>
                                </div>
                                <label for="job-logo-input" class="user-avatar-upload-btn">
                                    <i class="ri-camera-line"></i> اختر صورة
                                </label>
                                <input type="file" name="logo_image" id="job-logo-input" accept="image/*">
                            </div>
                            @error('logo_image')
                                <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                            @enderror
                            @if(isset($job) && $job->logo_image)
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="remove_logo_image" value="1" id="remove_logo_image">
                                    <label class="form-check-label fs-12" for="remove_logo_image">حذف الصورة الحالية</label>
                                </div>
                            @endif
                            <div class="mt-3">
                                <label class="form-label fw-semibold fs-13">أيقونة (emoji)</label>
                                <input type="text" name="logo" id="job-icon-input"
                                       class="form-control form-input-enhanced"
                                       value="{{ old('logo', $job->logo ?? '') }}" placeholder="💻">
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-settings-3-line me-1 text-primary"></i> الإعدادات</h6>
                        </div>
                        <div class="card-body">
                            <div class="account-switch-panel mb-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                           {{ old('is_active', $job->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">وظيفة نشطة</label>
                                </div>
                            </div>
                            <div class="account-switch-panel mb-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured"
                                           {{ old('is_featured', $job->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_featured">وظيفة مميزة</label>
                                </div>
                            </div>
                            <div class="account-switch-panel mb-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_new" value="1" id="is_new"
                                           {{ old('is_new', $job->is_new ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_new">شارة «جديد»</label>
                                </div>
                            </div>
                            <div class="account-switch-panel">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_syria_friendly" value="1" id="is_syria_friendly"
                                           {{ old('is_syria_friendly', $job->is_syria_friendly ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_syria_friendly">Syria-friendly</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-lg-1">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-briefcase-line me-1 text-primary"></i> معلومات أساسية</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">العنوان <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control form-input-enhanced @error('title') is-invalid @enderror"
                                           value="{{ old('title', $job->title ?? '') }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">الترتيب</label>
                                    <input type="number" name="order" min="0" class="form-control form-input-enhanced"
                                           value="{{ old('order', $job->order ?? 0) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الشركة <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control form-input-enhanced @error('company_name') is-invalid @enderror"
                                           value="{{ old('company_name', $job->company_name ?? '') }}" required>
                                    @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">التخصص التقني</label>
                                    <select name="tech_specialty_id" class="form-select form-input-enhanced">
                                        <option value="">— بدون —</option>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty->id }}" @selected(old('tech_specialty_id', $job->tech_specialty_id ?? '') == $specialty->id)>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الموقع <span class="text-danger">*</span></label>
                                    <input type="text" name="location" class="form-control form-input-enhanced @error('location') is-invalid @enderror"
                                           value="{{ old('location', $job->location ?? '') }}" required>
                                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">نوع الدوام <span class="text-danger">*</span></label>
                                    <input type="text" name="employment_type" class="form-control form-input-enhanced"
                                           value="{{ old('employment_type', $job->employment_type ?? 'دوام كامل') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Remote</label>
                                    <select name="remote_type" class="form-select form-input-enhanced" required>
                                        <option value="full-remote" @selected(old('remote_type', $job->remote_type ?? 'full-remote') === 'full-remote')>عن بُعد كامل</option>
                                        <option value="hybrid" @selected(old('remote_type', $job->remote_type ?? '') === 'hybrid')>هجين</option>
                                        <option value="onsite" @selected(old('remote_type', $job->remote_type ?? '') === 'onsite')>حضوري</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Timezone</label>
                                    <input type="text" name="timezone" class="form-control form-input-enhanced"
                                           value="{{ old('timezone', $job->timezone ?? '') }}" placeholder="UTC+2">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">تاريخ النشر</label>
                                    <input type="datetime-local" name="published_at" class="form-control form-input-enhanced"
                                           value="{{ old('published_at', isset($job) && $job->published_at ? $job->published_at->format('Y-m-d\TH:i') : '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">الراتب من ($)</label>
                                    <input type="number" name="salary_min" min="0" class="form-control form-input-enhanced"
                                           value="{{ old('salary_min', $job->salary_min ?? '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">الراتب إلى ($)</label>
                                    <input type="number" name="salary_max" min="0" class="form-control form-input-enhanced"
                                           value="{{ old('salary_max', $job->salary_max ?? '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">العملة</label>
                                    <input type="text" name="currency" class="form-control form-input-enhanced"
                                           value="{{ old('currency', $job->currency ?? '$') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">طرق الدفع</label>
                                    <input type="text" name="payment_methods_text" class="form-control form-input-enhanced"
                                           value="{{ old('payment_methods_text', isset($job) ? implode(', ', $job->payment_methods ?? []) : '') }}"
                                           placeholder="Wise, PayPal">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">المهارات</label>
                                    <input type="text" name="skills_text" class="form-control form-input-enhanced"
                                           value="{{ old('skills_text', isset($job) ? implode(', ', $job->skills ?? []) : '') }}"
                                           placeholder="React, TypeScript, Node.js">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Tags (داخلية)</label>
                                    <input type="text" name="tags_text" class="form-control form-input-enhanced"
                                           value="{{ old('tags_text', isset($job) ? implode(', ', $job->tags ?? []) : '') }}"
                                           placeholder="full-time, remote, frontend">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Tag Labels (JSON)</label>
                                    <textarea name="tag_labels_json" rows="3" class="form-control form-input-enhanced"
                                              placeholder='[{"t":"عن بُعد 🌐","c":"teal"},{"t":"Frontend","c":"blue"}]'>{{ old('tag_labels_json', isset($job) && $job->tag_labels ? json_encode($job->tag_labels, JSON_UNESCAPED_UNICODE) : '') }}</textarea>
                                    <p class="text-muted fs-12 mb-0 mt-1">اتركه فارغاً لإنشاء tags تلقائياً من Remote والتخصص.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-file-text-line me-1 text-primary"></i> التفاصيل</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">عن الوظيفة</label>
                                <textarea name="description" rows="4" class="form-control form-input-enhanced">{{ old('description', $job->description ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">المهام والمسؤوليات (سطر لكل نقطة)</label>
                                <textarea name="responsibilities_text" rows="4" class="form-control form-input-enhanced">{{ old('responsibilities_text', isset($job) ? implode("\n", $job->responsibilities ?? []) : '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">المتطلبات (سطر لكل نقطة)</label>
                                <textarea name="requirements_text" rows="4" class="form-control form-input-enhanced">{{ old('requirements_text', isset($job) ? implode("\n", $job->requirements ?? []) : '') }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">المميزات (سطر لكل نقطة)</label>
                                <textarea name="benefits_text" rows="4" class="form-control form-input-enhanced">{{ old('benefits_text', isset($job) ? implode("\n", $job->benefits ?? []) : '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.jobs.index') }}" class="btn btn-light border">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> {{ isset($job) ? 'حفظ التعديلات' : 'إنشاء الوظيفة' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('job-logo-input');
    var iconInput = document.getElementById('job-icon-input');
    var imgPreview = document.getElementById('job-logo-preview');
    var iconPreview = document.getElementById('job-icon-preview');
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                imgPreview.src = URL.createObjectURL(this.files[0]);
                imgPreview.classList.remove('d-none');
                iconPreview.classList.add('d-none');
            }
        });
    }
    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function () {
            iconPreview.textContent = this.value || '💼';
        });
    }
});
</script>
@endpush
