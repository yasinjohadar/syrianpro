<div class="job-card" onclick="goTo('{{ route('jobs.show', $job) }}')">
  <div class="job-card-top">
    <div class="company-logo">
      @if($job->logo_image)
        <img src="{{ $job->logoUrl() }}" alt="{{ $job->company_name }}" class="company-logo-img">
      @else
        {{ $job->logo ?? '💼' }}
      @endif
    </div>
    <div style="flex:1; margin: 0 12px;">
      <div class="job-title">
        {{ $job->title }}
        @if($job->is_new)
          <span class="badge-new">جديد</span>
        @endif
      </div>
      <div class="job-company">{{ $job->company_name }} · {{ $job->location }}</div>
    </div>
    <button class="job-save" onclick="event.stopPropagation(); toggleSaveJob({{ $job->id }}, this)">🔖</button>
  </div>
  <div class="job-tags">
    @foreach($job->tag_labels ?? [] as $tag)
      <span class="tag tag-{{ $tag['c'] ?? 'blue' }}">{{ $tag['t'] }}</span>
    @endforeach
    @if($job->is_syria_friendly)
      <span class="remote-badge">🇸🇾 Syria-friendly</span>
    @endif
  </div>
  <div class="job-meta">
    <div class="job-salary">{{ $job->salary_display }} {{ $job->currency }}/شهر</div>
    <div class="job-date">{{ $job->relative_date }}</div>
  </div>
</div>
