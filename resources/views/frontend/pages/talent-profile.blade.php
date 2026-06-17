@extends('frontend.layouts.master')

@section('title', $talent->name . ' - تك سوريا')
@section('page', 'talent-profile')
@section('bodyClass', 'talent-profile-page')

@php
  $activePage = 'talents';
  $contactEmails = $talent->resolvedContactEmails();
  $contactWebsites = $talent->resolvedContactWebsites();
  $socialLinks = $talent->resolvedSocialLinks();
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
      <div class="tp-card-top" aria-hidden="true"></div>
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
        @if($talent->is_open_to_work)
          <span class="remote-badge" style="background:var(--accent-bg); color:var(--accent);">يبحث عن عمل</span>
        @endif
      </div>

      @if($talent->activePublicHiringRequest)
      <div class="tp-hiring-request" style="margin-top:16px;padding:14px;border-radius:12px;background:var(--surface2,#f8fafc);border:1px solid var(--border,#e2e8f0);">
        <div style="font-weight:700;font-size:0.9rem;margin-bottom:6px;">يبحث عن: {{ $talent->activePublicHiringRequest->headline }}</div>
        @if($talent->activePublicHiringRequest->cover_message)
          <p style="font-size:0.85rem;color:var(--text3);margin:0;line-height:1.6;">{{ \Illuminate\Support\Str::limit($talent->activePublicHiringRequest->cover_message, 160) }}</p>
        @endif
      </div>
      @endif

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
        <button class="btn btn-outline btn-full" type="button" onclick="goTo('{{ route('talent.profile.edit') }}')">
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
        @foreach($contactEmails as $email)
          @if(!empty($email['email']))
            <a class="tp-social-btn" href="mailto:{{ $email['email'] }}">
              @include('partials.contact-channel-icon', ['channel' => 'email', 'variant' => 'emoji', 'wrapperClass' => 'me-1'])
              {{ $email['label'] ?: $email['email'] }}
            </a>
          @endif
        @endforeach
        @foreach($contactWebsites as $site)
          @if(!empty($site['url']))
            <a class="tp-social-btn" href="{{ $talent->externalUrl($site['url']) }}" target="_blank" rel="noopener">
              @include('partials.contact-channel-icon', ['channel' => 'website', 'variant' => 'emoji', 'wrapperClass' => 'me-1'])
              {{ $site['label'] ?: $site['url'] }}
            </a>
          @endif
        @endforeach
        @foreach($socialLinks as $link)
          @if(!empty($link['url']))
            <a class="tp-social-btn" href="{{ $talent->externalUrl($link['url']) }}" target="_blank" rel="noopener">
              @include('partials.contact-channel-icon', ['channel' => 'social', 'platform' => $link['platform'] ?? 'other', 'variant' => 'emoji', 'wrapperClass' => 'me-1'])
              {{ $link['label'] ?? $talent->socialPlatformLabel($link['platform'] ?? null) }}
            </a>
          @endif
        @endforeach
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
