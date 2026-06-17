<a href="{{ route('companies.show', $company) }}" class="fco-card">
  <div class="fco-card__header">
    <div class="fco-card__header-bg"></div>
    <div class="fco-card__rating" title="التقييم">
      <span class="fco-card__rating-star">★</span>
      {{ $company->rating_display }}
    </div>
    <div class="fco-card__logo">
      @if($company->logo_image)
        <img src="{{ $company->logoUrl() }}" alt="{{ $company->name }}" class="fco-card__logo-img">
      @else
        <span class="fco-card__logo-emoji">{{ $company->logo ?? '🏢' }}</span>
      @endif
    </div>
  </div>

  <div class="fco-card__body">
    <h3 class="fco-card__name">{{ $company->name }}</h3>
    <p class="fco-card__meta">{{ $company->sector }} · {{ $company->location }}</p>

    <div class="fco-card__chips">
      @if($company->is_remote_friendly)
        <span class="fco-card__chip fco-card__chip--teal">Remote-friendly</span>
      @endif
      @if($company->is_verified)
        <span class="fco-card__chip fco-card__chip--gold">موثّقة</span>
      @endif
      <span class="fco-card__chip fco-card__chip--muted">{{ $company->sector }}</span>
    </div>

    <div class="fco-card__stats">
      <div class="fco-card__jobs">
        <span class="fco-card__jobs-count">{{ $company->jobs_count }}</span>
        <span class="fco-card__jobs-label">وظيفة متاحة</span>
      </div>
      @if($company->is_syria_friendly)
        <span class="fco-card__syria">Syria-friendly</span>
      @endif
    </div>

    @if($company->team_size)
      <p class="fco-card__team">{{ $company->team_size }}</p>
    @endif

    <span class="fco-card__cta">عرض الشركة ←</span>
  </div>
</a>
