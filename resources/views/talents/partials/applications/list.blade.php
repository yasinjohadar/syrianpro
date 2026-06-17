@forelse($applications as $application)
    <div class="list-group-item d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <div class="fw-semibold">
                @if($application->job)
                    <a href="{{ route('jobs.show', $application->job) }}" class="text-default">
                        {{ $application->job->title }}
                    </a>
                @else
                    <span class="text-muted">وظيفة محذوفة</span>
                @endif
            </div>
            <div class="text-muted fs-12">
                @if($application->job)
                    {{ $application->job->company_name }} · {{ $application->created_at->diffForHumans() }}
                @else
                    {{ $application->created_at->diffForHumans() }}
                @endif
            </div>
        </div>
        @php
            $statusClass = match($application->status) {
                'accepted' => 'success',
                'rejected' => 'secondary',
                'shortlisted' => 'warning',
                'reviewing' => 'info',
                default => 'primary',
            };
        @endphp
        <span class="badge-soft badge-soft-{{ $statusClass }}">{{ $application->statusLabel() }}</span>
    </div>
@empty
    <div class="list-group-item">
        <div class="text-center py-3">
            <div class="text-muted mb-2"><i class="ri-send-plane-line fs-24"></i></div>
            <p class="text-muted mb-2 fs-13">لم تتقدم لأي وظيفة بعد</p>
            <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-primary-light">تصفح الوظائف</a>
        </div>
    </div>
@endforelse
