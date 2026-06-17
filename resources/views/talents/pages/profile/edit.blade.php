@extends('talents.layouts.master')

@section('page-title')
تعديل الملف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التقني', 'url' => route('talent.dashboard')],
                ['label' => 'تعديل الملف'],
            ],
            'title' => 'ملف التقني',
            'subtitle' => 'حدّث ملفك كما يظهر للشركات على المنصة',
            'actions' => $talent && $talent->slug
                ? '<a href="' . route('talents.show', $talent) . '" class="btn btn-primary-light btn-wave" target="_blank"><i class="ri-external-link-line me-1"></i> الصفحة العامة</a>'
                : '',
        ])

        <form method="POST" action="{{ route('talent.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-line me-1 text-primary"></i> الصورة الشخصية</h6>
                        </div>
                        <div class="card-body">
                            @php
                                $previewAvatar = old('avatar', optional($talent)->avatar ?: mb_substr($user->name, 0, 1));
                                $previewImage = $talent?->avatar_image ? $talent->avatarUrl() : null;
                            @endphp
                            <div class="user-avatar-upload-wrap">
                                <div class="user-avatar-preview-wrap">
                                    <img id="talent-avatar-preview" class="user-avatar-preview {{ $previewImage ? '' : 'd-none' }}"
                                         src="{{ $previewImage }}" alt="">
                                    <span id="talent-icon-preview" class="user-avatar-initial {{ $previewImage ? 'd-none' : '' }}" style="font-size:2rem;">
                                        {{ $previewAvatar }}
                                    </span>
                                </div>
                                <label for="talent-avatar-input" class="user-avatar-upload-btn">
                                    <i class="ri-camera-line"></i> اختر صورة
                                </label>
                                <input type="file" name="avatar_image" id="talent-avatar-input" accept="image/*">
                            </div>
                            @error('avatar_image')
                                <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                            @enderror
                            @if($talent?->avatar_image)
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="remove_avatar_image" value="1" id="remove_avatar_image">
                                    <label class="form-check-label fs-12" for="remove_avatar_image">حذف الصورة الحالية</label>
                                </div>
                            @endif
                            <div class="mt-3">
                                <label class="form-label fw-semibold fs-13">حرف/أيقونة (emoji)</label>
                                <input type="text" name="avatar" id="talent-icon-input" class="form-control form-input-enhanced"
                                       value="{{ $previewAvatar }}" placeholder="أ">
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-account-circle-line me-1 text-primary"></i> الحساب</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="text-muted fs-12 d-block">البريد المسجّل</span>
                                <span class="fw-semibold">{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-global-line me-1 text-primary"></i> العمل عن بُعد</h6>
                        </div>
                        <div class="card-body">
                            <div class="account-switch-panel">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_remote" value="1" id="is_remote"
                                           {{ old('is_remote', optional($talent)->is_remote ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_remote">متاح للعمل عن بُعد</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-lg-1">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-star-line me-1 text-primary"></i> المعلومات الأساسية</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-input-enhanced @error('name') is-invalid @enderror"
                                           value="{{ old('name', optional($talent)->name ?: $user->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">المسمى الوظيفي <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control form-input-enhanced @error('title') is-invalid @enderror"
                                           value="{{ old('title', optional($talent)->title) }}" placeholder="مطور Full Stack" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">المدينة <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control form-input-enhanced @error('city') is-invalid @enderror"
                                           value="{{ old('city', optional($talent)->city) }}" placeholder="دمشق" required>
                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">التخصص</label>
                                    <select name="tech_specialty_id" class="form-select form-input-enhanced">
                                        <option value="">— اختر التخصص —</option>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty->id }}" @selected(old('tech_specialty_id', optional($talent)->tech_specialty_id) == $specialty->id)>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">التوفر</label>
                                    <input type="text" name="availability" class="form-control form-input-enhanced"
                                           value="{{ old('availability', optional($talent)->availability) }}" placeholder="متاح فوراً">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">الحد الأدنى ($/ساعة)</label>
                                    <input type="number" name="rate_min" min="0" class="form-control form-input-enhanced"
                                           value="{{ old('rate_min', optional($talent)->rate_min) }}" placeholder="20">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">الحد الأعلى ($/ساعة)</label>
                                    <input type="number" name="rate_max" min="0" class="form-control form-input-enhanced"
                                           value="{{ old('rate_max', optional($talent)->rate_max) }}" placeholder="30">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">نبذة عني</label>
                                    <textarea name="bio" rows="4" class="form-control form-input-enhanced">{{ old('bio', optional($talent)->bio) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">المهارات</label>
                                    <input type="text" name="skills_text" class="form-control form-input-enhanced"
                                           value="{{ old('skills_text', $talent ? implode(', ', $talent->skills ?? []) : '') }}"
                                           placeholder="React, TypeScript, Node.js">
                                    <div class="form-text">افصل بين المهارات بفاصلة</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('partials.profile.contact-fields', ['profile' => $talent])

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('talent.dashboard') }}" class="btn btn-light border">إلغاء</a>
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
    var fileInput = document.getElementById('talent-avatar-input');
    var iconInput = document.getElementById('talent-icon-input');
    var imgPreview = document.getElementById('talent-avatar-preview');
    var iconPreview = document.getElementById('talent-icon-preview');
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
            iconPreview.textContent = this.value || 'أ';
        });
    }
});
</script>
@endpush
