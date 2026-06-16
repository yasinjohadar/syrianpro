/**
 * Admin Blog Post Form — slug generation & featured image preview.
 */
(function (window) {
    'use strict';

    function generateSlug(text) {
        if (!text) return '';
        var slug = text.toString().trim();
        slug = slug
            .replace(/\s+/g, '-')
            .replace(/[^\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFFa-zA-Z0-9-]/g, '')
            .replace(/-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
        return slug || 'post-' + Date.now();
    }

    function initSlugFields(root) {
        var scope = root || document;
        var titleInput = scope.querySelector('#title');
        var slugInput = scope.querySelector('#slug');
        var generateBtn = scope.querySelector('#generateSlug');

        if (!titleInput || !slugInput) return;

        titleInput.addEventListener('input', function () {
            if (slugInput.dataset.manualEdit !== 'true') {
                slugInput.value = generateSlug(this.value);
            }
        });

        slugInput.addEventListener('input', function () {
            this.dataset.manualEdit = 'true';
        });

        if (generateBtn) {
            generateBtn.addEventListener('click', function () {
                slugInput.value = generateSlug(titleInput.value);
                slugInput.dataset.manualEdit = 'false';
            });
        }
    }

    function initFeaturedImagePreview(root) {
        var scope = root || document;
        var input = scope.querySelector('#featuredImage');
        var previewWrap = scope.querySelector('#imagePreview');
        var previewImg = previewWrap ? previewWrap.querySelector('img') : null;
        var placeholder = scope.querySelector('#featuredImagePlaceholder');
        var btnText = scope.querySelector('#featuredImageBtnText');
        var hint = scope.querySelector('#featuredImageHint');

        if (!input || !previewWrap || !previewImg) return;

        input.addEventListener('change', function () {
            var file = this.files && this.files[0];
            if (!file) return;

            var reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewWrap.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
                if (btnText) btnText.textContent = file.name;
                if (hint) hint.textContent = file.name;
            };
            reader.readAsDataURL(file);
        });
    }

    function initTinyMCE(selector) {
        if (typeof tinymce === 'undefined') {
            setTimeout(function () { initTinyMCE(selector); }, 100);
            return;
        }

        var el = document.querySelector(selector || '#content');
        if (!el || el.dataset.tinymceInit) return;
        el.dataset.tinymceInit = '1';

        tinymce.init({
            selector: selector || '#content',
            height: 560,
            directionality: 'rtl',
            language: 'ar',
            language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@latest/langs6/ar.js',
            promotion: false,
            branding: false,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code codesample fullscreen insertdatetime media table help wordcount emoticons directionality',
            toolbar: 'undo redo | blocks | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | codesample code | fullscreen | help',
            menubar: 'file edit view insert format tools table help',
            content_style: 'body { font-family: "Segoe UI", Tahoma, Arial, sans-serif; font-size: 14px; direction: rtl; }',
            relative_urls: false,
            remove_script_host: false,
            image_advtab: true,
            paste_data_images: true,
            codesample_global_prismjs: true,
            codesample_languages: [
                { text: 'HTML/XML', value: 'markup' },
                { text: 'JavaScript', value: 'javascript' },
                { text: 'CSS', value: 'css' },
                { text: 'PHP', value: 'php' },
                { text: 'Python', value: 'python' },
                { text: 'SQL', value: 'sql' },
                { text: 'JSON', value: 'json' }
            ]
        }).catch(function (err) {
            console.error('TinyMCE init error:', err);
        });
    }

    window.AdminBlogPostForm = {
        init: function (options) {
            options = options || {};
            var root = options.root ? document.querySelector(options.root) : document;
            initSlugFields(root);
            initFeaturedImagePreview(root);
            if (options.tinymce !== false) {
                setTimeout(function () {
                    initTinyMCE(options.contentSelector || '#content');
                }, options.tinymceDelay || 200);
            }
        },
        generateSlug: generateSlug
    };

    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('[data-blog-post-form]')) {
            window.AdminBlogPostForm.init();
        }
    });
})(window);
