@extends('company.layouts.master')

@section('page-title')
{{ $talent->name }}
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'قاعدة المواهب', 'url' => route('company.talents.index')],
                ['label' => $talent->name],
            ],
            'title' => $talent->name,
            'subtitle' => $talent->title,
            'actions' => '<a href="' . route('talents.show', $talent) . '" class="btn btn-primary-light btn-wave" target="_blank"><i class="ri-external-link-line me-1"></i> الصفحة العامة</a>
                <a href="' . route('company.talents.index') . '" class="btn btn-light border btn-wave ms-2"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-4">
            <div class="col-lg-4 order-lg-2">
                <div class="card custom-card form-card mb-4">
                    <div class="card-body text-center">
                        <div class="user-avatar-upload-wrap mb-3">
                            <div class="user-avatar-preview-wrap mx-auto" style="width:96px;height:96px;">
                                @if($talent->avatar_image)
                                    <img src="{{ $talent->avatarUrl() }}" alt="{{ $talent->name }}" class="user-avatar-preview rounded-circle">
                                @else
                                    <span class="user-avatar-initial d-flex align-items-center justify-content-center rounded-circle" style="width:96px;height:96px;font-size:2rem;">
                                        {{ $talent->avatar_initial }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1">
                            {{ $talent->name }}
                            @if($talent->is_verified)
                                <i class="ri-verified-badge-fill text-primary"></i>
                            @endif
                        </h5>
                        <p class="text-muted mb-3">{{ $talent->title }}</p>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                            <span class="badge-soft badge-soft-primary"><i class="ri-map-pin-line me-1"></i>{{ $talent->city }}</span>
                            @if($talent->is_remote)
                                <span class="badge-soft badge-soft-success">Remote</span>
                            @endif
                            @if($talent->is_featured)
                                <span class="badge-soft badge-soft-warning">مميز</span>
                            @endif
                            @if($talent->is_open_to_work)
                                <span class="badge-soft badge-soft-success">يبحث عن عمل</span>
                            @endif
                        </div>
                        <div class="text-start">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">المعدل</span>
                                <span class="fw-semibold">{{ $talent->rate_display }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">التوفر</span>
                                <span class="fw-semibold">{{ $talent->availability ?: '—' }}</span>
                            </div>
                            @if($talent->techSpecialty)
                                <div class="d-flex justify-content-between py-2">
                                    <span class="text-muted">التخصص</span>
                                    <span class="fw-semibold">{{ $talent->techSpecialty->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @php $links = $talent->links ?? []; @endphp
                @if(!empty(array_filter($links)))
                    <div class="card custom-card form-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-links-line me-1 text-primary"></i> الروابط</h6>
                        </div>
                        <div class="card-body d-grid gap-2">
                            @if(!empty($links['github']))
                                <a href="{{ $links['github'] }}" target="_blank" class="btn btn-light border btn-sm text-start">
                                    <i class="ri-github-fill me-1"></i> GitHub
                                </a>
                            @endif
                            @if(!empty($links['linkedin']))
                                <a href="{{ $links['linkedin'] }}" target="_blank" class="btn btn-light border btn-sm text-start">
                                    <i class="ri-linkedin-box-fill me-1"></i> LinkedIn
                                </a>
                            @endif
                            @if(!empty($links['portfolio']))
                                <a href="{{ $links['portfolio'] }}" target="_blank" class="btn btn-light border btn-sm text-start">
                                    <i class="ri-global-line me-1"></i> Portfolio
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if($company && $companyJobs->isNotEmpty())
                <div class="card custom-card form-card mb-4">
                    <div class="card-header"><h6 class="mb-0 fw-semibold fs-15"><i class="ri-mail-send-line me-1 text-primary"></i> دعوة للتقديم</h6></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('company.talents.invite', $talent) }}">
                            @csrf
                            <div class="mb-3">
                                <select name="job_listing_id" class="form-select form-input-enhanced" required>
                                    <option value="">— اختر وظيفة —</option>
                                    @foreach($companyJobs as $job)
                                        <option value="{{ $job->id }}">{{ $job->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea name="message" rows="2" class="form-control form-input-enhanced mb-3" placeholder="رسالة اختيارية..."></textarea>
                            <button type="submit" class="btn btn-primary w-100 btn-sm">إرسال دعوة</button>
                        </form>
                    </div>
                </div>
                @endif

                <div class="card custom-card form-card mb-4">
                    <div class="card-body d-flex flex-wrap gap-2">
                        @if($company)
                        <form method="POST" action="{{ route('company.talents.shortlist', $talent) }}">@csrf
                            <button type="submit" class="btn btn-sm {{ $isShortlisted ? 'btn-warning' : 'btn-primary-light' }}">
                                <i class="ri-star-line me-1"></i>{{ $isShortlisted ? 'إزالة من المختصر' : 'إضافة للمختصر' }}
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                @if($company && $notes->isNotEmpty())
                <div class="card custom-card form-card mb-4">
                    <div class="card-header"><h6 class="mb-0 fs-15">ملاحظات داخلية</h6></div>
                    <div class="card-body">
                        @foreach($notes as $note)
                            <div class="border-bottom pb-2 mb-2 fs-13 text-muted">{{ $note->message }}</div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-8 order-lg-1">
                @if($activeRequest || $pitchRequest)
                    <div class="card custom-card form-card mb-4 border-primary border-opacity-25">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-megaphone-line me-1 text-primary"></i> طلب توظيف نشط</h6>
                            @if($activeRequest)
                                <a href="{{ route('company.hiring-requests.show', $activeRequest) }}" class="btn btn-sm btn-primary-light">عرض الطلب</a>
                            @elseif($pitchRequest)
                                <a href="{{ route('company.hiring-requests.show', $pitchRequest) }}" class="btn btn-sm btn-primary-light">عرض Pitch</a>
                            @endif
                        </div>
                        <div class="card-body">
                            @php $requestCard = $pitchRequest ?: $activeRequest; @endphp
                            <h6 class="fw-bold mb-2">{{ $requestCard->headline }}</h6>
                            <p class="text-muted mb-3">{{ \Illuminate\Support\Str::limit($requestCard->cover_message, 200) }}</p>
                            @if($hiringResponse)
                                <span class="badge-soft badge-soft-info">ردك: {{ $hiringResponse->statusLabel() }}</span>
                            @elseif($activeRequest)
                                <form method="POST" action="{{ route('company.hiring-requests.respond', $activeRequest) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="interested">
                                    <button type="submit" class="btn btn-primary btn-sm">أبدي اهتماماً</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                @if($talent->bio)
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-line me-1 text-primary"></i> نبذة</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 text-muted" style="line-height:1.8;">{{ $talent->bio }}</p>
                        </div>
                    </div>
                @endif

                @if(!empty($talent->skills))
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-code-box-line me-1 text-primary"></i> المهارات</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($talent->skills as $skill)
                                    <span class="badge-soft badge-soft-primary">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($talent->experience))
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-briefcase-line me-1 text-primary"></i> الخبرة</h6>
                        </div>
                        <div class="card-body">
                            @foreach($talent->experience as $exp)
                                <div class="d-flex justify-content-between align-items-start py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div>
                                        <div class="fw-semibold">{{ $exp['role'] ?? '—' }}</div>
                                        <div class="text-muted fs-12">{{ $exp['company'] ?? '' }}</div>
                                    </div>
                                    <span class="badge-soft badge-soft-secondary">{{ $exp['years'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($talent->projects))
                    <div class="card custom-card form-card">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-folder-line me-1 text-primary"></i> المشاريع</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($talent->projects as $project)
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="fs-24 mb-2">{{ $project['image'] ?? '📁' }}</div>
                                            <div class="fw-semibold mb-1">{{ $project['title'] ?? 'مشروع' }}</div>
                                            <p class="text-muted fs-12 mb-2">{{ $project['desc'] ?? '' }}</p>
                                            @if(!empty($project['tags']))
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($project['tags'] as $tag)
                                                        <span class="badge-soft badge-soft-info">{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
