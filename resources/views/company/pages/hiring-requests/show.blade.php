@extends('company.layouts.master')

@section('page-title')
طلب توظيف
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @php $talent = $hiringRequest->talent; @endphp

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'طلبات التوظيف', 'url' => route('company.hiring-requests.index')],
                ['label' => $talent?->name ?? 'طلب'],
            ],
            'title' => $hiringRequest->headline,
            'subtitle' => $hiringRequest->isPitch() ? 'Pitch موجّه لشركتك' : 'طلب توظيف عام',
            'actions' => ($talent ? '<a href="' . route('company.talents.show', $talent) . '" class="btn btn-primary-light btn-wave"><i class="ri-user-line me-1"></i> ملف التقني</a>' : ''),
        ])

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card form-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-file-text-line me-1 text-primary"></i> تفاصيل الطلب</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <span class="text-muted fs-12 d-block">نوع العمل</span>
                                <span class="fw-semibold">{{ $hiringRequest->employmentTypeLabel() }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted fs-12 d-block">العمل عن بُعد</span>
                                <span class="fw-semibold">{{ $hiringRequest->is_remote ? 'نعم' : 'لا' }}</span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted fs-12 d-block">المعدل المتوقع</span>
                                <span class="fw-semibold">
                                    @if($hiringRequest->rate_min && $hiringRequest->rate_max)
                                        ${{ $hiringRequest->rate_min }} – ${{ $hiringRequest->rate_max }} /ساعة
                                    @else
                                        —
                                    @endif
                                </span>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted fs-12 d-block">تاريخ النشر</span>
                                <span class="fw-semibold">{{ $hiringRequest->published_at?->format('Y-m-d') ?? '—' }}</span>
                            </div>
                        </div>
                        @if($hiringRequest->cover_message)
                            <h6 class="fw-semibold mb-2">رسالة التغطية</h6>
                            <p class="text-muted mb-0" style="line-height:1.8;">{{ $hiringRequest->cover_message }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($talent)
                <div class="card custom-card form-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-line me-1 text-primary"></i> التقني</h6>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">{{ $talent->name }}</h5>
                        <p class="text-muted mb-2">{{ $talent->title }}</p>
                        <p class="text-muted fs-12 mb-3"><i class="ri-map-pin-line me-1"></i>{{ $talent->city }}</p>
                        <a href="{{ route('company.talents.show', $talent) }}" class="btn btn-primary-light btn-sm w-100">عرض الملف الكامل</a>
                    </div>
                </div>
                @endif

                @if($company)
                <div class="card custom-card form-card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-reply-line me-1 text-primary"></i> ردك على الطلب</h6>
                    </div>
                    <div class="card-body">
                        @if($response)
                            <div class="alert alert-light border mb-3">
                                حالتك الحالية:
                                <strong>{{ $response->statusLabel() }}</strong>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('company.hiring-requests.respond', $hiringRequest) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">الحالة</label>
                                <select name="status" class="form-select form-input-enhanced" required>
                                    @foreach($responseStatuses as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $response?->status ?: 'interested') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">رسالة (اختياري)</label>
                                <textarea name="message" rows="3" class="form-control form-input-enhanced" placeholder="رسالة للتقني أو ملاحظات داخلية...">{{ old('message', $response?->message) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-send-plane-line me-1"></i> حفظ الرد
                            </button>
                        </form>
                        @if($hiringRequest->isPitch() && $hiringRequest->status !== 'hired' && $response && in_array($response->status, ['interested', 'contacted']))
                            <form method="POST" action="{{ route('company.hiring-requests.mark-hired', $hiringRequest) }}" class="mt-3" onsubmit="return confirm('تأكيد توظيف هذا التقني؟')">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="ri-trophy-line me-1"></i> تم التوظيف
                                </button>
                            </form>
                        @elseif($hiringRequest->status === 'hired')
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="ri-checkbox-circle-line me-1"></i> تم تأكيد التوظيف
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
