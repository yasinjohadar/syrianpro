@extends('admin.layouts.master')

@section('page-title')
    المقالات
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="admin-toast-container" id="adminToastContainer"></div>
            @include('admin.partials.ui.alerts')

            @include('admin.partials.ui.page-header', [
                'breadcrumbs' => [
                    ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                    ['label' => 'المدونة'],
                    ['label' => 'المقالات'],
                ],
                'title' => 'كافة المقالات',
                'subtitle' => 'إدارة مقالات المدونة والنشر والتصنيف',
                'actions' => '<a href="' . route('admin.blog.posts.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة مقال جديد</a>',
            ])

            <div class="row g-3 mb-4">
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'purple', 'icon' => 'ri-article-line',
                    'label' => 'إجمالي المقالات', 'value' => number_format($stats['total']),
                    'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                    'label' => 'مقالات منشورة', 'value' => number_format($stats['published']),
                    'hint' => 'حالة منشور',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'cyan', 'icon' => 'ri-draft-line',
                    'label' => 'مسودات', 'value' => number_format($stats['draft']),
                    'hint' => 'بانتظار النشر',
                ])
                @include('admin.partials.ui.stat-card-gradient', [
                    'variant' => 'orange', 'icon' => 'ri-eye-line',
                    'label' => 'إجمالي المشاهدات', 'value' => number_format($stats['views']),
                    'hint' => 'عبر كل المقالات',
                ])
            </div>

            <div class="filter-panel">
                <div class="filter-panel__title">تصفية المقالات</div>
                <div class="filter-panel__subtitle">ابحث بالعنوان أو فلتر حسب التصنيف والحالة والكاتب</div>
                <x-admin.ajax-filter-form
                    :action="route('admin.blog.posts.index')"
                    target="#postsAjaxTarget"
                    count-target="#postsFilteredCount"
                    :reset-url="route('admin.blog.posts.index')"
                    id="postsFilterForm">
                    <div class="row g-2 g-md-3 align-items-end">
                        <div class="col-lg-3">
                            <label class="form-label fs-12 text-muted mb-1">بحث</label>
                            <div class="search-input-wrap">
                                <i class="ri-search-line"></i>
                                <input type="text" name="search" class="form-control" data-ajax-search
                                       placeholder="ابحث بالعنوان أو المحتوى..."
                                       value="{{ request('search') }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fs-12 text-muted mb-1">التصنيف</label>
                            <select name="category" class="form-select" data-ajax-auto>
                                <option value="">كل التصنيفات</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                            <select name="status" class="form-select" data-ajax-auto>
                                <option value="">كل الحالات</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>منشور</option>
                                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>مجدول</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label fs-12 text-muted mb-1">الكاتب</label>
                            <select name="author" class="form-select" data-ajax-auto>
                                <option value="">كل الكتاب</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill" id="postsSearchBtn">
                                <i class="ri-search-2-line me-1"></i> بحث
                            </button>
                            <button type="button" class="btn btn-light border" data-ajax-reset title="مسح الفلاتر">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </x-admin.ajax-filter-form>
            </div>

            <div class="card custom-card data-table-card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold fs-16">قائمة المقالات</span>
                        <span class="table-count-badge" id="postsFilteredCount">{{ number_format($stats['filtered']) }}</span>
                    </div>
                </div>
                <div class="ajax-filter-target" id="postsAjaxTarget">
                    @include('admin.blog.posts.partials.list')
                </div>
            </div>

        </div>
    </div>

    @include('admin.partials.ui.modal-action')

    <x-admin.confirm-modal
        id="deletePostModal"
        ajax-confirm
        variant="danger"
        icon="ri-delete-bin-7-line"
        title="تأكيد حذف المقال"
        message="لا يمكن التراجع عن هذا الإجراء. سيتم حذف المقال نهائياً."
        confirm-text="نعم، احذف المقال"
    />
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deletePostModal');
    var currentPostId = null;

    if (!deleteModal) return;

    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        if (!button) return;
        currentPostId = button.getAttribute('data-post-id');
        var subjectEl = deleteModal.querySelector('[data-confirm-subject]');
        if (subjectEl) subjectEl.textContent = button.getAttribute('data-post-title') || '';
    });

    var confirmBtn = deleteModal.querySelector('[data-confirm-submit]');
    if (!confirmBtn) return;

    confirmBtn.addEventListener('click', function () {
        if (!currentPostId) return;

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الحذف...';

        fetch('{{ url('/admin/blog/posts') }}/' + currentPostId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (response) {
            var contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            }
            window.location.reload();
            return null;
        })
        .then(function (data) {
            if (!data) return;
            if (data.success) {
                if (window.adminUiToast) {
                    window.adminUiToast(data.message || 'تم حذف المقال بنجاح', 'success');
                }
                var filterForm = document.getElementById('postsFilterForm');
                if (filterForm && window.AdminAjaxFilter) {
                    window.AdminAjaxFilter.fetch(filterForm, window.location.href, false);
                }
                bootstrap.Modal.getInstance(deleteModal)?.hide();
            } else if (window.adminUiToast) {
                window.adminUiToast(data.message || 'حدث خطأ أثناء الحذف', 'error');
            }
        })
        .catch(function () {
            if (window.adminUiToast) {
                window.adminUiToast('حدث خطأ أثناء الحذف', 'error');
            }
        })
        .finally(function () {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="ri-delete-bin-7-line me-1"></i><span data-confirm-submit-text>نعم، احذف المقال</span>';
            currentPostId = null;
        });
    });
});
</script>
@endpush
