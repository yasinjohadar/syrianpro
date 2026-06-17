@if($publicHiringRequest)
    <div class="talent-hiring-spotlight mb-3">
        <div class="talent-hiring-spotlight__content">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                <div>
                    <span class="talent-hiring-spotlight__badge">
                        <i class="ri-megaphone-line"></i> طلبك العام نشط
                    </span>
                    <h5 class="talent-hiring-spotlight__title">{{ $publicHiringRequest->headline }}</h5>
                    <p class="talent-hiring-spotlight__meta mb-0">
                        {{ $publicHiringRequest->statusLabel() }}
                        · {{ number_format($hiringResponsesCount) }} رد من الشركات
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('talent.hiring-request.index') }}" class="btn btn-light btn-sm">
                        <i class="ri-settings-3-line me-1"></i> إدارة
                    </a>
                    <a href="{{ route('talent.hiring-request.index') }}" class="btn btn-warning btn-sm">
                        <i class="ri-refresh-line me-1"></i> تحديث الطلب
                    </a>
                </div>
            </div>
        </div>
        <div class="talent-hiring-spotlight__icon">
            <i class="ri-user-search-line"></i>
        </div>
    </div>
@else
    <div class="talent-hiring-empty mb-3">
        <div class="talent-hiring-empty__icon"><i class="ri-megaphone-line"></i></div>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-1">انشر طلب توظيف عام</h6>
            <p class="text-muted fs-13 mb-0">دع الشركات تصل إليك مباشرة دون انتظار إعلان وظيفة مناسب.</p>
        </div>
        <a href="{{ route('talent.hiring-request.index') }}" class="btn btn-primary btn-sm flex-shrink-0">
            <i class="ri-add-line me-1"></i> أنشر الآن
        </a>
    </div>
@endif
