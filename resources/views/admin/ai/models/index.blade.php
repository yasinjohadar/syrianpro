@extends('admin.layouts.master')

@section('page-title', 'موديلات الذكاء الاصطناعي')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الذكاء الاصطناعي'],
                ['label' => 'الموديلات'],
            ],
            'title' => 'موديلات AI',
            'subtitle' => 'سجل الموديلات المُدار عبر <code>config/ai-panel.php</code>',
            'actions' => '<div class="d-flex gap-3 flex-wrap">'
                . '<a href="' . route('admin.ai.settings.index') . '" class="btn btn-link text-muted fw-bold text-decoration-none p-0"><i class="ri-settings-3-line me-1 fs-18"></i> الإعدادات</a>'
                . '<a href="' . route('admin.ai.models.create', ['mode' => 'catalog']) . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> من الكتالوج</a>'
                . '<a href="' . route('admin.ai.models.create', ['mode' => 'custom']) . '" class="btn btn-link text-info fw-bold text-decoration-none p-0"><i class="ri-edit-box-line me-1 fs-18"></i> موديل مخصص</a>'
                . '</div>',
        ])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-cpu-line',
                'label' => 'إجمالي الموديلات', 'value' => number_format($stats['total']),
                'hint' => number_format($stats['providers']) . ' مزود',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'نشط', 'value' => number_format($stats['active']),
                'hint' => 'جاهز للاستخدام',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-pause-circle-line',
                'label' => 'معطّل', 'value' => number_format($stats['inactive']),
                'hint' => 'غير مستخدم',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-cloud-line',
                'label' => 'المزودون', 'value' => number_format($stats['providers']),
                'hint' => 'OpenAI، Claude…',
            ])
        </div>

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">سجل الموديلات</span>
                <span class="table-count-badge">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="min-width: 160px;">اسم الموديل</th>
                                <th style="min-width: 110px;">المزود</th>
                                <th style="min-width: 160px;">معرّف API</th>
                                <th style="min-width: 160px;">القدرات</th>
                                <th style="min-width: 120px;">افتراضي</th>
                                <th style="min-width: 90px;">الحالة</th>
                                <th style="min-width: 120px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registry as $model)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.ai.models.edit', $model['id']) }}"
                                       class="fw-bold row-title-link text-decoration-none">
                                        {{ $model['name'] }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge-soft badge-soft-info">
                                        {{ $providerLabels[$model['provider']] ?? $model['provider'] }}
                                    </span>
                                </td>
                                <td><code class="fs-12">{{ $model['model_key'] }}</code></td>
                                <td>
                                    @foreach($model['capabilities'] ?? [] as $cap)
                                        <span class="badge-soft badge-soft-primary me-1 mb-1">
                                            {{ $capabilityLabels[$cap] ?? $cap }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($model['is_default'] ?? [] as $cap)
                                        <span class="badge-soft badge-soft-success me-1 mb-1">{{ $cap }}</span>
                                    @endforeach
                                    @if(empty($model['is_default']))
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($model['is_active'] ?? true)
                                        <span class="badge-soft badge-soft-success">نشط</span>
                                    @else
                                        <span class="badge-soft badge-soft-secondary">معطّل</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <button type="button"
                                                class="action-btn action-btn--view"
                                                title="فحص الاتصال"
                                                onclick="testAiModelConnection({
                                                    provider: @json($model['provider']),
                                                    modelKey: @json($model['model_key']),
                                                    modelName: @json($model['name']),
                                                    button: this
                                                })">
                                            <i class="ri-wifi-line"></i>
                                        </button>
                                        <a href="{{ route('admin.ai.models.edit', $model['id']) }}"
                                           class="action-btn action-btn--edit" title="تعديل">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModelModal"
                                                data-model-id="{{ $model['id'] }}"
                                                data-model-name="{{ $model['name'] }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="ri-cpu-line"></i></div>
                                        <h5 class="fw-bold mb-2">لا توجد موديلات</h5>
                                        <p class="text-muted mb-3">أضف موديلاً من الكتالوج أو أنشئ موديلاً مخصصاً.</p>
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <a href="{{ route('admin.ai.models.create', ['mode' => 'catalog']) }}" class="btn btn-primary btn-sm">من الكتالوج</a>
                                            <a href="{{ route('admin.ai.models.create', ['mode' => 'custom']) }}" class="btn btn-light border btn-sm">موديل مخصص</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.ai.models._test-connection')
@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteModelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteModelForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف الموديل</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف الموديل</h4>
                    <p class="action-modal-user mb-2" id="deleteModelName"></p>
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
    var deleteModal = document.getElementById('deleteModelModal');
    var deleteForm = document.getElementById('deleteModelForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteModelName').textContent = button.getAttribute('data-model-name');
            deleteForm.action = '{{ url('/admin/ai/models') }}/' + button.getAttribute('data-model-id');
        });
    }
});
</script>
@endpush
