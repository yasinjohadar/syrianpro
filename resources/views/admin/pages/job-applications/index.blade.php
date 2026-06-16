@extends('admin.layouts.master')

@section('page-title')
طلبات التوظيف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'طلبات التوظيف'],
            ],
            'title' => 'طلبات التوظيف',
            'subtitle' => 'طلبات التقديم على الوظائف من المستخدمين المسجّلين',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-file-list-3-line',
                'label' => 'إجمالي الطلبات', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-time-line',
                'label' => 'قيد المراجعة', 'value' => number_format($stats['pending']),
                'hint' => 'بانتظار الإجراء',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'مقبول', 'value' => number_format($stats['accepted']),
                'hint' => 'تم القبول',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-close-circle-line',
                'label' => 'مرفوض', 'value' => number_format($stats['rejected']),
                'hint' => 'تم الرفض',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية الطلبات</div>
            <div class="filter-panel__subtitle">ابحث بالمتقدم أو الوظيفة أو فلتر حسب الحالة</div>
            <form action="{{ route('admin.job-applications.index') }}" method="GET" id="jobApplicationsFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-4">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" name="search" class="form-control"
                                   placeholder="اسم المتقدم، البريد، عنوان الوظيفة..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الوظيفة</label>
                        <select name="job_id" class="form-select">
                            <option value="">الكل</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ (string) request('job_id') === (string) $job->id ? 'selected' : '' }}>
                                    {{ $job->title }} — {{ $job->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select name="status" class="form-select">
                            <option value="">الكل</option>
                            @foreach(\App\Models\JobApplication::statusLabels() as $value => $label)
                                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="ri-search-2-line me-1"></i> بحث
                        </button>
                        <a href="{{ route('admin.job-applications.index') }}" class="btn btn-light border" title="مسح">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-bold fs-16">قائمة الطلبات</span>
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
                                <th style="min-width: 140px;">المتقدم</th>
                                <th style="min-width: 180px;">البريد</th>
                                <th style="min-width: 200px;">الوظيفة</th>
                                <th style="min-width: 120px;">الحالة</th>
                                <th style="min-width: 110px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $application)
                            <tr>
                                <td class="text-muted fw-medium">{{ $applications->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="meta-text">{{ $application->created_at->format('Y-m-d H:i') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="row-avatar {{ $loop->even ? 'row-avatar--alt' : '' }}">
                                            {{ mb_strtoupper(mb_substr($application->user?->name ?? '?', 0, 1)) }}
                                        </span>
                                        <a href="{{ route('admin.job-applications.show', $application) }}"
                                           class="fw-bold row-title-link text-decoration-none">
                                            {{ $application->user?->name ?? '—' }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    @if($application->user?->email)
                                        <span dir="ltr" class="text-primary">{{ $application->user->email }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->job)
                                        <div class="fw-semibold">{{ $application->job->title }}</div>
                                        <span class="text-muted fs-11">{{ $application->job->company_name }}</span>
                                    @else
                                        <span class="text-muted">وظيفة محذوفة</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($application->status) {
                                            'accepted' => 'badge-soft-success',
                                            'rejected' => 'badge-soft-danger',
                                            'shortlisted' => 'badge-soft-info',
                                            'reviewing' => 'badge-soft-warning',
                                            default => 'badge-soft-primary',
                                        };
                                    @endphp
                                    <span class="badge-soft {{ $statusClass }}">{{ $application->statusLabel() }}</span>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.job-applications.show', $application) }}"
                                           class="action-btn action-btn--view" title="عرض">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteApplicationModal"
                                                data-application-id="{{ $application->id }}"
                                                data-application-name="{{ $application->user?->name }}">
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
                                        <h5 class="fw-bold mb-2">لا توجد طلبات</h5>
                                        <p class="text-muted mb-0">لا توجد طلبات توظيف مطابقة للبحث.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($applications->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteApplicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteApplicationForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف الطلب</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف الطلب</h4>
                    <p class="action-modal-user mb-2" id="deleteApplicationName"></p>
                    <p class="text-muted mb-0 px-md-4">لا يمكن التراجع عن هذا الإجراء.</p>
                </div>
                <div class="modal-footer justify-content-center flex-wrap">
                    <button type="button" class="btn btn-light btn-lg px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger btn-lg px-4">نعم، احذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteApplicationModal');
    var deleteForm = document.getElementById('deleteApplicationForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteApplicationName').textContent = button.getAttribute('data-application-name');
            deleteForm.action = '{{ url('/admin/job-applications') }}/' + button.getAttribute('data-application-id');
        });
    }
});
</script>
@endpush
