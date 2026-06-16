@extends('admin.layouts.master')

@section('page-title')
عرض رسالة تواصل
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div>
                <h4 class="mb-0">رسالة من {{ $contactMessage->name }}</h4>
                <p class="mb-0 text-muted">{{ $contactMessage->created_at->format('Y-m-d H:i') }} — {{ \App\Models\ContactMessage::subjectLabel($contactMessage->subject) }}</p>
            </div>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-right-circle me-2"></i> العودة للقائمة
                </a>
                <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
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
                <div class="card-title">تفاصيل الرسالة</div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">الاسم الكامل</label>
                        <p class="fw-semibold mb-0">{{ $contactMessage->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">البريد الإلكتروني</label>
                        <p class="mb-0"><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">رقم الهاتف</label>
                        <p class="mb-0">{{ $contactMessage->phone ?: '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">الموضوع</label>
                        <p class="mb-0"><span class="badge bg-primary-transparent">{{ \App\Models\ContactMessage::subjectLabel($contactMessage->subject) }}</span></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small">الرسالة</label>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($contactMessage->message)) !!}
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small">تاريخ الإرسال</label>
                        <p class="mb-0">{{ $contactMessage->created_at->format('Y-m-d H:i') }}</p>
                        @if($contactMessage->read_at)
                            <p class="text-muted small mb-0">تمت القراءة: {{ $contactMessage->read_at->format('Y-m-d H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
