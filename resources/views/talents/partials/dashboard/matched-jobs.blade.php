@if($matchedJobs->isNotEmpty())
    <div class="card custom-card talent-matched-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <div class="card-title mb-0">وظائف تناسبك</div>
                <p class="text-muted fs-12 mb-0 mt-1">مطابقة ذكية حسب مهاراتك وتخصصك</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-primary-light">المزيد</a>
        </div>
        <div class="list-group list-group-flush">
            @foreach($matchedJobs as $row)
                <a href="{{ route('jobs.show', $row['job']) }}" class="list-group-item list-group-item-action talent-matched-item">
                    <div class="talent-matched-item__body">
                        <div class="fw-semibold text-default">{{ $row['job']->title }}</div>
                        <div class="text-muted fs-12">
                            {{ $row['job']->company_name }}
                            @if($row['job']->remote_type)
                                · {{ $row['job']->remote_type }}
                            @endif
                        </div>
                    </div>
                    <div class="match-score-ring" style="--score: {{ $row['score'] }}%;">
                        <span>{{ $row['score'] }}%</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
