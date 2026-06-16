@extends('admin.layouts.master')

@section('page-title')
    جدولة النسخ
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'النسخ الاحتياطي'],
                ['label' => 'الجدولة'],
            ],
            'title' => 'جدولة النسخ الاحتياطية',
            'subtitle' => 'جدولة نسخ تلقائية دورية',
            'actions' => '<div class="d-flex gap-3 flex-wrap">'
                . '<a href="' . route('admin.backups.index') . '" class="btn btn-link text-muted fw-bold text-decoration-none p-0"><i class="ri-database-2-line me-1 fs-18"></i> النسخ</a>'
                . '<a href="' . route('admin.backup-schedules.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> جدولة جديدة</a>'
                . '</div>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-calendar-schedule-line',
                'label' => 'إجمالي الجدولات', 'value' => number_format($stats['total']),
                'hint' => 'مجدولة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'نشطة', 'value' => number_format($stats['active']),
                'hint' => 'تعمل تلقائياً',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-pause-circle-line',
                'label' => 'معطّلة', 'value' => number_format($stats['inactive']),
                'hint' => 'متوقفة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-time-line',
                'label' => 'الجدولات', 'value' => number_format($stats['total']),
                'hint' => 'في النظام',
            ])
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">قائمة الجدولات</span>
                <span class="table-count-badge">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th style="min-width:140px;">الاسم</th>
                                <th style="min-width:100px;">النوع</th>
                                <th style="min-width:100px;">التكرار</th>
                                <th style="min-width:80px;">الوقت</th>
                                <th style="min-width:90px;">الحالة</th>
                                <th style="min-width:130px;">التشغيل التالي</th>
                                <th style="min-width:150px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                            <tr>
                                <td class="text-muted fw-medium">{{ $schedule->id }}</td>
                                <td>
                                    <a href="{{ route('admin.backup-schedules.edit', $schedule->id) }}"
                                       class="fw-bold row-title-link text-decoration-none">
                                        {{ $schedule->name }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-soft badge-soft-info">
                                        {{ \App\Models\BackupSchedule::BACKUP_TYPES[$schedule->backup_type] }}
                                    </span>
                                </td>
                                <td>{{ \App\Models\BackupSchedule::FREQUENCIES[$schedule->frequency] }}</td>
                                <td><span class="meta-text"><i class="ri-time-line"></i> {{ $schedule->time }}</span></td>
                                <td>
                                    @if($schedule->is_active)
                                        <span class="badge-soft badge-soft-success">نشط</span>
                                    @else
                                        <span class="badge-soft badge-soft-secondary">معطّل</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="meta-text">
                                        {{ $schedule->next_run_at?->format('Y-m-d H:i') ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <a href="{{ route('admin.backup-schedules.edit', $schedule->id) }}"
                                           class="action-btn action-btn--edit" title="تعديل">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.backup-schedules.execute', $schedule->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn--view" title="تشغيل الآن">
                                                <i class="ri-play-line"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.backup-schedules.toggle-active', $schedule->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="action-btn {{ $schedule->is_active ? 'action-btn--delete' : 'action-btn--edit' }}"
                                                    title="{{ $schedule->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                <i class="ri-{{ $schedule->is_active ? 'pause' : 'play' }}-circle-line"></i>
                                            </button>
                                        </form>
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteScheduleModal"
                                                data-schedule-id="{{ $schedule->id }}"
                                                data-schedule-name="{{ $schedule->name }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-calendar-schedule-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد جدولات</h5>
                                        <p class="text-muted mb-3">أنشئ جدولة نسخ تلقائية.</p>
                                        <a href="{{ route('admin.backup-schedules.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ri-add-line me-1"></i> جدولة جديدة
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($schedules->hasPages())
                    <div class="card-footer border-top bg-transparent py-3">
                        {{ $schedules->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteScheduleForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف الجدولة</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف الجدولة</h4>
                    <p class="action-modal-user mb-2" id="deleteScheduleName"></p>
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
    var deleteModal = document.getElementById('deleteScheduleModal');
    var deleteForm = document.getElementById('deleteScheduleForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteScheduleName').textContent = button.getAttribute('data-schedule-name');
            deleteForm.action = '{{ url('/admin/backup-schedules') }}/' + button.getAttribute('data-schedule-id');
        });
    }
});
</script>
@endpush
