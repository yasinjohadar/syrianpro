@extends('admin.layouts.master')

@section('page-title')
تفاصيل طلب التوظيف
@stop

@php
    $applicant = $jobApplication->user;
    $job = $jobApplication->job;
    $initial = mb_strtoupper(mb_substr($applicant?->name ?? '?', 0, 1));
    $statusBadgeClass = match($jobApplication->status) {
        'accepted' => 'badge-soft-success',
        'rejected' => 'badge-soft-danger',
        'shortlisted' => 'badge-soft-info',
        'reviewing' => 'badge-soft-warning',
        default => 'badge-soft-primary',
    };
@endphp

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'طلبات التوظيف', 'url' => route('admin.job-applications.index')],
                ['label' => $applicant?->name ?? 'طلب #' . $jobApplication->id],
            ],
            'title' => 'طلب توظيف — ' . ($applicant?->name ?? '—'),
            'subtitle' => '<span class="badge-soft ' . $statusBadgeClass . ' me-2">' . $jobApplication->statusLabel() . '</span>'
                . '<span class="text-muted">تقدّم في ' . $jobApplication->created_at->format('Y-m-d H:i') . '</span>',
            'actions' => '
                <a href="' . route('admin.job-applications.index') . '" class="btn btn-light border btn-wave">
                    <i class="ri-arrow-right-line me-1"></i> رجوع
                </a>
                <button type="button" class="btn btn-danger-light btn-wave"
                        data-bs-toggle="modal" data-bs-target="#deleteApplicationModal">
                    <i class="ri-delete-bin-line me-1"></i> حذف
                </button>
            ',
        ])

        <form action="{{ route('admin.job-applications.update-status', $jobApplication) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-3-line me-1 text-primary"></i> المتقدم</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="user-avatar-upload-wrap mb-3">
                                <div class="user-avatar-preview-wrap mx-auto">
                                    @if($applicant?->photo)
                                        <img class="user-avatar-preview"
                                             src="{{ asset('storage/' . $applicant->photo) }}"
                                             alt="{{ $applicant->name }}">
                                    @else
                                        <span class="user-avatar-initial">{{ $initial }}</span>
                                    @endif
                                </div>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $applicant?->name ?? '—' }}</h5>
                            @if($applicant?->email)
                                <p class="text-muted fs-13 mb-2" dir="ltr">{{ $applicant->email }}</p>
                            @endif
                            <span class="badge-soft {{ $statusBadgeClass }}">{{ $jobApplication->statusLabel() }}</span>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-calendar-line me-1 text-primary"></i> ملخص الطلب</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted fs-12 mb-1">تاريخ التقديم</label>
                                <p class="mb-0 fw-semibold">{{ $jobApplication->created_at->format('Y-m-d H:i') }}</p>
                                <p class="text-muted fs-12 mb-0">{{ $jobApplication->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted fs-12 mb-1">آخر تحديث</label>
                                <p class="mb-0 fw-semibold">{{ $jobApplication->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                            @if($job)
                            <div>
                                <label class="form-label fw-semibold text-muted fs-12 mb-1">الوظيفة</label>
                                <p class="mb-0 fw-semibold">{{ $job->title }}</p>
                                <p class="text-muted fs-12 mb-0">{{ $job->company_name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($job)
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-links-line me-1 text-primary"></i> روابط سريعة</h6>
                        </div>
                        <div class="card-body d-grid gap-2">
                            <a href="{{ route('jobs.show', $job) }}" target="_blank" class="btn btn-light border btn-wave">
                                <i class="ri-external-link-line me-1"></i> عرض في الموقع
                            </a>
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-light border btn-wave">
                                <i class="ri-edit-line me-1"></i> تعديل الوظيفة
                            </a>
                            @if($applicant)
                            <a href="{{ route('admin.users.edit', $applicant) }}" class="btn btn-light border btn-wave">
                                <i class="ri-user-settings-line me-1"></i> ملف المستخدم
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-8 order-lg-1">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-contacts-line me-1 text-primary"></i> بيانات المتقدم</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الاسم الكامل</label>
                                    <input type="text" class="form-control form-input-enhanced"
                                           value="{{ $applicant?->name ?? '—' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">اسم المستخدم</label>
                                    <input type="text" class="form-control form-input-enhanced"
                                           value="{{ $applicant?->username ?? '—' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">البريد الإلكتروني</label>
                                    <input type="email" class="form-control form-input-enhanced" dir="ltr"
                                           value="{{ $applicant?->email ?? '—' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">رقم الهاتف</label>
                                    <input type="text" class="form-control form-input-enhanced" dir="ltr"
                                           value="{{ $applicant?->phone ?? '—' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-briefcase-line me-1 text-primary"></i> الوظيفة</h6>
                        </div>
                        <div class="card-body">
                            @if($job)
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold">عنوان الوظيفة</label>
                                        <input type="text" class="form-control form-input-enhanced"
                                               value="{{ $job->title }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">الشركة</label>
                                        <input type="text" class="form-control form-input-enhanced"
                                               value="{{ $job->company_name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الموقع</label>
                                        <input type="text" class="form-control form-input-enhanced"
                                               value="{{ $job->location }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">نوع الدوام</label>
                                        <input type="text" class="form-control form-input-enhanced"
                                               value="{{ $job->employment_type }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الراتب</label>
                                        <input type="text" class="form-control form-input-enhanced"
                                               value="{{ $job->salary_display }} {{ $job->currency }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">نوع العمل</label>
                                        <input type="text" class="form-control form-input-enhanced"
                                               value="{{ $job->remote_type === 'full-remote' ? 'عن بُعد' : $job->remote_type }}" readonly>
                                    </div>
                                </div>
                            @else
                                <div class="empty-state py-4">
                                    <div class="empty-state-icon"><i class="ri-briefcase-line"></i></div>
                                    <p class="text-muted mb-0">الوظيفة المرتبطة بهذا الطلب غير متوفرة (قد تكون محذوفة).</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-shield-check-line me-1 text-primary"></i> حالة الطلب</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">الحالة <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select form-input-enhanced @error('status') is-invalid @enderror" required>
                                        @foreach(\App\Models\JobApplication::statusLabels() as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', $jobApplication->status) === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">ملاحظات داخلية</label>
                                    <textarea name="admin_notes" rows="4"
                                              class="form-control form-input-enhanced @error('admin_notes') is-invalid @enderror"
                                              placeholder="ملاحظات للفريق الإداري فقط — لا تظهر للمتقدم">{{ old('admin_notes', $jobApplication->admin_notes) }}</textarea>
                                    @error('admin_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <p class="text-muted fs-12 mb-0 mt-2">هذه الملاحظات داخلية ولا يراها المتقدم.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card form-card">
                <div class="card-body py-3">
                    <div class="form-actions border-0 pt-0 mt-0">
                        <a href="{{ route('admin.job-applications.index') }}" class="btn btn-light border px-4">
                            <i class="ri-close-line me-1"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary px-4 btn-wave">
                            <i class="ri-save-line me-1"></i> حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteApplicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.job-applications.destroy', $jobApplication) }}" method="POST">
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
                    <p class="action-modal-user mb-2">{{ $applicant?->name ?? '—' }}</p>
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
