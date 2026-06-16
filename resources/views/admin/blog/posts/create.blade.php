@extends('admin.layouts.master')

@section('page-title')
    إضافة مقال جديد
@stop

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'المدونة'],
                ['label' => 'المقالات', 'url' => route('admin.blog.posts.index')],
                ['label' => 'إضافة مقال'],
            ],
            'title' => 'إضافة مقال جديد',
            'subtitle' => 'إنشاء مقال جديد في المدونة مع إعدادات SEO والنشر',
            'actions' => '<a href="' . route('admin.blog.posts.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للقائمة</a>',
        ])

        <form action="{{ route('admin.blog.posts.store') }}" method="POST" enctype="multipart/form-data" data-blog-post-form>
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-file-text-line me-1 text-primary"></i> المعلومات الأساسية
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="title">عنوان المقال <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                       class="form-control form-input-enhanced @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}" required placeholder="أدخل عنوان المقال">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="slug">الرابط (Slug) <span class="text-danger">*</span></label>
                                <div class="input-group slug-input-group">
                                    <input type="text" name="slug" id="slug"
                                           class="form-control form-input-enhanced @error('slug') is-invalid @enderror"
                                           value="{{ old('slug') }}" required dir="ltr" placeholder="article-slug">
                                    <button type="button" class="btn btn-light border" id="generateSlug">
                                        <i class="ri-magic-line me-1"></i> توليد تلقائي
                                    </button>
                                </div>
                                <small class="text-muted fs-12 d-block mt-1">
                                    <i class="ri-link me-1"></i> يُولَّد تلقائياً من العنوان — يدعم العربية والإنجليزية
                                </small>
                                @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="excerpt">المقتطف</label>
                                <textarea name="excerpt" id="excerpt" rows="3"
                                          class="form-control form-input-enhanced @error('excerpt') is-invalid @enderror"
                                          placeholder="نبذة مختصرة تظهر في قوائم المقالات">{{ old('excerpt') }}</textarea>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold" for="content">المحتوى <span class="text-danger">*</span></label>
                                <textarea name="content" id="content"
                                          class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="accordion form-accordion" id="seoAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#seoCollapse" aria-expanded="true">
                                    <i class="ri-search-line me-2 text-primary"></i> إعدادات SEO
                                </button>
                            </h2>
                            <div id="seoCollapse" class="accordion-collapse collapse show" data-bs-parent="#seoAccordion">
                                <div class="accordion-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">عنوان SEO (Meta Title)</label>
                                            <input type="text" name="meta_title"
                                                   class="form-control form-input-enhanced @error('meta_title') is-invalid @enderror"
                                                   value="{{ old('meta_title') }}" maxlength="255"
                                                   placeholder="يُستخدم عنوان المقال إن تُرك فارغاً">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">وصف SEO (Meta Description)</label>
                                            <textarea name="meta_description" rows="2"
                                                      class="form-control form-input-enhanced @error('meta_description') is-invalid @enderror"
                                                      placeholder="150–160 حرف">{{ old('meta_description') }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">الكلمات المفتاحية</label>
                                            <input type="text" name="meta_keywords"
                                                   class="form-control form-input-enhanced @error('meta_keywords') is-invalid @enderror"
                                                   value="{{ old('meta_keywords') }}" placeholder="افصل بفاصلة">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">الكلمة المفتاحية الرئيسية</label>
                                            <input type="text" name="focus_keyword"
                                                   class="form-control form-input-enhanced @error('focus_keyword') is-invalid @enderror"
                                                   value="{{ old('focus_keyword') }}">
                                            @error('focus_keyword')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-sticky">
                        <div class="card custom-card form-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold fs-15">
                                    <i class="ri-send-plane-line me-1 text-primary"></i> إعدادات النشر
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">الحالة <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select form-input-enhanced @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>مسودة</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>منشور</option>
                                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">تاريخ النشر</label>
                                    <input type="datetime-local" name="published_at"
                                           class="form-control form-input-enhanced @error('published_at') is-invalid @enderror"
                                           value="{{ old('published_at') }}">
                                    <small class="text-muted fs-12">اتركه فارغاً للنشر الفوري</small>
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="seo-options-panel mb-0">
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" name="is_featured" value="1"
                                               id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            <i class="ri-star-line me-1 text-warning"></i> مقال مميز
                                        </label>
                                    </div>
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" name="allow_comments" value="1"
                                               id="allow_comments" {{ old('allow_comments', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_comments">
                                            <i class="ri-chat-3-line me-1"></i> السماح بالتعليقات
                                        </label>
                                    </div>
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" name="is_indexable" value="1"
                                               id="is_indexable" {{ old('is_indexable', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_indexable">
                                            <i class="ri-search-eye-line me-1"></i> قابل للفهرسة (Index)
                                        </label>
                                    </div>
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" name="is_followable" value="1"
                                               id="is_followable" {{ old('is_followable', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_followable">
                                            <i class="ri-links-line me-1"></i> قابل للمتابعة (Follow)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card form-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold fs-15">
                                    <i class="ri-folder-line me-1 text-primary"></i> التصنيف
                                </h6>
                            </div>
                            <div class="card-body">
                                <select name="category_id" class="form-select form-input-enhanced @error('category_id') is-invalid @enderror" required>
                                    <option value="">اختر التصنيف</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="card custom-card form-card mb-4">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-semibold fs-15">
                                    <i class="ri-price-tag-3-line me-1 text-primary"></i> الوسوم
                                </h6>
                                <span class="badge-soft badge-soft-primary fs-11">{{ $tags->count() }}</span>
                            </div>
                            <div class="card-body">
                                <div class="tags-scroll-panel">
                                    <div class="role-check-grid">
                                        @foreach($tags as $tag)
                                            <label class="role-check-chip">
                                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                                       {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                                <span>{{ $tag->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card form-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold fs-15">
                                    <i class="ri-image-line me-1 text-primary"></i> الصورة البارزة
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="post-featured-upload-wrap">
                                    <label for="featuredImage" id="featuredImagePlaceholder" class="post-featured-placeholder mb-0">
                                        <i class="ri-image-add-line"></i>
                                        <span id="featuredImageHint">اضغط لاختيار صورة</span>
                                    </label>
                                    <div id="imagePreview" class="w-100 d-none">
                                        <img src="" alt="معاينة" class="post-featured-preview">
                                    </div>
                                    <label for="featuredImage" class="user-avatar-upload-btn mt-3" id="featuredImageBtn">
                                        <i class="ri-upload-2-line"></i> <span id="featuredImageBtnText">اختر صورة</span>
                                    </label>
                                    <input type="file" name="featured_image" id="featuredImage"
                                           class="@error('featured_image') is-invalid @enderror" accept="image/*">
                                    @error('featured_image')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-3 mb-0">
                                    <label class="form-label fw-semibold">نص بديل للصورة (Alt)</label>
                                    <input type="text" name="featured_image_alt"
                                           class="form-control form-input-enhanced @error('featured_image_alt') is-invalid @enderror"
                                           value="{{ old('featured_image_alt') }}" placeholder="وصف الصورة لمحركات البحث">
                                    @error('featured_image_alt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card form-card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2 py-2 fw-semibold">
                                    <i class="ri-save-line me-1"></i> حفظ المقال
                                </button>
                                <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-light border w-100 py-2">
                                    <i class="ri-close-circle-line me-1"></i> إلغاء
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@stop

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
<script src="{{ asset('assets/js/admin-blog-post-form.js') }}"></script>
@endpush
