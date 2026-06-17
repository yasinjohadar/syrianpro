@extends('frontend.layouts.master')

@section('title', $job->title . ' - تك سوريا')
@section('page', 'job-detail')
@section('bodyClass', 'job-detail-page')

@php
  $activePage = 'jobs';
  $remoteLabel = match ($job->remote_type) {
    'full-remote' => 'عن بُعد',
    'hybrid' => 'هجين',
    default => $job->remote_type,
  };
@endphp

@section('content')
<div class="jd-cover">
  <div class="jd-cover-bg" aria-hidden="true">
    <div class="jd-cover-orb jd-cover-orb-1"></div>
    <div class="jd-cover-orb jd-cover-orb-2"></div>
  </div>
  <div class="jd-cover-inner">
    <nav class="jd-breadcrumb" aria-label="مسار التنقل">
      <span onclick="goTo('{{ route('home') }}')">الرئيسية</span>
      <span class="sep">›</span>
      <span onclick="goTo('{{ route('jobs.index') }}')">الوظائف</span>
      <span class="sep">›</span>
      <span>{{ $job->title }}</span>
    </nav>

    <div class="jd-hero">
      <div class="jd-hero-logo-wrap">
        <div class="jd-hero-logo-ring"></div>
        <div class="jd-hero-logo">
          @if($job->logo_image)
            <img src="{{ $job->logoUrl() }}" alt="{{ $job->company_name }}" class="jd-hero-logo-img">
          @else
            {{ $job->logo ?? '💼' }}
          @endif
        </div>
      </div>
      <div class="jd-hero-body">
        @if($job->is_new)
          <span class="jd-hero-badge">جديد</span>
        @endif
        <h1 class="jd-hero-title">{{ $job->title }}</h1>
        <p class="jd-hero-company">{{ $job->company_name }}</p>
        <div class="jd-hero-chips">
          <span class="jd-chip"><span class="jd-chip-icon">💰</span><span dir="ltr">{{ $job->salary_display }} {{ $job->currency }}/شهر</span></span>
          <span class="jd-chip"><span class="jd-chip-icon">📍</span><span>{{ $job->location }}</span></span>
          <span class="jd-chip"><span class="jd-chip-icon">💼</span><span>{{ $job->employment_type }}</span></span>
          @if($job->relative_date)
            <span class="jd-chip jd-chip--muted"><span class="jd-chip-icon">🕐</span><span>{{ $job->relative_date }}</span></span>
          @endif
        </div>
        <div class="jd-hero-tags">
          @if($job->remote_type)
            <span class="jd-hero-tag jd-hero-tag--remote">{{ $remoteLabel }}</span>
          @endif
          @if($job->is_syria_friendly)
            <span class="jd-hero-tag jd-hero-tag--syria">Syria-friendly</span>
          @endif
          @foreach($job->tag_labels ?? [] as $tag)
            <span class="jd-hero-tag jd-hero-tag--{{ $tag['c'] ?? 'blue' }}">{{ $tag['t'] }}</span>
          @endforeach
          @if($job->techSpecialty)
            <span class="jd-hero-tag jd-hero-tag--muted">{{ $job->techSpecialty->name }}</span>
          @endif
        </div>
      </div>
      <button class="jd-save-btn job-save" type="button" onclick="toggleSaveJob({{ $job->id }}, this)" aria-label="حفظ الوظيفة">🔖</button>
    </div>
  </div>
</div>

<div class="jd-layout">
  <div class="jd-main">
    <article class="jd-content-card">
      @if($job->description)
        <section class="jd-section">
          <header class="jd-section-head">
            <span class="jd-section-icon" aria-hidden="true">📋</span>
            <h2>عن الوظيفة</h2>
          </header>
          <div class="jd-section-body">
            <p class="jd-lead">{{ $job->description }}</p>
          </div>
        </section>
      @endif

      @if(!empty($job->responsibilities))
        <section class="jd-section">
          <header class="jd-section-head">
            <span class="jd-section-icon jd-section-icon--tasks" aria-hidden="true">✅</span>
            <h2>المهام والمسؤوليات</h2>
          </header>
          <ul class="jd-checklist">
            @foreach($job->responsibilities as $item)
              <li>
                <span class="jd-checklist-mark" aria-hidden="true"></span>
                <span>{{ $item }}</span>
              </li>
            @endforeach
          </ul>
        </section>
      @endif

      @if(!empty($job->requirements))
        <section class="jd-section">
          <header class="jd-section-head">
            <span class="jd-section-icon jd-section-icon--req" aria-hidden="true">🎓</span>
            <h2>المتطلبات</h2>
          </header>
          <ul class="jd-checklist jd-checklist--req">
            @foreach($job->requirements as $item)
              <li>
                <span class="jd-checklist-mark" aria-hidden="true"></span>
                <span>{{ $item }}</span>
              </li>
            @endforeach
          </ul>
        </section>
      @endif

      @if(!empty($job->benefits))
        <section class="jd-section jd-section--last">
          <header class="jd-section-head">
            <span class="jd-section-icon jd-section-icon--benefits" aria-hidden="true">🎁</span>
            <h2>المميزات</h2>
          </header>
          <div class="jd-benefits-grid">
            @foreach($job->benefits as $item)
              <div class="jd-benefit-pill">
                <span class="jd-benefit-dot" aria-hidden="true"></span>
                <span>{{ $item }}</span>
              </div>
            @endforeach
          </div>
        </section>
      @endif

      @if(!empty($job->skills))
        <div class="jd-skills-panel">
          <header class="jd-skills-head">
            <span aria-hidden="true">🏷️</span>
            <h3>المهارات المطلوبة</h3>
          </header>
          <div class="jd-skills-tags">
            @foreach($job->skills as $skill)
              <span class="jd-skill-tag">{{ $skill }}</span>
            @endforeach
          </div>
        </div>
      @endif
    </article>
  </div>

  <aside class="jd-sidebar">
    <div class="jd-apply-card">
      <div class="jd-apply-card-glow" aria-hidden="true"></div>
      <div class="jd-apply-card-inner">
        <span class="jd-apply-eyebrow">✨ جاهز للتقديم؟</span>
        <h3>تقدّم الآن</h3>
        <p>وظيفة {{ $remoteLabel }} — دفع {{ trim($job->currency, '$') ?: 'USD' }}</p>
        @auth
          @if($hasApplied)
            <button class="jd-apply-btn jd-apply-btn--done" type="button" disabled id="job-apply-btn">{{ $applicationStatusLabel }}</button>
            <p class="jd-apply-note">تم إرسال طلبك لهذه الوظيفة</p>
          @else
            <button class="jd-apply-btn" type="button" id="job-apply-btn" onclick="applyToJob({{ $job->id }})">تقدّم الآن ←</button>
          @endif
        @else
          <a class="jd-apply-btn" href="{{ route('login', ['redirect' => url()->current()]) }}">سجّل الدخول للتقديم</a>
        @endauth
      </div>
    </div>

    <div class="jd-info-card">
      <header class="jd-info-head">
        <span aria-hidden="true">📊</span>
        <h3>معلومات الوظيفة</h3>
      </header>
      <div class="jd-info-grid">
        <div class="jd-info-tile">
          <span class="jd-info-tile-icon">💰</span>
          <span class="jd-info-tile-label">الراتب</span>
          <span class="jd-info-tile-value" dir="ltr">{{ $job->salary_display }} {{ $job->currency }}/شهر</span>
        </div>
        <div class="jd-info-tile">
          <span class="jd-info-tile-icon">📍</span>
          <span class="jd-info-tile-label">الموقع</span>
          <span class="jd-info-tile-value">{{ $job->location }}</span>
        </div>
        <div class="jd-info-tile">
          <span class="jd-info-tile-icon">💼</span>
          <span class="jd-info-tile-label">نوع الدوام</span>
          <span class="jd-info-tile-value">{{ $job->employment_type }}</span>
        </div>
        <div class="jd-info-tile">
          <span class="jd-info-tile-icon">🌐</span>
          <span class="jd-info-tile-label">نمط العمل</span>
          <span class="jd-info-tile-value">{{ $remoteLabel }}</span>
        </div>
        <div class="jd-info-tile">
          <span class="jd-info-tile-icon">🕐</span>
          <span class="jd-info-tile-label">Timezone</span>
          <span class="jd-info-tile-value">{{ $job->timezone ?? '—' }}</span>
        </div>
        <div class="jd-info-tile jd-info-tile--wide">
          <span class="jd-info-tile-icon">💳</span>
          <span class="jd-info-tile-label">طرق الدفع</span>
          <span class="jd-info-tile-value">{{ implode(' · ', $job->payment_methods ?? []) ?: '—' }}</span>
        </div>
      </div>
    </div>

    @if($job->company)
      <a href="{{ route('companies.show', $job->company) }}" class="jd-company-link">
        <span>عرض ملف الشركة</span>
        <span aria-hidden="true">←</span>
      </a>
    @endif
  </aside>
</div>
@endsection

@section('scripts')
<script>
  window.JOB_APPLY_URL = @json(route('jobs.apply', $job));
</script>
@endsection
