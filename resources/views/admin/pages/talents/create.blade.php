@extends('admin.layouts.master')

@section('page-title')
    {{ isset($talent) ? 'تعديل موهبة' : 'إضافة موهبة' }}
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'المواهب', 'url' => route('admin.talents.index')],
                ['label' => isset($talent) ? 'تعديل' : 'إضافة'],
            ],
            'title' => isset($talent) ? 'تعديل الموهبة' : 'إضافة موهبة جديدة',
            'subtitle' => 'إدارة الملف الشخصي والمهارات والخبرة',
            'actions' => '<a href="' . route('admin.talents.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST"
              action="{{ isset($talent) ? route('admin.talents.update', $talent) : route('admin.talents.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if(isset($talent)) @method('PUT') @endif

            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header"><h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-line me-1 text-primary"></i> الصورة / الحرف</h6></div>
                        <div class="card-body">
                            @php
                                $previewAvatar = old('avatar', $talent->avatar ?? mb_substr(old('name', $talent->name ?? ''), 0, 1));
                                $previewImage = isset($talent) && $talent->avatar_image ? $talent->avatarUrl() : null;
                            @endphp
                            <div class="user-avatar-upload-wrap">
                                <div class="user-avatar-preview-wrap">
                                    <img id="talent-image-preview" class="user-avatar-preview {{ $previewImage ? '' : 'd-none' }}"
                                         src="{{ $previewImage }}" alt="">
                                    <span id="talent-avatar-preview" class="user-avatar-initial {{ $previewImage ? 'd-none' : '' }}" style="font-size:2rem;">
                                        {{ $previewAvatar ?: 'أ' }}
                                    </span>
                                </div>
                                <label for="talent-image-input" class="user-avatar-upload-btn"><i class="ri-camera-line"></i> اختر صورة</label>
                                <input type="file" name="avatar_image" id="talent-image-input" accept="image/*">
                            </div>
                            @if(isset($talent) && $talent->avatar_image)
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="remove_avatar_image" value="1" id="remove_avatar_image">
                                    <label class="form-check-label fs-12" for="remove_avatar_image">حذف الصورة</label>
                                </div>
                            @endif
                            <div class="mt-3">
                                <label class="form-label fw-semibold fs-13">حرف/emoji</label>
                                <input type="text" name="avatar" id="talent-avatar-input" class="form-control form-input-enhanced"
                                       value="{{ old('avatar', $talent->avatar ?? '') }}" maxlength="2">
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header"><h6 class="mb-0 fw-semibold fs-15"><i class="ri-settings-3-line me-1 text-primary"></i> الإعدادات</h6></div>
                        <div class="card-body">
                            <div class="account-switch-panel mb-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                           {{ old('is_active', $talent->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">نشط</label>
                                </div>
                            </div>
                            <div class="account-switch-panel mb-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured"
                                           {{ old('is_featured', $talent->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_featured">موهبة مميزة</label>
                                </div>
                            </div>
                            <div class="account-switch-panel mb-3">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="is_verified"
                                           {{ old('is_verified', $talent->is_verified ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_verified">موثّق ✓</label>
                                </div>
                            </div>
                            <div class="account-switch-panel">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_remote" value="1" id="is_remote"
                                           {{ old('is_remote', $talent->is_remote ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_remote">يعمل عن بُعد</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-lg-1">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header"><h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-star-line me-1 text-primary"></i> معلومات أساسية</h6></div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-input-enhanced @error('name') is-invalid @enderror"
                                           value="{{ old('name', $talent->name ?? '') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">المسمى الوظيفي <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control form-input-enhanced @error('title') is-invalid @enderror"
                                           value="{{ old('title', $talent->title ?? '') }}" required>
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">المدينة <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control form-input-enhanced @error('city') is-invalid @enderror"
                                           value="{{ old('city', $talent->city ?? '') }}" required>
                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">التوفر</label>
                                    <input type="text" name="availability" class="form-control form-input-enhanced"
                                           value="{{ old('availability', $talent->availability ?? '') }}" placeholder="متاح فوراً">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">الترتيب</label>
                                    <input type="number" name="order" min="0" class="form-control form-input-enhanced"
                                           value="{{ old('order', $talent->order ?? 0) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">المعدل من ($/ساعة)</label>
                                    <input type="number" name="rate_min" min="0" class="form-control form-input-enhanced"
                                           value="{{ old('rate_min', $talent->rate_min ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">المعدل إلى ($/ساعة)</label>
                                    <input type="number" name="rate_max" min="0" class="form-control form-input-enhanced"
                                           value="{{ old('rate_max', $talent->rate_max ?? '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">التخصص التقني</label>
                                    <select name="tech_specialty_id" class="form-select form-input-enhanced">
                                        <option value="">— بدون —</option>
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty->id }}" @selected(old('tech_specialty_id', $talent->tech_specialty_id ?? '') == $specialty->id)>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">المهارات (مفصولة بفاصلة)</label>
                                    <input type="text" name="skills_text" class="form-control form-input-enhanced"
                                           value="{{ old('skills_text', isset($talent) ? implode(', ', $talent->skills ?? []) : '') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">نبذة</label>
                                    <textarea name="bio" rows="4" class="form-control form-input-enhanced">{{ old('bio', $talent->bio ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header"><h6 class="mb-0 fw-semibold fs-15"><i class="ri-links-line me-1 text-primary"></i> روابط ومحتوى</h6></div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">GitHub</label>
                                    <input type="text" name="link_github" class="form-control form-input-enhanced"
                                           value="{{ old('link_github', isset($talent) ? ($talent->links['github'] ?? '') : '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">LinkedIn</label>
                                    <input type="text" name="link_linkedin" class="form-control form-input-enhanced"
                                           value="{{ old('link_linkedin', isset($talent) ? ($talent->links['linkedin'] ?? '') : '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Portfolio</label>
                                    <input type="text" name="link_portfolio" class="form-control form-input-enhanced"
                                           value="{{ old('link_portfolio', isset($talent) ? ($talent->links['portfolio'] ?? '') : '') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">الخبرة (JSON)</label>
                                    <textarea name="experience_json" rows="4" class="form-control form-input-enhanced" dir="ltr">{{ old('experience_json', isset($talent) && $talent->experience ? json_encode($talent->experience, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">المشاريع (JSON)</label>
                                    <textarea name="projects_json" rows="6" class="form-control form-input-enhanced" dir="ltr">{{ old('projects_json', isset($talent) && $talent->projects ? json_encode($talent->projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.talents.index') }}" class="btn btn-light border">إلغاء</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> {{ isset($talent) ? 'حفظ التعديلات' : 'إنشاء الموهبة' }}
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
    var fileInput = document.getElementById('talent-image-input');
    var avatarInput = document.getElementById('talent-avatar-input');
    var imgPreview = document.getElementById('talent-image-preview');
    var avatarPreview = document.getElementById('talent-avatar-preview');
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                imgPreview.src = URL.createObjectURL(this.files[0]);
                imgPreview.classList.remove('d-none');
                avatarPreview.classList.add('d-none');
            }
        });
    }
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('input', function () {
            avatarPreview.textContent = this.value || 'أ';
        });
    }
});
</script>
@endpush
