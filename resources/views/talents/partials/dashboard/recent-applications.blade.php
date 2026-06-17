@forelse($applications as $application)
    <tr>
        <td>
            @if($application->job)
                <a href="{{ route('jobs.show', $application->job) }}" class="fw-semibold text-default text-decoration-none">
                    {{ $application->job->title }}
                </a>
                <div class="text-muted fs-12">{{ $application->job->company_name }}</div>
            @else
                <span class="text-muted">وظيفة محذوفة</span>
            @endif
        </td>
        <td class="text-muted fs-12">{{ $application->created_at->diffForHumans() }}</td>
        <td>
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
        </td>
        <td class="text-end">
            @if($application->job)
                <a href="{{ route('jobs.show', $application->job) }}" class="btn btn-sm btn-light">عرض</a>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4">
            <div class="talent-empty-state py-4">
                <div class="talent-empty-state__icon"><i class="ri-send-plane-line"></i></div>
                <h6 class="mb-1">لم تتقدم لأي وظيفة بعد</h6>
                <p class="text-muted fs-13 mb-3">ابدأ بتصفح الوظائف المناسبة لمهاراتك وقدّم طلبك الأول.</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">
                    <i class="ri-search-line me-1"></i> تصفح الوظائف
                </a>
            </div>
        </td>
    </tr>
@endforelse
