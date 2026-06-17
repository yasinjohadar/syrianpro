@php
    $job = $job ?? null;
    $isEdit = $job !== null;
    $formAction = $isEdit ? route('company.jobs.update', $job) : route('company.jobs.store');
    $defaultLogo = old('logo', $job?->logo ?: ($company->logo ?? '💼'));
    $previewImage = $isEdit && $job->logo_image ? $job->logoUrl() : null;
@endphp

<form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-4">
        <div class="col-lg-4 order-lg-2">
            <div class="card custom-card form-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0 fw-semibold fs-15"><i class="ri-image-line me-1 text-primary"></i> شعار الوظيفة</h6>
                </div>
                <div class="card-body">
                    <div class="user-avatar-upload-wrap">
                        <div class="user-avatar-preview-wrap">
                            <img id="job-logo-preview" class="user-avatar-preview {{ $previewImage ? '' : 'd-none' }}"
                                 src="{{ $previewImage }}" alt="">
                            <span id="job-icon-preview" class="user-avatar-initial {{ $previewImage ? 'd-none' : '' }}" style="font-size:2rem;">
                                {{ $defaultLogo }}
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
                    @if($isEdit && $job->logo_image)
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="remove_logo_image" value="1" id="remove_logo_image">
                            <label class="form-check-label fs-12" for="remove_logo_image">حذف الصورة الحالية</label>
                        </div>
                    @endif
                    <div class="mt-3">
                        <label class="form-label fw-semibold fs-13">أيقونة (emoji)</label>
                        <input type="text" name="logo" id="job-icon-input"
                               class="form-control form-input-enhanced"
                               value="{{ $defaultLogo }}" placeholder="💻">
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
                                   {{ old('is_active', optional($job)->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">وظيفة نشطة</label>
                        </div>
                    </div>
                    <div class="account-switch-panel mb-3">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_new" value="1" id="is_new"
                                   {{ old('is_new', optional($job)->is_new ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_new">شارة «جديد»</label>
                        </div>
                    </div>
                    <div class="account-switch-panel">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_syria_friendly" value="1" id="is_syria_friendly"
                                   {{ old('is_syria_friendly', optional($job)->is_syria_friendly ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_syria_friendly">Syria-friendly</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card form-card">
                <div class="card-header">
                    <h6 class="mb-0 fw-semibold fs-15"><i class="ri-building-2-line me-1 text-primary"></i> الشركة</h6>
                </div>
                <div class="card-body">
                    <input type="hidden" name="company_name" value="{{ $company->name }}">
                    <p class="fw-semibold mb-1">{{ $company->name }}</p>
                    <p class="text-muted fs-12 mb-0">{{ $company->sector ?? 'شركة' }} · {{ $company->location ?? 'عن بُعد' }}</p>
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
                            <label class="form-label fw-semibold">المسمى الوظيفي <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-input-enhanced @error('title') is-invalid @enderror"
                                   value="{{ old('title', optional($job)->title) }}" required placeholder="مثال: مطور React أول">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">التخصص التقني</label>
                            <select name="tech_specialty_id" class="form-select form-input-enhanced">
                                <option value="">— اختر —</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}" @selected(old('tech_specialty_id', optional($job)->tech_specialty_id) == $specialty->id)>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">الموقع <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control form-input-enhanced @error('location') is-invalid @enderror"
                                   value="{{ old('location', optional($job)->location ?: 'عن بُعد') }}" required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">نوع الدوام <span class="text-danger">*</span></label>
                            <input type="text" name="employment_type" class="form-control form-input-enhanced"
                                   value="{{ old('employment_type', optional($job)->employment_type ?: 'دوام كامل') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">نوع العمل</label>
                            <select name="remote_type" class="form-select form-input-enhanced" required>
                                <option value="full-remote" @selected(old('remote_type', optional($job)->remote_type ?: 'full-remote') === 'full-remote')>عن بُعد كامل</option>
                                <option value="hybrid" @selected(old('remote_type', optional($job)->remote_type) === 'hybrid')>هجين</option>
                                <option value="onsite" @selected(old('remote_type', optional($job)->remote_type) === 'onsite')>حضوري</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Timezone</label>
                            <input type="text" name="timezone" class="form-control form-input-enhanced"
                                   value="{{ old('timezone', optional($job)->timezone ?: 'UTC+2') }}" placeholder="UTC+2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">تاريخ النشر</label>
                            <input type="datetime-local" name="published_at" class="form-control form-input-enhanced"
                                   value="{{ old('published_at', $job?->published_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">الراتب من ($)</label>
                            <input type="number" name="salary_min" min="0" class="form-control form-input-enhanced"
                                   value="{{ old('salary_min', optional($job)->salary_min) }}" placeholder="800">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">الراتب إلى ($)</label>
                            <input type="number" name="salary_max" min="0" class="form-control form-input-enhanced"
                                   value="{{ old('salary_max', optional($job)->salary_max) }}" placeholder="1500">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">العملة</label>
                            <input type="text" name="currency" class="form-control form-input-enhanced"
                                   value="{{ old('currency', optional($job)->currency ?: '$') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">طرق الدفع</label>
                            <input type="text" name="payment_methods_text" class="form-control form-input-enhanced"
                                   value="{{ old('payment_methods_text', $job ? implode(', ', $job->payment_methods ?? []) : implode(', ', $company->payment_methods ?? ['Wise', 'PayPal'])) }}"
                                   placeholder="Wise, PayPal, Bank Transfer">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">المهارات المطلوبة</label>
                            <input type="text" name="skills_text" class="form-control form-input-enhanced"
                                   value="{{ old('skills_text', $job ? implode(', ', $job->skills ?? []) : '') }}"
                                   placeholder="React, TypeScript, Node.js">
                            <p class="text-muted fs-12 mb-0 mt-1">افصل بين المهارات بفاصلة</p>
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
                        <label class="form-label fw-semibold">وصف الوظيفة</label>
                        <textarea name="description" rows="4" class="form-control form-input-enhanced" placeholder="وصف الوظيفة Remote...">{{ old('description', optional($job)->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">المهام والمسؤوليات</label>
                        <textarea name="responsibilities_text" rows="4" class="form-control form-input-enhanced" placeholder="- مهمة أولى">{{ old('responsibilities_text', $job ? implode("\n", $job->responsibilities ?? []) : '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">المتطلبات</label>
                        <textarea name="requirements_text" rows="4" class="form-control form-input-enhanced" placeholder="- خبرة 3+ سنوات">{{ old('requirements_text', $job ? implode("\n", $job->requirements ?? []) : '') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">المميزات</label>
                        <textarea name="benefits_text" rows="4" class="form-control form-input-enhanced" placeholder="- راتب USD">{{ old('benefits_text', $job ? implode("\n", $job->benefits ?? []) : '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('company.jobs.index') }}" class="btn btn-light border">إلغاء</a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line me-1"></i> {{ $isEdit ? 'حفظ التعديلات' : 'نشر الوظيفة' }}
                </button>
            </div>
        </div>
    </div>
</form>

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
