@php
  $tags = $job->tag_labels ?? [];
  $visibleTags = array_slice($tags, 0, 3);
  $remoteLabel = match ($job->remote_type) {
    'full-remote' => 'عن بُعد',
    'hybrid' => 'هجين',
    default => $job->remote_type,
  };
@endphp

<article class="fjob-card">
  <button
    type="button"
    class="fjob-card__save"
    aria-label="حفظ الوظيفة"
    onclick="event.stopPropagation(); toggleSaveJob({{ $job->id }}, this)"
  >🔖</button>

  <a href="{{ route('jobs.show', $job) }}" class="fjob-card__link">
    <div class="fjob-card__stripe"></div>

    <div class="fjob-card__header">
      <div class="fjob-card__logo">
        @if($job->logo_image)
          <img src="{{ $job->logoUrl() }}" alt="{{ $job->company_name }}" class="fjob-card__logo-img">
        @else
          <span class="fjob-card__logo-emoji">{{ $job->logo ?? '💼' }}</span>
        @endif
      </div>
      <div class="fjob-card__intro">
        <div class="fjob-card__title-row">
          <h3 class="fjob-card__title">{{ $job->title }}</h3>
          @if($job->is_new)
            <span class="fjob-card__badge fjob-card__badge--new">جديد</span>
          @endif
        </div>
        <p class="fjob-card__company">{{ $job->company_name }}</p>
        <p class="fjob-card__location">{{ $job->location }}</p>
      </div>
    </div>

    <div class="fjob-card__chips">
      @if($job->remote_type)
        <span class="fjob-card__chip fjob-card__chip--remote">{{ $remoteLabel }}</span>
      @endif
      @if($job->is_syria_friendly)
        <span class="fjob-card__chip fjob-card__chip--syria">Syria-friendly</span>
      @endif
      @foreach($visibleTags as $tag)
        <span class="fjob-card__chip fjob-card__chip--{{ $tag['c'] ?? 'blue' }}">{{ $tag['t'] }}</span>
      @endforeach
      @if($job->techSpecialty)
        <span class="fjob-card__chip fjob-card__chip--muted">{{ $job->techSpecialty->name }}</span>
      @endif
    </div>

    <div class="fjob-card__salary-box">
      <span class="fjob-card__salary-label">الراتب الشهري</span>
      <span class="fjob-card__salary">
        @if($job->salary_display !== '—')
          <span dir="ltr" class="tp-ltr-val">{{ $job->salary_display }}</span>
          <span class="fjob-card__salary-currency">{{ $job->currency }}/شهر</span>
        @else
          <span class="fjob-card__salary-na">غير محدد</span>
        @endif
      </span>
    </div>

    <div class="fjob-card__footer">
      <span class="fjob-card__date">{{ $job->relative_date }}</span>
      <span class="fjob-card__cta">عرض الوظيفة ←</span>
    </div>
  </a>
</article>
