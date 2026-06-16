@extends('frontend.layouts.master')

@section('title', $talent->name . ' - تك سوريا')
@section('page', 'talent-profile')
@section('bodyClass', 'talent-profile-page')

@php
  $activePage = 'talents';
  $links = $talent->links ?? [];
@endphp

@section('content')
<div class="tp-cover">
  <div class="tp-cover-bg"></div>
  <div class="tp-cover-inner">
    <div class="breadcrumb tp-breadcrumb">
      <span onclick="goTo('{{ route('home') }}')">الرئيسية</span>
      <span class="sep">›</span>
      <span onclick="goTo('{{ route('talents.index') }}')">المواهب</span>
      <span class="sep">›</span>
      <span>{{ $talent->name }}</span>
    </div>
  </div>
</div>

<div class="tp-layout">
  <aside class="tp-sidebar">
    <div class="tp-card">
      <div class="tp-avatar-wrap">
        <div class="tp-avatar-ring"></div>
        <div class="tp-avatar">
          @if($talent->avatar_image)
            <img src="{{ $talent->avatarUrl() }}" alt="{{ $talent->name }}" class="tp-avatar-img">
          @else
            {{ $talent->avatar_initial }}
          @endif
        </div>
        @if($talent->is_verified)
          <span class="tp-verified">✓ موثّق</span>
        @endif
      </div>
      <h1 class="tp-name">{{ $talent->name }}</h1>
      <p class="tp-title">{{ $talent->title }}</p>

      <div class="tp-meta-row">
        <span class="tp-meta-chip"><span>📍</span> <span>{{ $talent->city }}</span></span>
        @if($talent->is_remote)
          <span class="remote-badge">عن بُعد</span>
        @endif
      </div>

      <div class="tp-stats">
        <div class="tp-stat">
          <span class="tp-stat-icon">💵</span>
          <div>
            <span class="tp-stat-val">
              @if($talent->rate_min && $talent->rate_max)
                <span dir="ltr" class="tp-ltr-val">${{ number_format($talent->rate_min) }} – ${{ number_format($talent->rate_max) }}</span><span class="tp-rate-unit">/ساعة</span>
              @else
                —
              @endif
            </span>
            <span class="tp-stat-lbl">المعدل</span>
          </div>
        </div>
        <div class="tp-stat">
          <span class="tp-stat-icon">⏱</span>
          <div>
            <span class="tp-stat-val tp-stat-val--sm">{{ $talent->availability }}</span>
            <span class="tp-stat-lbl">التوفر</span>
          </div>
        </div>
      </div>

      <div class="tp-actions">
        <button class="btn btn-primary btn-full tp-btn-contact" type="button" onclick="contactTalent()">
          <span>📧</span> تواصل الآن
        </button>
        <button class="btn btn-outline btn-full" type="button" onclick="goTo('{{ route('edit-profile') }}')">
          <span>✏️</span> تعديل الملف
        </button>
      </div>

      <div class="tp-divider"></div>

      <div class="tp-skills-label">المهارات</div>
      <div class="tp-skills">
        @foreach($talent->skills ?? [] as $skill)
          <span class="tp-skill">{{ $skill }}</span>
        @endforeach
      </div>

      <div class="tp-divider"></div>

      <div class="tp-social">
        @if(!empty($links['github']))
          <a class="tp-social-btn" href="{{ $links['github'] }}" target="_blank">🐙 GitHub</a>
        @endif
        @if(!empty($links['linkedin']))
          <a class="tp-social-btn" href="{{ $links['linkedin'] }}" target="_blank">in LinkedIn</a>
        @endif
        @if(!empty($links['portfolio']))
          <a class="tp-social-btn" href="{{ $links['portfolio'] }}" target="_blank">🌐 Portfolio</a>
        @endif
      </div>
    </div>
  </aside>

  <main class="tp-main">
    @if($talent->bio)
    <section class="tp-panel">
      <div class="tp-panel-head">
        <span class="tp-panel-icon">👋</span>
        <h2>نبذة عني</h2>
      </div>
      <div class="tp-panel-body">
        <p class="tp-bio">{{ $talent->bio }}</p>
      </div>
    </section>
    @endif

    @if(!empty($talent->experience))
    <section class="tp-panel">
      <div class="tp-panel-head">
        <span class="tp-panel-icon">💼</span>
        <h2>الخبرة المهنية</h2>
      </div>
      <div class="tp-panel-body">
        <div class="tp-timeline">
          @foreach($talent->experience as $index => $exp)
            <div class="tp-timeline-item">
              <div class="tp-timeline-dot{{ $index === 0 ? ' active' : '' }}"></div>
              <div class="tp-timeline-content">
                <div class="tp-timeline-role">{{ $exp['role'] ?? '' }}</div>
                <div class="tp-timeline-company">{{ $exp['company'] ?? '' }}</div>
                <div class="tp-timeline-years">{{ $exp['years'] ?? '' }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </section>
    @endif

    @if(!empty($talent->projects))
    <section class="tp-panel tp-panel--projects">
      <div class="tp-panel-head">
        <span class="tp-panel-icon">🎨</span>
        <h2>معرض الأعمال</h2>
        <span class="tp-project-count">{{ count($talent->projects) }} مشروع</span>
      </div>
      <div class="tp-panel-body">
        <div class="tp-projects">
          @foreach($talent->projects as $project)
            <article class="project-card project-card--lux">
              <div class="project-image project-image--lux">{{ $project['image'] ?? '💼' }}</div>
              <div class="project-body">
                <h4 class="project-title">{{ $project['title'] ?? '' }}</h4>
                <p class="project-desc">{{ $project['desc'] ?? '' }}</p>
                <div class="skill-tags project-tags">
                  @foreach($project['tags'] ?? [] as $tag)
                    <span class="skill-tag">{{ $tag }}</span>
                  @endforeach
                </div>
                <div class="project-links">
                  @if(!empty($project['demoUrl']))
                    <a href="{{ $project['demoUrl'] }}" class="btn btn-primary btn-sm" onclick="event.stopPropagation()">🔗 معاينة</a>
                  @endif
                  @if(!empty($project['githubUrl']))
                    <a href="{{ $project['githubUrl'] }}" class="btn btn-outline btn-sm" onclick="event.stopPropagation()">🐙 GitHub</a>
                  @endif
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>
    @endif
  </main>
</div>
@endsection
