@extends('company.layouts.master')

@section('page-title')
تفاصيل الطلب
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'المتقدمون', 'url' => route('company.applications.index')],
                ['label' => 'تفاصيل الطلب'],
            ],
            'title' => 'طلب توظيف',
            'subtitle' => $application->job?->title ?? 'وظيفة محذوفة',
            'actions' => '<a href="' . route('company.applications.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card form-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-line me-1 text-primary"></i> بيانات المتقدم</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted fs-12 mb-1">الاسم</div>
                                <div class="fw-semibold">{{ $application->user?->name ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted fs-12 mb-1">البريد</div>
                                <div class="fw-semibold">{{ $application->user?->email ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted fs-12 mb-1">تاريخ التقديم</div>
                                <div class="fw-semibold">{{ $application->created_at->translatedFormat('j M Y — H:i') }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted fs-12 mb-1">الحالة الحالية</div>
                                <span class="badge bg-primary-transparent">{{ $application->statusLabel() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card custom-card form-card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-edit-line me-1 text-primary"></i> تحديث الحالة</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('company.applications.update', $application) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الحالة</label>
                                    <select name="status" class="form-select form-input-enhanced @error('status') is-invalid @enderror" required>
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" @selected(old('status', $application->status) === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">ملاحظات داخلية</label>
                                    <textarea name="admin_notes" rows="4" class="form-control form-input-enhanced" placeholder="ملاحظات للفريق...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> حفظ التحديث
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card custom-card form-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-briefcase-line me-1 text-primary"></i> الوظيفة</h6>
                    </div>
                    <div class="card-body">
                        @if($application->job)
                            <p class="fw-semibold mb-1">{{ $application->job->title }}</p>
                            <p class="text-muted fs-12 mb-2">{{ $application->job->location }} · {{ $application->job->employment_type }}</p>
                            <a href="{{ route('jobs.show', $application->job) }}" class="btn btn-sm btn-primary-light" target="_blank">عرض الوظيفة</a>
                        @else
                            <p class="text-muted mb-0">الوظيفة غير متاحة</p>
                        @endif
                    </div>
                </div>

                @if($application->user?->talent)
                    <div class="card custom-card form-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-star-line me-1 text-primary"></i> ملف الموهبة</h6>
                        </div>
                        <div class="card-body">
                            <p class="fw-semibold mb-1">{{ $application->user->talent->title }}</p>
                            <p class="text-muted fs-12 mb-3">{{ $application->user->talent->city }}</p>
                            <a href="{{ route('company.talents.show', $application->user->talent) }}" class="btn btn-sm btn-light" target="_blank">عرض الملف</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
