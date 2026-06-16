@extends('admin.layouts.master')

@section('page-title')
إنشاء مقال بالذكاء الاصطناعي
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
                ['label' => 'مقال بالذكاء الاصطناعي'],
            ],
            'title' => 'إنشاء مقال بالذكاء الاصطناعي',
            'subtitle' => 'إنشاء مقال متكامل مع جميع حقول SEO',
            'actions' => '<a href="' . route('admin.blog.posts.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للقائمة</a>',
        ])

        <form action="{{ route('admin.blog.ai-posts.store') }}" method="POST" enctype="multipart/form-data" id="aiBlogPostForm">
            @csrf

            <div class="row g-4">
                <!-- Main Column -->
                <div class="col-lg-8">

                    <!-- AI Generation -->
                    <div class="card custom-card form-card ai-panel mb-4">
                        <div class="card-header d-flex align-items-center gap-2">
                            <span class="ai-panel__icon"><i class="ri-robot-2-line"></i></span>
                            <div>
                                <h6 class="mb-0 fw-bold fs-15">إعدادات التوليد بالذكاء الاصطناعي</h6>
                                <small class="text-muted">أدخل الموضوع واختر الإعدادات ثم اضغط توليد</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="topic">الموضوع أو الكلمة المفتاحية</label>
                                <input type="text" id="topic" class="form-control form-input-enhanced"
                                       placeholder="مثال: الذكاء الاصطناعي في التعليم">
                                <small class="text-muted fs-12">أدخل الموضوع الذي تريد إنشاء مقال عنه</small>
                            </div>

                            <div class="ai-provider-hint mb-3">
                                <i class="ri-information-line me-1"></i>
                                المزود والموديل من
                                <a href="{{ route('admin.ai.settings.index') }}">إعدادات الذكاء الاصطناعي</a>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" for="content_length">طول المحتوى</label>
                                    <select id="content_length" class="form-select form-input-enhanced">
                                        <option value="short">قصير (500-800 كلمة)</option>
                                        <option value="medium" selected>متوسط (1000-1500 كلمة)</option>
                                        <option value="long">طويل (2000-3000 كلمة)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" for="tone">الأسلوب</label>
                                    <select id="tone" class="form-select form-input-enhanced">
                                        <option value="professional" selected>احترافي</option>
                                        <option value="friendly">ودود</option>
                                        <option value="technical">تقني</option>
                                        <option value="casual">عادي</option>
                                        <option value="formal">رسمي</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold" for="language">اللغة</label>
                                    <select id="language" class="form-select form-input-enhanced">
                                        <option value="ar" selected>العربية</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2">خيارات SEO</label>
                                <div class="seo-options-panel">
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" id="generate_seo" value="1" checked>
                                        <label class="form-check-label" for="generate_seo">
                                            توليد حقول SEO الأساسية (Meta Title, Description, Keywords)
                                        </label>
                                    </div>
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" id="generate_og" value="1" checked>
                                        <label class="form-check-label" for="generate_og">توليد Open Graph Tags</label>
                                    </div>
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" id="generate_twitter" value="1" checked>
                                        <label class="form-check-label" for="generate_twitter">توليد Twitter Card Tags</label>
                                    </div>
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" id="generate_schema" value="1" checked>
                                        <label class="form-check-label" for="generate_schema">توليد Schema.org Markup</label>
                                    </div>
                                    <div class="seo-option-item">
                                        <input class="form-check-input mt-1" type="checkbox" id="generate_keyword_synonyms" value="1" checked>
                                        <label class="form-check-label" for="generate_keyword_synonyms">توليد مرادفات الكلمة المفتاحية</label>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary ai-generate-btn w-100" id="generateBtn">
                                <i class="ri-magic-line me-2"></i>
                                <span class="btn-text">توليد المقال</span>
                                <span class="spinner-border spinner-border-sm loading-spinner ms-2" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="card custom-card form-card preview-success-card mb-4" id="previewCard" style="display: none;">
                        <div class="card-header">
                            <h6 class="mb-0 fw-bold fs-15">
                                <i class="ri-eye-line me-1 text-success"></i> معاينة المحتوى المولد
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="preview-success-banner">
                                <i class="ri-checkbox-circle-line"></i>
                                <span>تم توليد المحتوى بنجاح! يمكنك مراجعته وتعديله في الحقول أدناه قبل الحفظ.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15">
                                <i class="ri-file-text-line me-1 text-primary"></i> المعلومات الأساسية
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">عنوان المقال <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control form-input-enhanced @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}" required placeholder="عنوان المقال">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">الرابط (Slug) <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="slug" class="form-control form-input-enhanced @error('slug') is-invalid @enderror"
                                       value="{{ old('slug') }}" required dir="ltr" placeholder="article-slug">
                                <small class="text-muted fs-12">
                                    <i class="ri-link me-1"></i> رابط المقال في الموقع — يُولَّد تلقائياً من العنوان
                                </small>
                                @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">المقتطف</label>
                                <textarea name="excerpt" id="excerpt" rows="3" class="form-control form-input-enhanced @error('excerpt') is-invalid @enderror"
                                          placeholder="نبذة مختصرة عن المقال">{{ old('excerpt') }}</textarea>
                                @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-semibold">المحتوى <span class="text-danger">*</span></label>
                                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="15">{{ old('content') }}</textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SEO Accordion -->
                    <div class="accordion form-accordion" id="seoAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#seoCollapse" aria-expanded="true">
                                    <i class="ri-search-line me-2 text-primary"></i> إعدادات SEO
                                </button>
                            </h2>
                            <div id="seoCollapse" class="accordion-collapse collapse show" data-bs-parent="#seoAccordion">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">عنوان SEO (Meta Title)</label>
                                        <input type="text" name="meta_title" id="meta_title" class="form-control form-input-enhanced" maxlength="255">
                                        <small class="text-muted fs-12">50-60 حرف</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">وصف SEO (Meta Description)</label>
                                        <textarea name="meta_description" id="meta_description" rows="2" class="form-control form-input-enhanced"></textarea>
                                        <small class="text-muted fs-12">150-160 حرف</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">الكلمات المفتاحية</label>
                                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control form-input-enhanced">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">الكلمة المفتاحية الرئيسية</label>
                                        <input type="text" name="focus_keyword" id="focus_keyword" class="form-control form-input-enhanced">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">مرادفات الكلمة المفتاحية</label>
                                        <input type="text" name="focus_keyword_synonyms" id="focus_keyword_synonyms" class="form-control form-input-enhanced">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-semibold">Canonical URL</label>
                                        <input type="url" name="canonical_url" id="canonical_url" class="form-control form-input-enhanced" dir="ltr">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ogCollapse">
                                    <i class="ri-facebook-circle-line me-2 text-primary"></i> Open Graph
                                </button>
                            </h2>
                            <div id="ogCollapse" class="accordion-collapse collapse" data-bs-parent="#seoAccordion">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">OG Title</label>
                                        <input type="text" name="og_title" id="og_title" class="form-control form-input-enhanced" maxlength="255">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">OG Description</label>
                                        <textarea name="og_description" id="og_description" rows="2" class="form-control form-input-enhanced"></textarea>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">OG Type</label>
                                            <select name="og_type" id="og_type" class="form-select form-input-enhanced">
                                                <option value="article" selected>Article</option>
                                                <option value="website">Website</option>
                                                <option value="blog">Blog</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">OG Locale</label>
                                            <input type="text" name="og_locale" id="og_locale" class="form-control form-input-enhanced" value="ar_SA">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#twitterCollapse">
                                    <i class="ri-twitter-x-line me-2 text-primary"></i> Twitter Card
                                </button>
                            </h2>
                            <div id="twitterCollapse" class="accordion-collapse collapse" data-bs-parent="#seoAccordion">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Twitter Card Type</label>
                                        <select name="twitter_card" id="twitter_card" class="form-select form-input-enhanced">
                                            <option value="summary">Summary</option>
                                            <option value="summary_large_image" selected>Summary Large Image</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Twitter Title</label>
                                        <input type="text" name="twitter_title" id="twitter_title" class="form-control form-input-enhanced" maxlength="255">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Twitter Description</label>
                                        <textarea name="twitter_description" id="twitter_description" rows="2" class="form-control form-input-enhanced"></textarea>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-semibold">Twitter Creator</label>
                                        <input type="text" name="twitter_creator" id="twitter_creator" class="form-control form-input-enhanced">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#schemaCollapse">
                                    <i class="ri-code-s-slash-line me-2 text-primary"></i> Schema.org
                                </button>
                            </h2>
                            <div id="schemaCollapse" class="accordion-collapse collapse" data-bs-parent="#seoAccordion">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Schema Type</label>
                                        <input type="text" name="schema_type" id="schema_type" class="form-control form-input-enhanced" value="Article">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Schema Headline</label>
                                        <input type="text" name="schema_headline" id="schema_headline" class="form-control form-input-enhanced" maxlength="255">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-semibold">Schema Description</label>
                                        <textarea name="schema_description" id="schema_description" rows="2" class="form-control form-input-enhanced"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
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
                                    <select name="status" class="form-select form-input-enhanced" required>
                                        <option value="draft" selected>مسودة</option>
                                        <option value="published">منشور</option>
                                        <option value="scheduled">مجدول</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">تاريخ النشر</label>
                                    <input type="datetime-local" name="published_at" class="form-control form-input-enhanced">
                                    <small class="text-muted fs-12">اتركه فارغاً للنشر الفوري</small>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured">
                                        <label class="form-check-label" for="is_featured">مقال مميز</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="allow_comments" value="1" id="allow_comments" checked>
                                        <label class="form-check-label" for="allow_comments">السماح بالتعليقات</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_indexable" value="1" id="is_indexable" checked>
                                        <label class="form-check-label" for="is_indexable">قابل للفهرسة (Index)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_followable" value="1" id="is_followable" checked>
                                        <label class="form-check-label" for="is_followable">قابل للمتابعة (Follow)</label>
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
                                <select name="category_id" class="form-select form-input-enhanced" required>
                                    <option value="">اختر التصنيف</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="card custom-card form-card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold fs-15">
                                    <i class="ri-price-tag-3-line me-1 text-primary"></i> الوسوم
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="tags-scroll">
                                    @foreach($tags as $tag)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tags[]"
                                               value="{{ $tag->id }}" id="tag{{ $tag->id }}">
                                        <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->name }}</label>
                                    </div>
                                    @endforeach
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
                                <div class="mb-3">
                                    <input type="file" name="featured_image" class="form-control form-input-enhanced" accept="image/*" id="featuredImage">
                                </div>
                                <div id="imagePreview" class="image-upload-preview mb-3" style="display: none;">
                                    <img src="" alt="Preview">
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-semibold">نص بديل للصورة (Alt Text)</label>
                                    <input type="text" name="featured_image_alt" class="form-control form-input-enhanced">
                                </div>
                            </div>
                        </div>

                        <div class="card custom-card form-card sidebar-submit-card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="ri-save-line me-2"></i> حفظ المقال
                                </button>
                                <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-light border w-100">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
<script>
function initTinyMCE() {
    if (typeof tinymce === 'undefined') {
        setTimeout(initTinyMCE, 100);
        return;
    }

    tinymce.init({
        selector: '#content',
        height: 600,
        directionality: 'rtl',
        language: 'ar',
        language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@latest/langs6/ar.js',
        promotion: false,
        branding: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code codesample fullscreen insertdatetime media table help wordcount emoticons directionality',
        toolbar: 'undo redo | blocks | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | codesample code | fullscreen | help',
        menubar: 'file edit view insert format tools table help',
        content_style: 'body { font-family: "Segoe UI", Tahoma, Arial, sans-serif; font-size: 14px; direction: rtl; }',
        elementpath: true,
        resize: true,
        paste_data_images: true,
        relative_urls: false,
        remove_script_host: false,
        image_advtab: true,
        codesample_global_prismjs: true,
    }).catch(function(error) {
        console.error('TinyMCE initialization error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initTinyMCE, 200);

    document.getElementById('generateBtn').addEventListener('click', function() {
        const topic = document.getElementById('topic').value.trim();
        if (!topic) {
            Swal.fire({ icon: 'error', title: 'خطأ', text: 'يرجى إدخال الموضوع أو الكلمة المفتاحية' });
            return;
        }

        const btn = this;
        const btnText = btn.querySelector('.btn-text');
        const spinner = btn.querySelector('.loading-spinner');

        btn.disabled = true;
        btnText.textContent = 'جاري التوليد...';
        spinner.classList.add('active');

        const formData = {
            topic: topic,
            content_length: document.getElementById('content_length').value,
            tone: document.getElementById('tone').value,
            language: document.getElementById('language').value,
            category_id: document.querySelector('select[name="category_id"]').value,
            generate_seo: document.getElementById('generate_seo').checked,
            generate_og: document.getElementById('generate_og').checked,
            generate_twitter: document.getElementById('generate_twitter').checked,
            generate_schema: document.getElementById('generate_schema').checked,
            generate_keyword_synonyms: document.getElementById('generate_keyword_synonyms').checked,
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("admin.blog.ai-posts.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fillFormFields(data.data);
                document.getElementById('previewCard').style.display = 'block';
                document.getElementById('previewCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
                Swal.fire({
                    icon: 'success',
                    title: 'تم التوليد بنجاح!',
                    text: 'تم توليد المقال وجميع حقول SEO. راجع المحتوى قبل الحفظ.',
                    timer: 3000
                });
            } else {
                Swal.fire({ icon: 'error', title: 'خطأ', text: data.message || 'حدث خطأ أثناء توليد المقال' });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'خطأ', text: 'حدث خطأ أثناء الاتصال بالخادم' });
        })
        .finally(() => {
            btn.disabled = false;
            btnText.textContent = 'توليد المقال';
            spinner.classList.remove('active');
        });
    });

    function fillFormFields(data) {
        if (data.title) document.getElementById('title').value = data.title;
        if (data.slug) document.getElementById('slug').value = data.slug;
        if (data.excerpt) document.getElementById('excerpt').value = data.excerpt;
        if (data.content) {
            const editor = tinymce.get('content');
            if (editor) {
                editor.setContent(data.content);
            } else {
                setTimeout(function() {
                    const ed = tinymce.get('content');
                    if (ed) ed.setContent(data.content);
                }, 500);
            }
        }
        if (data.meta_title) document.getElementById('meta_title').value = data.meta_title;
        if (data.meta_description) document.getElementById('meta_description').value = data.meta_description;
        if (data.meta_keywords) document.getElementById('meta_keywords').value = data.meta_keywords;
        if (data.focus_keyword) document.getElementById('focus_keyword').value = data.focus_keyword;
        if (data.focus_keyword_synonyms) document.getElementById('focus_keyword_synonyms').value = data.focus_keyword_synonyms;
        if (data.canonical_url) document.getElementById('canonical_url').value = data.canonical_url;
        if (data.og_title) document.getElementById('og_title').value = data.og_title;
        if (data.og_description) document.getElementById('og_description').value = data.og_description;
        if (data.og_type) document.getElementById('og_type').value = data.og_type;
        if (data.og_locale) document.getElementById('og_locale').value = data.og_locale;
        if (data.twitter_card) document.getElementById('twitter_card').value = data.twitter_card;
        if (data.twitter_title) document.getElementById('twitter_title').value = data.twitter_title;
        if (data.twitter_description) document.getElementById('twitter_description').value = data.twitter_description;
        if (data.twitter_creator) document.getElementById('twitter_creator').value = data.twitter_creator;
        if (data.schema_type) document.getElementById('schema_type').value = data.schema_type;
        if (data.schema_headline) document.getElementById('schema_headline').value = data.schema_headline;
        if (data.schema_description) document.getElementById('schema_description').value = data.schema_description;
    }

    document.getElementById('title').addEventListener('input', function() {
        if (!document.getElementById('slug').value) {
            const slug = this.value.toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\u0600-\u06FFa-z0-9-]/g, '')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            document.getElementById('slug').value = slug;
        }
    });

    document.getElementById('featuredImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('imagePreview');
                preview.querySelector('img').src = ev.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
