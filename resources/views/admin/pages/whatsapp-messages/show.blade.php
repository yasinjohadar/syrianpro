@extends('admin.layouts.master')

@section('page-title')
    تفاصيل الرسالة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'واتساب', 'url' => route('admin.whatsapp-messages.index')],
                ['label' => 'رسالة #' . $message->id],
            ],
            'title' => 'تفاصيل الرسالة',
            'subtitle' => $message->created_at->format('Y-m-d H:i:s'),
            'actions' => '<a href="' . route('admin.whatsapp-messages.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card form-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-message-2-line me-1 text-primary"></i> معلومات الرسالة</h6>
                        @if(in_array($message->status, ['queued', 'failed']))
                            <button type="button" class="btn btn-sm btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#retryMessageModal">
                                <i class="ri-refresh-line me-1"></i> إعادة المحاولة
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="text-muted fs-12 mb-1">المعرّف</div>
                                    <div class="fw-bold">#{{ $message->id }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="text-muted fs-12 mb-1">الاتجاه</div>
                                    <div>
                                        @if($message->direction === 'inbound')
                                            <span class="badge-soft badge-soft-info">واردة</span>
                                        @else
                                            <span class="badge-soft badge-soft-primary">صادرة</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="text-muted fs-12 mb-1">المستقبل</div>
                                    <div class="fw-bold" dir="ltr">{{ $message->contact->wa_id ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="text-muted fs-12 mb-1">Meta Message ID</div>
                                    <div class="fw-medium fs-13" dir="ltr">{{ $message->meta_message_id ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="text-muted fs-12 mb-1">النوع</div>
                                    <div class="fw-bold">{{ $message->type }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="text-muted fs-12 mb-1">الحالة</div>
                                    <div>
                                        @php
                                            $statusMap = [
                                                'sent' => ['success', 'مرسل'],
                                                'delivered' => ['info', 'مستلم'],
                                                'read' => ['primary', 'مقروء'],
                                                'failed' => ['danger', 'فشل'],
                                                'queued' => ['warning', 'في الانتظار'],
                                            ];
                                            $st = $statusMap[$message->status] ?? ['warning', 'في الانتظار'];
                                        @endphp
                                        <span class="badge-soft badge-soft-{{ $st[0] }}">{{ $st[1] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded-3 bg-light">
                                    <div class="text-muted fs-12 mb-1">الرسالة</div>
                                    <div class="fw-medium">{{ $message->body ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 bg-light h-100">
                                    <div class="text-muted fs-12 mb-1">تاريخ الإنشاء</div>
                                    <div class="fw-medium">{{ $message->created_at->format('Y-m-d H:i:s') }}</div>
                                </div>
                            </div>
                        </div>

                        @if($message->error)
                            <div class="alert alert-danger mt-4 mb-0">
                                <strong><i class="ri-error-warning-line me-1"></i> خطأ:</strong>
                                <pre class="mb-0 mt-2 fs-12">{{ json_encode($message->error, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@if(in_array($message->status, ['queued', 'failed']))
<div class="modal fade modal-user-action" id="retryMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">إعادة إرسال الرسالة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-0">هل تريد إعادة إرسال هذه الرسالة؟</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">إلغاء</button>
                <form action="{{ route('admin.whatsapp-messages.retry', $message) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">تأكيد</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
