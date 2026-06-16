@extends('admin.layouts.master')

@section('page-title')
    {{ isset($techSpecialty) ? 'تعديل تخصص' : 'إضافة تخصص' }}
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'التخصصات التقنية', 'url' => route('admin.tech-specialties.index')],
                ['label' => isset($techSpecialty) ? 'تعديل' : 'إضافة'],
            ],
            'title' => isset($techSpecialty) ? 'تعديل التخصص' : 'إضافة تخصص جديد',
            'subtitle' => 'إدارة الاسم والأيقونة/الصورة وعدد الوظائف',
            'actions' => '<a href="' . route('admin.tech-specialties.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST"
              action="{{ isset($techSpecialty) ? route('admin.tech-specialties.update', $techSpecialty) : route('admin.tech-specialties.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($techSpecialty))
                @method('PUT')
            @endif

            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-image-line me-1 text-primary"></i> الصورة / الأيقونة</h6>
                        </div>
                        <div class="card-body">
                            <div class="user-avatar-upload-wrap">
                                @php
                                    $previewIcon = old('icon', $techSpecialty->icon ?? '💻');
                                    $previewImage = isset($techSpecialty) && $techSpecialty->image ? $techSpecialty->iconUrl() : null;
                                @endphp
                                <div class="user-avatar-preview-wrap" id="specialty-preview-wrap">
                                    <img id="specialty-image-preview" class="user-avatar-preview {{ $previewImage ? '' : 'd-none' }}"
                                         src="{{ $previewImage }}" alt="">
                                    <span id="specialty-icon-preview" class="user-avatar-initial {{ $previewImage ? 'd-none' : '' }}" style="font-size:2rem;">
                                        {{ $previewIcon }}
                                    </span>
                                </div>
                                <label for="specialty-image-input" class="user-avatar-upload-btn">
                                    <i class="ri-camera-line"></i> اختر صورة
                                </label>
                                <input type="file" name="image" id="specialty-image-input" accept="image/*">
                            </div>
                            @error('image')
                                <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                            @enderror

                            @if(isset($techSpecialty) && $techSpecialty->image)
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                    <label class="form-check-label fs-12" for="remove_image">حذف الصورة الحالية</label>
                                </div>
                            @endif

                            <div class="mt-3">
                                <label class="form-label fw-semibold fs-13">أيقونة (emoji أو نص)</label>
                                <input type="text" name="icon" id="specialty-icon-input"
                                       class="form-control form-input-enhanced @error('icon') is-invalid @enderror"
                                       value="{{ old('icon', $techSpecialty->icon ?? '') }}"
                                       placeholder="⚛️">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <p class="text-muted fs-12 mb-0 mt-2">تُستخدم عند عدم رفع صورة.</p>
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
                                           {{ old('is_active', $techSpecialty->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">تخصص نشط</label>
                                </div>
                            </div>
                            <div class="account-switch-panel">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="show_on_home" value="1" id="show_on_home"
                                           {{ old('show_on_home', $techSpecialty->show_on_home ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="show_on_home">يظهر في الرئيسية</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-lg-1">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-code-box-line me-1 text-primary"></i> معلومات التخصص</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                           class="form-control form-input-enhanced @error('name') is-invalid @enderror"
                                           value="{{ old('name', $techSpecialty->name ?? '') }}"
                                           placeholder="Frontend" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">الترتيب</label>
                                    <input type="number" name="order" min="0"
                                           class="form-control form-input-enhanced @error('order') is-invalid @enderror"
                                           value="{{ old('order', $techSpecialty->order ?? 0) }}">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">عدد الوظائف</label>
                                    <input type="number" name="jobs_count" min="0"
                                           class="form-control form-input-enhanced @error('jobs_count') is-invalid @enderror"
                                           value="{{ old('jobs_count', $techSpecialty->jobs_count ?? 0) }}">
                                    @error('jobs_count')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if(isset($techSpecialty))
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Slug</label>
                                        <input type="text" class="form-control form-input-enhanced" value="{{ $techSpecialty->slug }}" dir="ltr" readonly disabled>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card form-card">
                <div class="card-body py-3">
                    <div class="form-actions border-0 pt-0 mt-0">
                        <a href="{{ route('admin.tech-specialties.index') }}" class="btn btn-light border px-4">
                            <i class="ri-close-line me-1"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary px-4 btn-wave">
                            <i class="ri-save-line me-1"></i> {{ isset($techSpecialty) ? 'حفظ التعديلات' : 'حفظ التخصص' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var imageInput = document.getElementById('specialty-image-input');
    var iconInput = document.getElementById('specialty-icon-input');
    var imagePreview = document.getElementById('specialty-image-preview');
    var iconPreview = document.getElementById('specialty-icon-preview');

    if (imageInput) {
        imageInput.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('d-none');
                iconPreview.classList.add('d-none');
            };
            reader.readAsDataURL(this.files[0]);
        });
    }

    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function () {
            if (imageInput && imageInput.files && imageInput.files.length) return;
            iconPreview.textContent = this.value.trim() || '💻';
            iconPreview.classList.remove('d-none');
            imagePreview.classList.add('d-none');
        });
    }
});
</script>
@endpush
