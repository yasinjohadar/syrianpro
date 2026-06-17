@extends('company.layouts.master')

@section('page-title')
ملف الشركة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'ملف الشركة'],
            ],
            'title' => 'ملف الشركة',
            'subtitle' => 'حدّث بيانات شركتك كما تظهر للمواهب على المنصة',
            'actions' => $company && $company->slug
                ? '<a href="' . route('companies.show', $company) . '" class="btn btn-primary-light btn-wave" target="_blank"><i class="ri-external-link-line me-1"></i> الصفحة العامة</a>'
                : '',
        ])

        <form method="POST" action="{{ route('company.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-image-line me-1 text-primary"></i> شعار الشركة</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $previewLogo = old('logo', optional($company)->logo ?: '🏢');
                                $previewImage = $company?->logo_image ? $company->logoUrl() : null;
                            @endphp
                            <div class="user-avatar-upload-wrap">
                                <div class="user-avatar-preview-wrap">
                                    <img id="company-logo-preview" class="user-avatar-preview {{ $previewImage ? '' : 'd-none' }}"
                                         src="{{ $previewImage }}" alt="">
                                    <span id="company-icon-preview" class="user-avatar-initial {{ $previewImage ? 'd-none' : '' }}" style="font-size:2rem;">
                                        {{ $previewLogo }}
                                    </span>
                                </div>
                                <label for="company-logo-input" class="user-avatar-upload-btn">
                                    <i class="ri-camera-line"></i> اختر صورة
                                </label>
                                <input type="file" name="logo_image" id="company-logo-input" accept="image/*">
                            </div>
                            @error('logo_image')
                                <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                            @enderror
                            @if($company?->logo_image)
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="remove_logo_image" value="1" id="remove_logo_image">
                                    <label class="form-check-label fs-12" for="remove_logo_image">حذف الصورة الحالية</label>
                                </div>
                            @endif
                            <div class="mt-3">
                                <label class="form-label fw-semibold fs-13">أيقونة (emoji)</label>
                                <input type="text" name="logo" id="company-icon-input" class="form-control form-input-enhanced"
                                       value="{{ $previewLogo }}" placeholder="🏢">
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-global-line me-1 text-primary"></i> Remote-friendly</h6>
                        </div>
                        <div class="card-body">
                            <div class="account-switch-panel mb-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_remote_friendly" value="1" id="is_remote_friendly"
                                           {{ old('is_remote_friendly', optional($company)->is_remote_friendly ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_remote_friendly">Remote-friendly</label>
                                </div>
                            </div>
                            <div class="account-switch-panel">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_syria_friendly" value="1" id="is_syria_friendly"
                                           {{ old('is_syria_friendly', optional($company)->is_syria_friendly ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_syria_friendly">Syria-friendly</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-lg-1">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-building-2-line me-1 text-primary"></i> معلومات الشركة</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">اسم الشركة <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-input-enhanced @error('name') is-invalid @enderror"
                                           value="{{ old('name', optional($company)->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">سنة التأسيس</label>
                                    <input type="text" name="founded" class="form-control form-input-enhanced"
                                           value="{{ old('founded', optional($company)->founded) }}" placeholder="2018">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">القطاع</label>
                                    <input type="text" name="sector" class="form-control form-input-enhanced"
                                           value="{{ old('sector', optional($company)->sector) }}" placeholder="تطوير البرمجيات">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الموقع</label>
                                    <input type="text" name="location" class="form-control form-input-enhanced"
                                           value="{{ old('location', optional($company)->location) }}" placeholder="دمشق / عن بُعد">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Timezone</label>
                                    <input type="text" name="timezone" class="form-control form-input-enhanced"
                                           value="{{ old('timezone', optional($company)->timezone ?: 'UTC+2') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">حجم الفريق</label>
                                    <input type="text" name="team_size" class="form-control form-input-enhanced"
                                           value="{{ old('team_size', optional($company)->team_size) }}" placeholder="10–30">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">نبذة عن الشركة</label>
                                    <textarea name="about" rows="4" class="form-control form-input-enhanced">{{ old('about', optional($company)->about) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">الرسالة</label>
                                    <textarea name="mission" rows="3" class="form-control form-input-enhanced">{{ old('mission', optional($company)->mission) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">طرق الدفع</label>
                                    <input type="text" name="payment_methods_text" class="form-control form-input-enhanced"
                                           value="{{ old('payment_methods_text', $company ? implode(', ', $company->payment_methods ?? []) : 'Wise, PayPal') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">التقنيات</label>
                                    <input type="text" name="tech_stack_text" class="form-control form-input-enhanced"
                                           value="{{ old('tech_stack_text', $company ? implode(', ', $company->tech_stack ?? []) : '') }}"
                                           placeholder="React, Node.js, AWS">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">المميزات (سطر لكل ميزة)</label>
                                    <textarea name="perks_text" rows="4" class="form-control form-input-enhanced">{{ old('perks_text', $company ? implode("\n", $company->perks ?? []) : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('company.partials.profile.contact-fields', ['company' => $company])

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('company.dashboard') }}" class="btn btn-light border">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> حفظ الملف
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
    var fileInput = document.getElementById('company-logo-input');
    var iconInput = document.getElementById('company-icon-input');
    var imgPreview = document.getElementById('company-logo-preview');
    var iconPreview = document.getElementById('company-icon-preview');
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
            iconPreview.textContent = this.value || '🏢';
        });
    }
});
</script>
@endpush
