@extends('frontend.layouts.master')

@section('title', $company->name . ' - تك سوريا')
@section('page', 'company-profile')
@section('bodyClass', 'company-profile-page')

@php
  $activePage = 'companies';
  $perkIcons = ['💵', '🌐', '📚', '🎁', '⏰', '🏥'];
@endphp

@section('content')
<div class="cp-cover">
  <div class="cp-cover-bg"></div>
  <div class="cp-cover-inner">
    <nav class="breadcrumb cp-breadcrumb" aria-label="مسار التنقل">
      <span onclick="goTo('{{ route('home') }}')">الرئيسية</span>
      <span class="sep">›</span>
      <span onclick="goTo('{{ route('companies.index') }}')">الشركات</span>
      <span class="sep">›</span>
      <span>{{ $company->name }}</span>
    </nav>

    <div class="cp-hero">
      <div class="cp-hero-logo-wrap">
        <div class="cp-hero-logo-ring"></div>
        <div class="cp-hero-logo">
          @if($company->logo_image)
            <img src="{{ $company->logoUrl() }}" alt="{{ $company->name }}" class="cp-hero-logo-img">
          @else
            {{ $company->logo ?? '🏢' }}
          @endif
        </div>
        @if($company->is_verified)
          <span class="cp-verified">✓ موثّقة</span>
        @endif
      </div>
      <div class="cp-hero-body">
        <h1 class="cp-hero-title">{{ $company->name }}</h1>
        <p class="cp-hero-sub">{{ $company->sector }} · {{ $company->location }}</p>
        <div class="cp-hero-chips">
          <span class="cp-chip"><span class="cp-chip-icon">⭐</span>{{ $company->rating_display }} / 5</span>
          <span class="cp-chip"><span class="cp-chip-icon">💼</span>{{ $company->jobs_count }} وظيفة</span>
          <span class="cp-chip"><span class="cp-chip-icon">👥</span>{{ $company->team_size ?? '—' }}</span>
          <span class="cp-chip"><span class="cp-chip-icon">📅</span>منذ {{ $company->founded ?? '—' }}</span>
        </div>
        <div class="cp-hero-tags">
          @if($company->is_remote_friendly)
            <span class="tag tag-teal">Remote-friendly 🌐</span>
          @endif
          <span class="tag tag-blue">{{ $company->sector }}</span>
          @if($company->is_verified)
            <span class="tag tag-gold">موثّقة ✓</span>
          @endif
          @if($company->is_syria_friendly)
            <span class="remote-badge">🇸🇾 Syria-friendly</span>
          @endif
        </div>
        <div class="cp-hero-actions">
          <button class="btn btn-primary cp-btn-jobs" type="button" onclick="goTo('{{ route('jobs.index') }}?q={{ urlencode($company->name) }}')">
            💼 مشاهدة الوظائف ({{ $company->jobs_count }})
          </button>
          <button class="btn btn-outline cp-btn-outline" type="button" onclick="goTo('{{ route('talents.index') }}')">👥 تصفح المواهب</button>
          @if($company->website)
            <a class="btn btn-outline cp-btn-outline" href="https://{{ $company->website }}" target="_blank" rel="noopener">🌐 {{ $company->website }}</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<div class="cp-layout">
  <main class="cp-main">
    @if($company->about)
    <section class="cp-card">
      <h2 class="cp-card-title"><span class="cp-card-icon">🏢</span> نبذة عن الشركة</h2>
      <p class="cp-about-text">{{ $company->about }}</p>
    </section>
    @endif

    @if($company->mission)
    <section class="cp-card">
      <h2 class="cp-card-title"><span class="cp-card-icon">🎯</span> رؤيتنا</h2>
      <blockquote class="cp-mission">{{ $company->mission }}</blockquote>
    </section>
    @endif

    @if(!empty($company->values))
    <section class="cp-card">
      <h2 class="cp-card-title"><span class="cp-card-icon">💎</span> قيمنا</h2>
      <div class="cp-values-grid">
        @foreach($company->values as $value)
          <div class="cp-value-item"><span class="cp-value-icon">◆</span><span>{{ $value }}</span></div>
        @endforeach
      </div>
    </section>
    @endif

    @if(!empty($company->tech_stack))
    <section class="cp-card">
      <h2 class="cp-card-title"><span class="cp-card-icon">🛠️</span> التقنيات والأدوات</h2>
      <div class="cp-tech-tags">
        @foreach($company->tech_stack as $tech)
          <span class="skill-tag">{{ $tech }}</span>
        @endforeach
      </div>
    </section>
    @endif

    @if(!empty($company->culture))
    <section class="cp-card">
      <h2 class="cp-card-title"><span class="cp-card-icon">🌱</span> ثقافة العمل</h2>
      <ul class="cp-culture-list">
        @foreach($company->culture as $item)
          <li>{{ $item }}</li>
        @endforeach
      </ul>
    </section>
    @endif

    @if(!empty($company->perks))
    <section class="cp-card">
      <h2 class="cp-card-title"><span class="cp-card-icon">🎁</span> المميزات والحوافز</h2>
      <div class="cp-perks-grid">
        @foreach($company->perks as $index => $perk)
          <div class="cp-perk">
            <span class="cp-perk-icon">{{ $perkIcons[$index % count($perkIcons)] }}</span>
            <span>{{ $perk }}</span>
          </div>
        @endforeach
      </div>
    </section>
    @endif

    <section class="cp-card">
      <h2 class="cp-card-title"><span class="cp-card-icon">💼</span> الوظائف المتاحة</h2>
      <div class="jobs-grid">
        @forelse($companyJobs as $job)
          @include('frontend.partials.job-card', ['job' => $job])
        @empty
          <p style="color:var(--text3);">لا توجد وظائف مرتبطة حالياً.</p>
        @endforelse
      </div>
    </section>
  </main>

  <aside class="cp-sidebar">
    <div class="cp-stats-grid">
      <div class="cp-stat">
        <span class="cp-stat-icon">💼</span>
        <div><span class="cp-stat-val">{{ $company->jobs_count }}</span><span class="cp-stat-lbl">وظيفة مفتوحة</span></div>
      </div>
      <div class="cp-stat">
        <span class="cp-stat-icon">🌐</span>
        <div><span class="cp-stat-val">Remote</span><span class="cp-stat-lbl">نوع التوظيف</span></div>
      </div>
      <div class="cp-stat">
        <span class="cp-stat-icon">💵</span>
        <div><span class="cp-stat-val">USD</span><span class="cp-stat-lbl">العملة</span></div>
      </div>
      <div class="cp-stat">
        <span class="cp-stat-icon">⭐</span>
        <div><span class="cp-stat-val">{{ $company->rating_display }}</span><span class="cp-stat-lbl">التقييم</span></div>
      </div>
    </div>

    <div class="cp-side-card">
      <h3>📋 معلومات سريعة</h3>
      <div class="cp-info-row"><span class="cp-info-icon">📍</span><div><span class="cp-info-lbl">الموقع</span><span class="cp-info-val">{{ $company->location }}</span></div></div>
      <div class="cp-info-row"><span class="cp-info-icon">👥</span><div><span class="cp-info-lbl">حجم الفريق</span><span class="cp-info-val">{{ $company->team_size ?? '—' }}</span></div></div>
      <div class="cp-info-row"><span class="cp-info-icon">📅</span><div><span class="cp-info-lbl">التأسيس</span><span class="cp-info-val">{{ $company->founded ?? '—' }}</span></div></div>
      <div class="cp-info-row"><span class="cp-info-icon">🕐</span><div><span class="cp-info-lbl">Timezone</span><span class="cp-info-val">{{ $company->timezone ?? '—' }}</span></div></div>
      <div class="cp-info-row"><span class="cp-info-icon">💳</span><div><span class="cp-info-lbl">طرق الدفع</span><span class="cp-info-val">{{ implode(' · ', $company->payment_methods ?? []) ?: '—' }}</span></div></div>
    </div>

    @if(!empty($company->perks))
    <div class="cp-side-card cp-side-highlight">
      <h3>🎯 لماذا تنضم إلينا؟</h3>
      <ul class="cp-why-list">
        @foreach(array_slice($company->perks, 0, 5) as $perk)
          <li><span>✓</span> {{ $perk }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <div class="cp-cta-box">
      <h3>🚀 جاهز للانضمام؟</h3>
      <p>تصفح الوظائف المتاحة أو تواصل معنا مباشرة</p>
      <button class="btn btn-primary btn-full" type="button" onclick="goTo('{{ route('jobs.index') }}?q={{ urlencode($company->name) }}')">تقدّم الآن</button>
    </div>
  </aside>
</div>
@endsection
