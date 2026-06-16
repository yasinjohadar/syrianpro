@extends('admin.layouts.master')

@section('page-title')
    رسائل WhatsApp
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'واتساب'],
                ['label' => 'الرسائل'],
            ],
            'title' => 'رسائل WhatsApp',
            'subtitle' => 'عرض وإدارة الرسائل الواردة والصادرة',
            'actions' => '<div class="d-flex gap-3 flex-wrap">'
                . '<a href="' . route('admin.whatsapp-settings.index') . '" class="btn btn-link text-muted fw-bold text-decoration-none p-0"><i class="ri-settings-3-line me-1 fs-18"></i> الإعدادات</a>'
                . '<a href="' . route('admin.whatsapp-messages.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-send-plane-line me-1 fs-18"></i> إرسال رسالة</a>'
                . '</div>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-whatsapp-line',
                'label' => 'إجمالي الرسائل', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['filtered']) . ' حسب الفلاتر',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-inbox-line',
                'label' => 'واردة', 'value' => number_format($stats['inbound']),
                'hint' => 'inbound',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-send-plane-2-line',
                'label' => 'صادرة', 'value' => number_format($stats['outbound']),
                'hint' => 'outbound',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-error-warning-line',
                'label' => 'فاشلة', 'value' => number_format($stats['failed']),
                'hint' => 'failed',
            ])
        </div>

        <div class="filter-panel">
            <div class="filter-panel__title">تصفية الرسائل</div>
            <div class="filter-panel__subtitle">ابحث أو فلتر حسب الاتجاه والحالة والتاريخ</div>
            <form method="GET" action="{{ route('admin.whatsapp-messages.index') }}" id="waMessagesFilterForm">
                <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label fs-12 text-muted mb-1">بحث</label>
                        <div class="search-input-wrap">
                            <i class="ri-search-line"></i>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="البحث...">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">الاتجاه</label>
                        <select class="form-select" name="direction">
                            <option value="">الكل</option>
                            <option value="inbound" {{ request('direction') == 'inbound' ? 'selected' : '' }}>واردة</option>
                            <option value="outbound" {{ request('direction') == 'outbound' ? 'selected' : '' }}>صادرة</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">الحالة</label>
                        <select class="form-select" name="status">
                            <option value="">الكل</option>
                            <option value="queued" {{ request('status') == 'queued' ? 'selected' : '' }}>في الانتظار</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>مرسل</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>مستلم</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>مقروء</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">من تاريخ</label>
                        <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label fs-12 text-muted mb-1">إلى تاريخ</label>
                        <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-lg-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill" id="waSearchBtn">
                            <i class="ri-search-2-line"></i>
                        </button>
                        <a href="{{ route('admin.whatsapp-messages.index') }}" class="btn btn-light border" title="مسح">
                            <i class="ri-refresh-line"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">قائمة الرسائل</span>
                <span class="table-count-badge">{{ number_format($stats['filtered']) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th style="min-width:90px;">الاتجاه</th>
                                <th style="min-width:140px;">المستقبل</th>
                                <th style="min-width:200px;">الرسالة</th>
                                <th style="min-width:100px;">الحالة</th>
                                <th style="min-width:130px;">التاريخ</th>
                                <th style="min-width:80px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                            <tr>
                                <td class="text-muted fw-medium">{{ $message->id }}</td>
                                <td>
                                    @if($message->direction === 'inbound')
                                        <span class="badge-soft badge-soft-info">واردة</span>
                                    @else
                                        <span class="badge-soft badge-soft-primary">صادرة</span>
                                    @endif
                                </td>
                                <td dir="ltr" class="text-primary">{{ $message->contact->wa_id ?? '—' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($message->body ?? '—', 60) }}</td>
                                <td>
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
                                </td>
                                <td>
                                    <span class="meta-text">
                                        <i class="ri-time-line"></i>
                                        {{ $message->created_at->format('Y-m-d H:i') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.whatsapp-messages.show', $message) }}"
                                       class="action-btn action-btn--view" title="عرض">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-whatsapp-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد رسائل</h5>
                                        <p class="text-muted mb-3">لم تُرسل أو تُستقبل أي رسائل بعد.</p>
                                        <a href="{{ route('admin.whatsapp-messages.create') }}" class="btn btn-primary btn-sm">
                                            <i class="ri-send-plane-line me-1"></i> إرسال رسالة
                                        </a>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('waMessagesFilterForm');
    var btn = document.getElementById('waSearchBtn');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        });
    }
});
</script>
@endpush
