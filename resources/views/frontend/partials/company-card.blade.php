<div class="job-card" onclick="goTo('{{ route('companies.show', $company) }}')">
  <div class="job-card-top">
    <div class="company-logo">
      @if($company->logo_image)
        <img src="{{ $company->logoUrl() }}" alt="{{ $company->name }}" class="company-logo-img">
      @else
        {{ $company->logo ?? '🏢' }}
      @endif
    </div>
    <div style="flex:1; margin: 0 12px;">
      <div class="job-title">{{ $company->name }}</div>
      <div class="job-company">{{ $company->sector }} · {{ $company->location }}</div>
    </div>
    <div class="job-rating" title="التقييم">⭐ {{ $company->rating_display }}</div>
  </div>
  <div class="job-tags">
    @if($company->is_remote_friendly)
      <span class="tag tag-teal">Remote-friendly 🌐</span>
    @endif
    <span class="tag tag-blue">{{ $company->sector }}</span>
    @if($company->is_verified)
      <span class="tag tag-gold">موثّقة ✓</span>
    @endif
  </div>
  <div class="job-meta">
    <div class="job-salary">{{ $company->jobs_count_label }}</div>
    <div class="job-date">{{ $company->is_syria_friendly ? '🇸🇾 Syria-friendly' : '' }}</div>
  </div>
</div>
