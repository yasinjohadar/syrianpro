@php
  $skills = $talent->skills ?? [];
  $visibleSkills = array_slice($skills, 0, 3);
  $extraSkills = max(0, count($skills) - count($visibleSkills));
  $hiringRequest = $talent->relationLoaded('activePublicHiringRequest')
    ? $talent->activePublicHiringRequest
    : null;
@endphp

<a href="{{ route('talents.show', $talent) }}" class="talent-card">
  @if(!empty($recommendationReason))
    <div class="talent-card__recommend">{{ $recommendationReason }}</div>
  @endif

  <div class="talent-card__header">
    <div class="talent-card__avatar-wrap">
      <div class="talent-card__avatar-ring"></div>
      <div class="talent-card__avatar">
        @if($talent->avatar_image)
          <img src="{{ $talent->avatarUrl() }}" alt="{{ $talent->name }}" class="talent-card__avatar-img">
        @else
          {{ $talent->avatar_initial }}
        @endif
      </div>
    </div>
    <div class="talent-card__identity">
      <div class="talent-card__name-row">
        <h3 class="talent-card__name">{{ $talent->name }}</h3>
        @if($talent->is_featured)
          <span class="talent-card__featured">مميز</span>
        @endif
      </div>
      <p class="talent-card__title">{{ $talent->title }}</p>
      @if($talent->techSpecialty)
        <p class="talent-card__specialty">{{ $talent->techSpecialty->name }}</p>
      @endif
    </div>
  </div>

  <div class="talent-card__badges">
    @if($talent->city)
      <span class="talent-card__chip">{{ $talent->city }}</span>
    @endif
    @if($talent->is_remote)
      <span class="talent-card__chip talent-card__chip--teal">عن بُعد</span>
    @endif
    @if($talent->is_open_to_work || $hiringRequest)
      <span class="talent-card__chip talent-card__chip--green">يبحث عن عمل</span>
    @endif
    @if($talent->is_verified)
      <span class="talent-card__chip talent-card__chip--gold">موثّق</span>
    @endif
  </div>

  @if(count($visibleSkills) > 0)
    <div class="talent-card__skills">
      @foreach($visibleSkills as $skill)
        <span class="talent-card__skill">{{ $skill }}</span>
      @endforeach
      @if($extraSkills > 0)
        <span class="talent-card__skill talent-card__skill--more">+{{ $extraSkills }}</span>
      @endif
    </div>
  @endif

  @if($hiringRequest?->headline)
    <p class="talent-card__hiring">
      يبحث عن: {{ \Illuminate\Support\Str::limit($hiringRequest->headline, 48) }}
    </p>
  @endif

  <div class="talent-card__footer">
    <div class="talent-card__rate">
      @if($talent->rate_min && $talent->rate_max)
        <span dir="ltr" class="tp-ltr-val">${{ number_format($talent->rate_min) }} – ${{ number_format($talent->rate_max) }}</span><span class="tp-rate-unit">/ساعة</span>
      @else
        <span class="talent-card__rate-na">—</span>
      @endif
    </div>
    @if($talent->availability)
      <div class="talent-card__availability">{{ $talent->availability }}</div>
    @endif
  </div>

  <span class="talent-card__cta">عرض الملف ←</span>
</a>
