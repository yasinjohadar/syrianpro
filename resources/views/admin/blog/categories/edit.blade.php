@extends('admin.layouts.master')

@section('page-title')
تعديل تصنيف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'المدونة'],
                ['label' => 'التصنيفات', 'url' => route('admin.blog.categories.index')],
                ['label' => 'تعديل'],
            ],
            'title' => 'تعديل: ' . $category->name,
            'subtitle' => 'تحديث بيانات التصنيف وإعدادات SEO',
            'actions' => '<a href="' . route('admin.blog.categories.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للقائمة</a>',
        ])

        <form action="{{ route('admin.blog.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-folder-line me-1 text-primary"></i> معلومات التصنيف
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="categoryName"
                                       class="form-control form-input-enhanced @error('name') is-invalid @enderror"
                                       value="{{ old('name', $category->name) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">الوصف</label>
                                <textarea name="description" rows="3" class="form-control form-input-enhanced">{{ old('description', $category->description) }}</textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الأيقونة</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="icon-preview-box" id="iconPreview">
                                            <i class="{{ old('icon', $category->icon ?: 'ri-folder-line') }}"></i>
                                        </span>
                                        <input type="text" name="icon" id="categoryIcon"
                                               class="form-control form-input-enhanced"
                                               value="{{ old('icon', $category->icon) }}" placeholder="ri-folder-line" dir="ltr">
                                    </div>
                                    <small class="text-muted fs-12">Remix Icon أو Font Awesome</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">اللون</label>
                                    <div class="color-input-wrap">
                                        <input type="color" name="color" id="categoryColor"
                                               value="{{ old('color', $category->color ?? '#6366f1') }}">
                                        <input type="text" id="categoryColorHex" class="form-control form-input-enhanced"
                                               value="{{ old('color', $category->color ?? '#6366f1') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">التصنيف الأب</label>
                                    <select name="parent_id" class="form-select form-input-enhanced">
                                        <option value="">بدون (تصنيف رئيسي)</option>
                                        @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">الترتيب</label>
                                    <input type="number" name="order" class="form-control form-input-enhanced"
                                           value="{{ old('order', $category->order) }}" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-search-line me-1 text-primary"></i> إعدادات SEO
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">عنوان SEO</label>
                                <input type="text" name="meta_title" class="form-control form-input-enhanced"
                                       value="{{ old('meta_title', $category->meta_title) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">وصف SEO</label>
                                <textarea name="meta_description" rows="2" class="form-control form-input-enhanced">{{ old('meta_description', $category->meta_description) }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label fw-semibold">الكلمات المفتاحية</label>
                                <input type="text" name="meta_keywords" class="form-control form-input-enhanced"
                                       value="{{ old('meta_keywords', $category->meta_keywords) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-sticky">
                        <div class="card custom-card form-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold fs-15">
                                    <i class="ri-settings-3-line me-1 text-primary"></i> الإعدادات
                                </h6>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="is_active" value="0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                           id="is_active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="is_active">تصنيف نشط</label>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card form-card sidebar-submit-card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="ri-save-line me-2"></i> تحديث التصنيف
                                </button>
                                <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-light border w-100">
                                    <i class="ri-close-line me-2"></i> إلغاء
                                </a>
                            </div>
                        </div>
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
    var colorInput = document.getElementById('categoryColor');
    var colorHex = document.getElementById('categoryColorHex');
    var iconInput = document.getElementById('categoryIcon');
    var iconPreview = document.getElementById('iconPreview');

    function syncColor() {
        if (colorInput && colorHex) colorHex.value = colorInput.value;
        if (iconPreview && colorInput) iconPreview.style.background = colorInput.value;
    }

    function syncIcon() {
        if (!iconPreview || !iconInput) return;
        var icon = iconInput.value.trim() || 'ri-folder-line';
        iconPreview.innerHTML = '<i class="' + icon + '"></i>';
    }

    if (colorInput) colorInput.addEventListener('input', syncColor);
    if (iconInput) iconInput.addEventListener('input', syncIcon);
    syncColor();
    syncIcon();
});
</script>
@endpush
