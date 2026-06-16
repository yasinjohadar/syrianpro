<div class="job-card" onclick="goTo('{{ route('talents.show', $talent) }}')">
  <div class="job-card-top">
    <div class="company-logo">
      @if($talent->avatar_image)
        <img src="{{ $talent->avatarUrl() }}" alt="{{ $talent->name }}" class="company-logo-img company-logo-img--round">
      @else
        {{ $talent->avatar_initial }}
      @endif
    </div>
    <div style="flex:1; margin: 0 12px;">
      <div class="job-title">{{ $talent->name }}</div>
      <div class="job-company">{{ $talent->title }}</div>
    </div>
  </div>
  <div class="job-tags">
    @if($talent->is_remote)
      <span class="tag tag-teal">عن بُعد 🌐</span>
    @endif
    <span class="tag tag-blue">📍 {{ $talent->city }}</span>
    @if($talent->is_verified)
      <span class="tag tag-gold">موثّق ✓</span>
    @endif
    @foreach(array_slice($talent->skills ?? [], 0, 3) as $skill)
      <span class="tag tag-gray">{{ $skill }}</span>
    @endforeach
  </div>
  <div class="job-meta">
    <div class="job-salary">
      @if($talent->rate_min && $talent->rate_max)
        <span dir="ltr" class="tp-ltr-val">${{ number_format($talent->rate_min) }} – ${{ number_format($talent->rate_max) }}</span><span class="tp-rate-unit">/ساعة</span>
      @else
        —
      @endif
    </div>
    <div class="job-date">{{ $talent->availability }}</div>
  </div>
</div>
