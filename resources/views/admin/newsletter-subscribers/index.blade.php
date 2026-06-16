@extends('admin.layouts.master')

@section('page-title')
النشرة البريدية
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'النشرة البريدية'],
            ],
            'title' => 'المشتركون في النشرة البريدية',
            'subtitle' => 'إدارة اشتراكات النشرة البريدية',
            'actions' => '<a href="' . route('admin.newsletter-subscribers.export', request()->query()) . '" class="btn btn-link text-success fw-bold text-decoration-none p-0"><i class="ri-download-2-line me-1 fs-18"></i> تصدير CSV</a>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-group-line',
                'label' => 'إجمالي المشتركين', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'نشط', 'value' => number_format($stats['active']),
                'hint' => 'اشتراك فعّال',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-calendar-check-line',
                'label' => 'جدد هذا الشهر', 'value' => number_format($stats['this_month']),
                'hint' => now()->locale('ar')->translatedFormat('F Y'),
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-mail-send-line',
                'label' => 'النتائج الحالية', 'value' => number_format($stats['filtered']),
                'hint' => 'في الجدول أدناه',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية المشتركين</div>
            <div class="filter-panel__subtitle">ابحث بالبريد أو فلتر حسب حالة الاشتراك</div>
            <form action="{{ route('admin.newsletter-subscribers.index') }}" method="GET" id="newsletterFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-6">
                        <label class="form-label fs-12 text-muted mb-1">بحث بالبريد</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control"
                                   placeholder="البريد الإلكتروني..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">الكل</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill" id="newsletterSearchBtn">
                            <i class="ri-search-2-line me-1"></i> بحث
                        </button>
                        <a href="{{ route('admin.newsletter-subscribers.index') }}" class="btn btn-light border" title="مسح">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة المشتركين</span>
                    <span class="table-count-badge">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 220px;">البريد الإلكتروني</th>
                                <th style="min-width: 120px;">المصدر</th>
                                <th style="min-width: 140px;">تاريخ الاشتراك</th>
                                <th style="min-width: 100px;">الحالة</th>
                                <th style="min-width: 80px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscribers as $subscriber)
                            <tr>
                                <td class="text-muted fw-medium">{{ $subscribers->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="email-copy-wrap">
                                        <span dir="ltr" class="fw-bold text-primary">{{ $subscriber->email }}</span>
                                        <button type="button" class="copy-btn" data-copy="{{ $subscriber->email }}" title="نسخ">
                                            <i class="ri-file-copy-line"></i>
                                        </button>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-soft badge-soft-info">
                                        {{ \App\Models\NewsletterSubscriber::sourceLabel($subscriber->source ?? '') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="meta-text">
                                        <i class="ri-calendar-line"></i>
                                        {{ ($subscriber->subscribed_at ?? $subscriber->created_at)->format('Y-m-d H:i') }}
                                    </span>
                                </td>
                                <td>
                                    @if($subscriber->is_active)
                                        <span class="badge-soft badge-soft-success">نشط</span>
                                    @else
                                        <span class="badge-soft badge-soft-secondary">ملغي</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                            class="action-btn action-btn--delete"
                                            title="حذف"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteSubscriberModal"
                                            data-subscriber-id="{{ $subscriber->id }}"
                                            data-subscriber-email="{{ $subscriber->email }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-mail-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا يوجد مشتركون</h5>
                                        <p class="text-muted mb-0">لا يوجد مشتركون مطابقون للبحث.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($subscribers->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">
                        {{ $subscribers->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteSubscriberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteSubscriberForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف المشترك</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف المشترك</h4>
                    <p class="action-modal-user mb-2" id="deleteSubscriberEmail"></p>
                    <p class="text-muted mb-0 px-md-4">لا يمكن التراجع عن هذا الإجراء.</p>
                </div>
                <div class="modal-footer justify-content-center flex-wrap">
                    <button type="button" class="btn btn-light btn-lg px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> إلغاء
                    </button>
                    <button type="submit" class="btn btn-danger btn-lg px-4">
                        <i class="ri-delete-bin-line me-1"></i> نعم، احذف
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
    var filterForm = document.getElementById('newsletterFilterForm');
    var searchBtn = document.getElementById('newsletterSearchBtn');
    if (filterForm && searchBtn) {
        filterForm.addEventListener('submit', function () {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري البحث...';
        });
    }

    var deleteModal = document.getElementById('deleteSubscriberModal');
    var deleteForm = document.getElementById('deleteSubscriberForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteSubscriberEmail').textContent = button.getAttribute('data-subscriber-email');
            deleteForm.action = '{{ url('/admin/newsletter-subscribers') }}/' + button.getAttribute('data-subscriber-id');
        });
    }
});
</script>
@endpush
