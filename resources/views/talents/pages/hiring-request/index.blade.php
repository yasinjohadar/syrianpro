@extends('talents.layouts.master')

@section('page-title')
أبحث عن عمل
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التقني', 'url' => route('talent.dashboard')],
                ['label' => 'أبحث عن عمل'],
            ],
            'title' => 'أبحث عن عمل',
            'subtitle' => 'انشر طلب توظيف عام أو أرسل عرضاً لشركة محددة',
            'actions' => $talent && $talent->slug
                ? '<a href="' . route('talents.show', $talent) . '" class="btn btn-primary-light btn-wave" target="_blank"><i class="ri-external-link-line me-1"></i> ملفي العام</a>'
                : '',
        ])

        @if(!$talent)
            <div class="card custom-card">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mb-3"><i class="ri-user-settings-line fs-40 text-muted"></i></div>
                    <h5 class="fw-bold mb-2">أكمل ملفك أولاً</h5>
                    <p class="text-muted mb-3">يجب ربط حسابك بملف موهبة قبل نشر طلب توظيف.</p>
                    <a href="{{ route('talent.profile.edit') }}" class="btn btn-primary">تعديل الملف</a>
                </div>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-search-line me-1 text-primary"></i> Open to Work</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('talent.hiring-request.toggle-open') }}">
                                @csrf
                                <div class="account-switch-panel">
                                    <div class="form-check form-switch mb-0">
                                        <input type="hidden" name="is_open_to_work" value="0">
                                        <input class="form-check-input" type="checkbox" name="is_open_to_work" value="1" id="is_open_to_work"
                                               {{ old('is_open_to_work', $talent->is_open_to_work) ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                        <label class="form-check-label fw-semibold" for="is_open_to_work">متاح للتوظيف</label>
                                    </div>
                                </div>
                            </form>
                            <p class="text-muted fs-12 mt-3 mb-0">عند التفعيل يظهر ملفك للشركات كمرشح نشط يبحث عن فرصة.</p>
                        </div>
                    </div>

                    <div class="card custom-card form-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-lightbulb-line me-1 text-primary"></i> نصائح</h6>
                        </div>
                        <div class="card-body fs-13 text-muted">
                            <ul class="mb-0 ps-3">
                                <li class="mb-2">الطلب العام يظهر لكل الشركات في لوحتهم.</li>
                                <li class="mb-2">Pitch يصل لشركة واحدة فقط مع رسالة مخصصة.</li>
                                <li>حدّث ملفك ومهاراتك لزيادة فرص الرد.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-lg-1">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-megaphone-line me-1 text-primary"></i> طلب توظيف عام</h6>
                        </div>
                        <div class="card-body">
                            @if($publicRequest && $publicRequest->status === 'active')
                                <div class="alert alert-success d-flex align-items-center mb-3">
                                    <i class="ri-checkbox-circle-line me-2"></i>
                                    <span>طلبك العام <strong>نشط</strong> — {{ $publicRequest->statusLabel() }}</span>
                                </div>
                            @elseif($publicRequest && $publicRequest->status === 'hired')
                                <div class="alert alert-primary d-flex align-items-center mb-3">
                                    <i class="ri-trophy-line me-2"></i>
                                    <span>تم التوظيف — {{ $publicRequest->statusLabel() }}</span>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('talent.hiring-request.store-public') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">الدور / المسمى المطلوب <span class="text-danger">*</span></label>
                                        <input type="text" name="headline" class="form-control form-input-enhanced @error('headline') is-invalid @enderror"
                                               value="{{ old('headline', $publicRequest?->headline ?: $talent->title) }}" required>
                                        @error('headline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">رسالة التغطية</label>
                                        <textarea name="cover_message" rows="4" class="form-control form-input-enhanced" placeholder="اشرح خبرتك وما تبحث عنه...">{{ old('cover_message', $publicRequest?->cover_message ?: $talent->bio) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">نوع العمل</label>
                                        <select name="employment_type" class="form-select form-input-enhanced">
                                            @foreach($employmentTypes as $value => $label)
                                                <option value="{{ $value }}" @selected(old('employment_type', $publicRequest?->employment_type ?: 'full_time') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">تاريخ انتهاء (اختياري)</label>
                                        <input type="date" name="expires_at" class="form-control form-input-enhanced"
                                               value="{{ old('expires_at', $publicRequest?->expires_at?->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">الحد الأدنى ($/ساعة)</label>
                                        <input type="number" name="rate_min" min="0" class="form-control form-input-enhanced"
                                               value="{{ old('rate_min', $publicRequest?->rate_min ?: $talent->rate_min) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">الحد الأعلى ($/ساعة)</label>
                                        <input type="number" name="rate_max" min="0" class="form-control form-input-enhanced"
                                               value="{{ old('rate_max', $publicRequest?->rate_max ?: $talent->rate_max) }}">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" name="is_remote" value="1" id="is_remote_public"
                                                   {{ old('is_remote', $publicRequest?->is_remote ?? $talent->is_remote) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_remote_public">عن بُعد</label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex flex-wrap gap-2 justify-content-end">
                                        <button type="submit" name="publish" value="0" class="btn btn-light border">حفظ مسودة</button>
                                        <button type="submit" name="publish" value="1" class="btn btn-primary">
                                            <i class="ri-send-plane-line me-1"></i> نشر الطلب
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @if($publicRequest)
                                <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                                    @if($publicRequest->status === 'active')
                                        <form method="POST" action="{{ route('talent.hiring-request.pause', $publicRequest) }}">@csrf<button type="submit" class="btn btn-sm btn-warning-light">إيقاف مؤقت</button></form>
                                    @elseif(in_array($publicRequest->status, ['paused', 'draft']))
                                        <form method="POST" action="{{ route('talent.hiring-request.resume', $publicRequest) }}">@csrf<button type="submit" class="btn btn-sm btn-success-light">استئناف</button></form>
                                    @endif
                                    @if(!in_array($publicRequest->status, ['closed', 'hired']))
                                        <form method="POST" action="{{ route('talent.hiring-request.close', $publicRequest) }}">@csrf<button type="submit" class="btn btn-sm btn-light border">إغلاق</button></form>
                                        <form method="POST" action="{{ route('talent.hiring-request.mark-hired', $publicRequest) }}" onsubmit="return confirm('تأكيد أنك حصلت على فرصة عمل؟')">@csrf<button type="submit" class="btn btn-sm btn-primary">تم التوظيف</button></form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-building-line me-1 text-primary"></i> Pitch لشركة محددة</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('talent.hiring-request.store-pitch') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الشركة <span class="text-danger">*</span></label>
                                        <select name="company_id" class="form-select form-input-enhanced @error('company_id') is-invalid @enderror" required>
                                            <option value="">— اختر شركة —</option>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>
                                                    {{ $company->name }} — {{ $company->sector }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">الدور المطلوب <span class="text-danger">*</span></label>
                                        <input type="text" name="headline" class="form-control form-input-enhanced @error('headline') is-invalid @enderror"
                                               value="{{ old('headline', $talent->title) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">رسالة مخصصة</label>
                                        <textarea name="cover_message" rows="3" class="form-control form-input-enhanced" placeholder="لماذا تريد الانضمام لهذه الشركة؟">{{ old('cover_message') }}</textarea>
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-mail-send-line me-1"></i> إرسال Pitch
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($pitches->isNotEmpty())
                    <div class="card custom-card mb-4">
                        <div class="card-header"><h6 class="card-title mb-0">عروضي الموجّهة</h6></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table data-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>الشركة</th>
                                            <th>العنوان</th>
                                            <th>الحالة</th>
                                            <th>التاريخ</th>
                                            <th>إجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pitches as $pitch)
                                            <tr>
                                                <td class="fw-semibold">{{ $pitch->company?->name ?? '—' }}</td>
                                                <td>{{ $pitch->headline }}</td>
                                                <td><span class="badge-soft badge-soft-primary">{{ $pitch->statusLabel() }}</span></td>
                                                <td class="text-muted fs-12">{{ $pitch->created_at->diffForHumans() }}</td>
                                                <td>
                                                    @if($pitch->status === 'active')
                                                        <form method="POST" action="{{ route('talent.hiring-request.pause', $pitch) }}" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-light border">إيقاف</button></form>
                                                    @elseif(in_array($pitch->status, ['paused', 'draft']))
                                                        <form method="POST" action="{{ route('talent.hiring-request.resume', $pitch) }}" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-primary-light">استئناف</button></form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($responses->isNotEmpty())
                    <div class="card custom-card">
                        <div class="card-header"><h6 class="card-title mb-0">ردود الشركات</h6></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table data-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>الشركة</th>
                                            <th>الحالة</th>
                                            <th>الرسالة</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($responses as $response)
                                            <tr>
                                                <td class="fw-semibold">{{ $response->company?->name ?? '—' }}</td>
                                                <td>
                                                    @php $sc = match($response->status) { 'contacted' => 'success', 'declined' => 'secondary', default => 'info' }; @endphp
                                                    <span class="badge-soft badge-soft-{{ $sc }}">{{ $response->statusLabel() }}</span>
                                                </td>
                                                <td class="text-muted fs-12">{{ $response->message ?: '—' }}</td>
                                                <td class="text-muted fs-12">{{ $response->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@stop
