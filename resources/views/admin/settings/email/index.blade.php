@extends('admin.layouts.master')

@section('page-title')
    إعدادات البريد
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الإعدادات'],
                ['label' => 'البريد الإلكتروني'],
            ],
            'title' => 'إعدادات البريد الإلكتروني (SMTP)',
            'subtitle' => 'إدارة إعدادات إرسال البريد الإلكتروني',
            'actions' => '<a href="' . route('admin.settings.email.create') . '" class="btn btn-link text-primary fw-bold text-decoration-none p-0"><i class="ri-add-circle-line me-1 fs-18"></i> إضافة إعدادات</a>',
        ])

        @if(!$activeSettings)
            <div class="alert alert-warning border-0 shadow-sm mb-4 rounded-3" role="alert">
                <i class="ri-error-warning-line me-1"></i>
                <strong>تنبيه:</strong> لا توجد إعدادات بريد نشطة. أضف وفعّل إعدادات SMTP لإرسال البريد.
            </div>
        @endif

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple', 'icon' => 'ri-mail-settings-line',
                'label' => 'إجمالي الإعدادات', 'value' => number_format($stats['total']),
                'hint' => 'محفوظة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green', 'icon' => 'ri-checkbox-circle-line',
                'label' => 'نشطة', 'value' => number_format($stats['active']),
                'hint' => $activeSettings ? 'جاهزة للإرسال' : 'لا يوجد',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange', 'icon' => 'ri-pause-circle-line',
                'label' => 'غير نشطة', 'value' => number_format($stats['inactive']),
                'hint' => 'احتياطية',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan', 'icon' => 'ri-test-tube-line',
                'label' => 'تم اختبارها', 'value' => number_format($stats['tested']),
                'hint' => 'اختبار SMTP',
            ])
        </div>

        @if($activeSettings)
        <div class="card custom-card form-card border-success mb-4">
            <div class="card-header bg-success bg-opacity-10">
                <h6 class="mb-0 fw-semibold fs-15 text-success">
                    <i class="ri-mail-check-line me-1"></i> الإعدادات النشطة حالياً
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="p-3 rounded-3 bg-light h-100">
                            <div class="text-muted fs-12 mb-1">المزود</div>
                            <div class="fw-bold">{{ $providers[$activeSettings->provider]['name'] ?? 'مخصص' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded-3 bg-light h-100">
                            <div class="text-muted fs-12 mb-1">البريد المرسل</div>
                            <div class="fw-bold" dir="ltr">{{ $activeSettings->mail_from_address }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded-3 bg-light h-100">
                            <div class="text-muted fs-12 mb-1">التشفير</div>
                            <div class="fw-bold">{{ strtoupper($activeSettings->mail_encryption) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 rounded-3 bg-light h-100">
                            <div class="text-muted fs-12 mb-1">آخر اختبار</div>
                            <div class="fw-bold">
                                @if($activeSettings->last_tested_at)
                                    {{ $activeSettings->last_tested_at->diffForHumans() }}
                                @else
                                    لم يُختبر
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card custom-card data-table-card">
            <div class="card-header d-flex align-items-center gap-2">
                <span class="fw-bold fs-16">جميع الإعدادات المحفوظة</span>
                <span class="table-count-badge">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="card-body p-0">
                @if($settings->count() > 0)
                <div class="table-responsive">
                    <table class="table data-table mb-0">
                        <thead>
                            <tr>
                                <th style="min-width:130px;">المزود</th>
                                <th style="min-width:140px;">SMTP Host</th>
                                <th style="min-width:70px;">Port</th>
                                <th style="min-width:160px;">البريد</th>
                                <th style="min-width:90px;">التشفير</th>
                                <th style="min-width:90px;">الحالة</th>
                                <th style="min-width:120px;">آخر اختبار</th>
                                <th style="min-width:150px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($settings as $setting)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($setting->provider == 'gmail')
                                            <i class="ri-google-fill text-danger fs-18"></i>
                                        @elseif($setting->provider == 'outlook')
                                            <i class="ri-microsoft-fill text-info fs-18"></i>
                                        @else
                                            <i class="ri-mail-settings-line text-primary fs-18"></i>
                                        @endif
                                        <span class="fw-medium">{{ $providers[$setting->provider]['name'] ?? 'مخصص' }}</span>
                                    </div>
                                </td>
                                <td><code class="fs-12">{{ $setting->mail_host }}</code></td>
                                <td><span class="badge-soft badge-soft-secondary">{{ $setting->mail_port }}</span></td>
                                <td><span dir="ltr" class="text-primary">{{ $setting->mail_from_address }}</span></td>
                                <td><span class="badge-soft badge-soft-info">{{ strtoupper($setting->mail_encryption) }}</span></td>
                                <td>
                                    @if($setting->is_active)
                                        <span class="badge-soft badge-soft-success">نشط</span>
                                    @else
                                        <span class="badge-soft badge-soft-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    @if($setting->test_results)
                                        @if($setting->test_results['status'] == 'success')
                                            <span class="badge-soft badge-soft-success"><i class="ri-check-line"></i> نجح</span>
                                        @else
                                            <span class="badge-soft badge-soft-danger"><i class="ri-close-line"></i> فشل</span>
                                        @endif
                                        <small class="d-block text-muted mt-1">{{ $setting->last_tested_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted fs-12">لم يُختبر</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btn-group">
                                        <button type="button"
                                                class="action-btn action-btn--view"
                                                title="اختبار"
                                                onclick="testEmail({{ $setting->id }}, @json($setting->mail_from_address))">
                                            <i class="ri-send-plane-line"></i>
                                        </button>
                                        @if(!$setting->is_active)
                                        <form action="{{ route('admin.settings.email.activate', $setting->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="action-btn action-btn--edit" title="تفعيل">
                                                <i class="ri-check-double-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('admin.settings.email.edit', $setting->id) }}"
                                           class="action-btn action-btn--edit" title="تعديل">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        @if(!$setting->is_active)
                                        <button type="button"
                                                class="action-btn action-btn--delete"
                                                title="حذف"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteEmailModal"
                                                data-setting-id="{{ $setting->id }}"
                                                data-setting-name="{{ $providers[$setting->provider]['name'] ?? 'مخصص' }} — {{ $setting->mail_from_address }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state py-5">
                    <div class="empty-state-icon"><i class="ri-mail-settings-line"></i></div>
                    <h5 class="fw-bold mb-2">لا توجد إعدادات بريد</h5>
                    <p class="text-muted mb-3">أضف إعدادات SMTP جديدة للبدء.</p>
                    <a href="{{ route('admin.settings.email.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-line me-1"></i> إضافة إعدادات
                    </a>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@include('admin.partials.ui.modal-action')

<div class="modal fade modal-user-action" id="deleteEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteEmailForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title visually-hidden">حذف الإعدادات</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="action-modal-icon action-modal-icon--danger" aria-hidden="true">
                        <i class="ri-delete-bin-7-line"></i>
                    </div>
                    <h4 class="mb-2 fw-bold text-danger">تأكيد حذف الإعدادات</h4>
                    <p class="action-modal-user mb-2" id="deleteEmailName"></p>
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

<div class="modal fade" id="testEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">اختبار إعدادات البريد</h5>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info border-0 mb-3">
                    <i class="ri-information-line me-1"></i>
                    سيتم إرسال بريد اختباري للتأكد من صحة الإعدادات.
                </div>
                <label class="form-label fw-semibold">البريد للاختبار</label>
                <input type="email" class="form-control form-input-enhanced" id="testEmailInput" placeholder="test@example.com" dir="ltr" required>
                <input type="hidden" id="testSettingId">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="sendTestEmailBtn" onclick="sendTestEmail()">
                    <i class="ri-send-plane-line me-1"></i> إرسال اختبار
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testEmail(settingId, defaultEmail) {
    document.getElementById('testSettingId').value = settingId;
    document.getElementById('testEmailInput').value = defaultEmail;
    new bootstrap.Modal(document.getElementById('testEmailModal')).show();
}

async function sendTestEmail() {
    var settingId = document.getElementById('testSettingId').value;
    var testEmailVal = document.getElementById('testEmailInput').value;
    var btn = document.getElementById('sendTestEmailBtn');

    if (!testEmailVal) {
        alert('الرجاء إدخال بريد إلكتروني صحيح');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جاري الإرسال...';

    try {
        var response = await fetch('/admin/settings/email/' + settingId + '/test', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ test_email: testEmailVal })
        });

        var result = await response.json();

        if (result.success) {
            if (window.adminUiToast) {
                window.adminUiToast('success', result.message);
            } else {
                alert('✅ ' + result.message);
            }
            bootstrap.Modal.getInstance(document.getElementById('testEmailModal')).hide();
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('❌ حدث خطأ أثناء إرسال البريد الاختباري');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-send-plane-line me-1"></i> إرسال اختبار';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteEmailModal');
    var deleteForm = document.getElementById('deleteEmailForm');
    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('deleteEmailName').textContent = button.getAttribute('data-setting-name');
            deleteForm.action = '{{ url('/admin/settings/email') }}/' + button.getAttribute('data-setting-id');
        });
    }
});
</script>
@endpush
