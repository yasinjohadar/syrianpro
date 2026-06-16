@extends('admin.layouts.master')

@section('page-title')
التصنيفات
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
                ['label' => 'التصنيفات'],
            ],
            'title' => 'تصنيفات المدونة',
            'subtitle' => 'إدارة تصنيفات المقالات وتنظيمها',
            'actions' => '<a href="' . route('admin.blog.categories.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة تصنيف جديد</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-folder-line',
                'label' => 'إجمالي التصنيفات', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'تصنيفات نشطة', 'value' => number_format($stats['active']),
                'hint' => 'مفعّلة حالياً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-pause-circle-line',
                'label' => 'غير نشطة', 'value' => number_format($stats['inactive']),
                'hint' => 'معطّلة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-article-line',
                'label' => 'إجمالي المقالات', 'value' => number_format($stats['posts']),
                'hint' => 'في كل التصنيفات',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية التصنيفات</div>
            <div class="filter-panel__subtitle">ابحث بالاسم أو فلتر حسب التصنيف الأب</div>
            <form action="{{ route('admin.blog.categories.index') }}" method="GET" id="categoriesFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control"
                                   placeholder="ابحث بالاسم..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <label class="form-label fs-12 text-muted mb-1">التصنيف الأب</label>
                        <select name="parent" class="form-select">
                            <option value="">الكل</option>
                            <option value="root" {{ request('parent') === 'root' ? 'selected' : '' }}>التصنيفات الرئيسية فقط</option>
                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill" id="categoriesSearchBtn">
                            <i class="ri-search-2-line me-1"></i> بحث
                        </button>
                        <a href="{{ route('admin.blog.categories.index') }}" class="btn btn-light border" title="مسح">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة التصنيفات</span>
                    <span class="table-count-badge">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 220px;">الاسم</th>
                                <th style="min-width: 140px;">التصنيف الأب</th>
                                <th style="min-width: 120px;">عدد المقالات</th>
                                <th style="min-width: 80px;">الترتيب</th>
                                <th style="min-width: 100px;">الحالة</th>
                                <th style="min-width: 150px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td class="text-muted fw-medium">{{ $categories->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}"
                                              @if($category->color) style="background: {{ $category->color }};" @endif>
                                            @if($category->icon)
                                                <i class="{{ $category->icon }}"></i>
                                            @else
                                                {{ mb_strtoupper(mb_substr($category->name, 0, 1)) }}
                                            @endif
                                        </span>
                                        <div>
                                            <a href="{{ route('admin.blog.categories.edit', $category->id) }}"
                                               class="fw-bold row-title-link text-decoration-none d-block">
                                                {{ $category->name }}
                                            </a>
                                            @if($category->slug)
                                                <span class="text-muted fs-11" dir="ltr">{{ $category->slug }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($category->parent)
                                        <span class="badge-soft badge-soft-secondary">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="badge-soft badge-soft-primary">رئيسي</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.blog.posts.index', ['category' => $category->id]) }}"
                                       class="badge-soft badge-soft-info text-decoration-none">
                                        {{ number_format($category->posts_count) }} مقال
                                    </a>
                                </td>
                                <td>
                                    <span class="meta-text">
                                        <i class="ri-sort-asc"></i>
                                        {{ $category->order }}
                                    </span>
                                </td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge-soft badge-soft-success">نشط</span>
                                    @else
                                        <span class="badge-soft badge-soft-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.blog.categories.edit', $category->id) }}"
                                           class="action-btn action-btn--edit" title="تعديل">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('admin.blog.categories.toggle-active', $category->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="action-btn action-btn--success {{ $category->is_active ? 'is-active' : '' }}"
                                                    title="{{ $category->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                <i class="ri-{{ $category->is_active ? 'checkbox-circle-fill' : 'checkbox-circle-line' }}"></i>
                                            </button>
                                        </form>
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteCategoryModal"
                                                data-category-id="{{ $category->id }}"
                                                data-category-name="{{ $category->name }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-folder-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد تصنيفات</h5>
                                        <p class="text-muted mb-3">لم يتم العثور على تصنيفات مطابقة.</p>
                                        <a href="{{ route('admin.blog.categories.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ri-add-line me-1"></i> إضافة تصنيف جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($categories->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteCategoryForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف التصنيف</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف التصنيف</h4>
                    <p class="action-modal-user mb-2" id="deleteCategoryName"></p>
                    <p class="text-muted mb-0 px-md-4">
                        لا يمكن التراجع عن هذا الإجراء. سيتم حذف التصنيف نهائياً.
                    </p>
                </div>
                <div class="modal-footer justify-content-center flex-wrap">
                    <button type="button" class="btn btn-light btn-lg px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-danger btn-lg px-4">
                        <i class="ri-delete-bin-line me-1"></i> نعم، احذف التصنيف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var filterForm = document.getElementById('categoriesFilterForm');
    var searchBtn = document.getElementById('categoriesSearchBtn');
    if (filterForm && searchBtn) {
        filterForm.addEventListener('submit', function () {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري البحث...';
        });
    }

    var deleteModal = document.getElementById('deleteCategoryModal');
    var deleteForm = document.getElementById('deleteCategoryForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-category-id');
            var name = button.getAttribute('data-category-name');
            document.getElementById('deleteCategoryName').textContent = name;
            deleteForm.action = '{{ url('/admin/blog/categories') }}/' + id;
        });
    }
});
</script>
@endpush
