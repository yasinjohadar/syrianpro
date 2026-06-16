@extends('admin.layouts.master')

@section('page-title')
عرض طلب استشارة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h4 class="mb-0">طلب استشارة من {{ $consultationRequest->name }}</h4>
                <p class="mb-0 text-muted">{{ $consultationRequest->created_at->format('Y-m-d H:i') }} — {{ \App\Models\ConsultationRequest::consultationTypeLabel($consultationRequest->consultation_type) }}</p>
            </div>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('admin.consultation-requests.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-right-circle me-2"></i> العودة للقائمة
                </a>
                <form action="{{ route('admin.consultation-requests.destroy', $consultationRequest) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-light">
                        <i class="bi bi-trash me-1"></i> حذف
                    </button>
                </form>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">تفاصيل الطلب</div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">الاسم الكامل</label>
                        <p class="fw-semibold mb-0">{{ $consultationRequest->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">البريد الإلكتروني</label>
                        <p class="mb-0"><a href="mailto:{{ $consultationRequest->email }}">{{ $consultationRequest->email }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">رقم الهاتف / واتساب</label>
                        <p class="mb-0">{{ $consultationRequest->phone ?: '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">نوع الاستشارة</label>
                        <p class="mb-0"><span class="badge bg-primary-transparent">{{ \App\Models\ConsultationRequest::consultationTypeLabel($consultationRequest->consultation_type) }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">التاريخ المفضل</label>
                        <p class="mb-0">{{ $consultationRequest->preferred_date?->format('Y-m-d') ?: '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">الوقت المفضل</label>
                        <p class="mb-0">{{ \App\Models\ConsultationRequest::preferredTimeLabel($consultationRequest->preferred_time) }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small">موضوع الاستشارة / وصف مختصر</label>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($consultationRequest->topic)) !!}
                        </div>
                    </div>
                    @if($consultationRequest->notes)
                    <div class="col-12">
                        <label class="form-label text-muted small">ملاحظات إضافية</label>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($consultationRequest->notes)) !!}
                        </div>
                    </div>
                    @endif
                    <div class="col-12">
                        <label class="form-label text-muted small">تاريخ الإرسال</label>
                        <p class="mb-0">{{ $consultationRequest->created_at->format('Y-m-d H:i') }}</p>
                        @if($consultationRequest->read_at)
                            <p class="text-muted small mb-0">تمت القراءة: {{ $consultationRequest->read_at->format('Y-m-d H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
