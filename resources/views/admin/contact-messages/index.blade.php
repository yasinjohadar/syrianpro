@extends('admin.layouts.master')

@section('page-title')
رسائل التواصل
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'رسائل التواصل'],
            ],
            'title' => 'رسائل التواصل',
            'subtitle' => 'رسائل النموذج من صفحة تواصل معنا',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-mail-line',
                'label' => 'إجمالي الرسائل', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-mail-unread-line',
                'label' => 'غير مقروءة', 'value' => number_format($stats['unread']),
                'hint' => 'بانتظار المراجعة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-mail-check-line',
                'label' => 'مقروءة', 'value' => number_format($stats['read']),
                'hint' => 'تمت مراجعتها',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-inbox-line',
                'label' => 'النتائج الحالية', 'value' => number_format($stats['filtered']),
                'hint' => 'في الجدول أدناه',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية الرسائل</div>
            <div class="filter-panel__subtitle">ابحث بالاسم أو البريد أو فلتر حسب الموضوع والحالة</div>
            <form action="{{ route('admin.contact-messages.index') }}" method="GET" id="contactMessagesFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control"
                                   placeholder="الاسم، البريد، أو نص الرسالة..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الموضوع</label>
                        <select name="subject" class="form-select">
                            <option value="">الكل</option>
                            <option value="course" {{ request('subject') === 'course' ? 'selected' : '' }}>استفسار عن دورة تدريبية</option>
                            <option value="project" {{ request('subject') === 'project' ? 'selected' : '' }}>طلب مشروع برمجي</option>
                            <option value="private" {{ request('subject') === 'private' ? 'selected' : '' }}>تدريب خاص</option>
                            <option value="collab" {{ request('subject') === 'collab' ? 'selected' : '' }}>تعاون وشراكة</option>
                            <option value="other" {{ request('subject') === 'other' ? 'selected' : '' }}>أخرى</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="read" class="form-select">
                            <option value="">الكل</option>
                            <option value="unread" {{ request('read') === 'unread' ? 'selected' : '' }}>غير مقروءة</option>
                            <option value="read" {{ request('read') === 'read' ? 'selected' : '' }}>مقروءة</option>
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill" id="contactMessagesSearchBtn">
                            <i class="ri-search-2-line me-1"></i> بحث
                        </button>
                        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-light border" title="مسح">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة الرسائل</span>
                    <span class="table-count-badge">{{ number_format($stats['filtered']) }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 130px;">التاريخ</th>
                                <th style="min-width: 140px;">الاسم</th>
                                <th style="min-width: 180px;">البريد</th>
                                <th style="min-width: 160px;">الموضوع</th>
                                <th style="min-width: 100px;">الحالة</th>
                                <th style="min-width: 110px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                            <tr class="{{ !$message->is_read ? 'table-row-unread' : '' }}">
                                <td class="text-muted fw-medium">{{ $messages->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="meta-text" title="{{ $message->created_at->locale('ar')->translatedFormat('j F Y، H:i') }}">
                                        <i class="ri-time-line"></i>
                                        {{ $message->created_at->format('Y-m-d H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                            {{ mb_strtoupper(mb_substr($message->name, 0, 1)) }}
                                        </span>
                                        <a href="{{ route('admin.contact-messages.show', $message) }}"
                                           class="fw-bold row-title-link text-decoration-none">
                                            {{ $message->name }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    @if($message->email)
                                        <span class="email-copy-wrap">
                                            <span dir="ltr" class="text-primary">{{ $message->email }}</span>
                                            <button type="button" class="copy-btn" data-copy="{{ $message->email }}" title="نسخ">
                                                <i class="ri-file-copy-line"></i>
                                            </button>
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-soft badge-soft-primary">
                                        {{ \App\Models\ContactMessage::subjectLabel($message->subject) }}
                                    </span>
                                </td>
                                <td>
                                    @if($message->is_read)
                                        <span class="badge-soft badge-soft-success">مقروءة</span>
                                    @else
                                        <span class="badge-soft badge-soft-warning">جديدة</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.contact-messages.show', $message) }}"
                                           class="action-btn action-btn--view" title="عرض">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteMessageModal"
                                                data-message-id="{{ $message->id }}"
                                                data-message-name="{{ $message->name }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-inbox-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد رسائل</h5>
                                        <p class="text-muted mb-0">لا توجد رسائل تواصل مطابقة للبحث.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($messages->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">
                        {{ $messages->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteMessageForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف الرسالة</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف الرسالة</h4>
                    <p class="action-modal-user mb-2" id="deleteMessageName"></p>
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
    var filterForm = document.getElementById('contactMessagesFilterForm');
    var searchBtn = document.getElementById('contactMessagesSearchBtn');
    if (filterForm && searchBtn) {
        filterForm.addEventListener('submit', function () {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري البحث...';
        });
    }

    var deleteModal = document.getElementById('deleteMessageModal');
    var deleteForm = document.getElementById('deleteMessageForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteMessageName').textContent = button.getAttribute('data-message-name');
            deleteForm.action = '{{ url('/admin/contact-messages') }}/' + button.getAttribute('data-message-id');
        });
    }
});
</script>
@endpush
