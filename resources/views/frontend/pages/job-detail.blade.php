@extends('frontend.layouts.master')

@section('title', $job->title . ' - تك سوريا')
@section('page', 'job-detail')
@section('bodyClass', 'job-detail-page')

@php $activePage = 'jobs'; @endphp

@section('content')
<div class="jd-cover">
  <div class="jd-cover-bg"></div>
  <div class="jd-cover-inner">
    <nav class="breadcrumb jd-breadcrumb" aria-label="مسار التنقل">
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
        <h1 class="jd-hero-title">{{ $job->title }}</h1>
        <p class="jd-hero-company">{{ $job->company_name }}</p>
        <div class="jd-hero-chips">
          <span class="jd-chip"><span class="jd-chip-icon">💰</span><span>{{ $job->salary_display }} {{ $job->currency }}/شهر</span></span>
          <span class="jd-chip"><span class="jd-chip-icon">📍</span><span>{{ $job->location }}</span></span>
          <span class="jd-chip"><span class="jd-chip-icon">💼</span><span>{{ $job->employment_type }}</span></span>
        </div>
        <div class="jd-hero-tags">
          @foreach($job->tag_labels ?? [] as $tag)
            <span class="tag tag-{{ $tag['c'] ?? 'blue' }}">{{ $tag['t'] }}</span>
          @endforeach
          @if($job->is_new)
            <span class="badge-new">جديد</span>
          @endif
          @if($job->is_syria_friendly)
            <span class="remote-badge">Syria-friendly</span>
          @endif
        </div>
      </div>
      <button class="jd-save-btn job-save" type="button" onclick="toggleSave(this)" aria-label="حفظ الوظيفة">🔖</button>
    </div>
  </div>
</div>

<div class="jd-layout">
  <div class="jd-main">
    <div class="detail-main">
      <div class="detail-body">
        @if($job->description)
        <div class="detail-section">
          <h3>📋 عن الوظيفة</h3>
          <p>{{ $job->description }}</p>
        </div>
        @endif
        @if(!empty($job->responsibilities))
        <div class="detail-section">
          <h3>✅ المهام والمسؤوليات</h3>
          <ul>
            @foreach($job->responsibilities as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @if(!empty($job->requirements))
        <div class="detail-section">
          <h3>🎓 المتطلبات</h3>
          <ul>
            @foreach($job->requirements as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @if(!empty($job->benefits))
        <div class="detail-section">
          <h3>🎁 المميزات</h3>
          <ul>
            @foreach($job->benefits as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @if(!empty($job->skills))
        <div class="jd-skills-box">
          <div class="jd-skills-label">🏷️ المهارات المطلوبة</div>
          <div class="skill-tags">
            @foreach($job->skills as $skill)
              <span class="skill-tag">{{ $skill }}</span>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <aside class="jd-sidebar detail-sidebar">
    <div class="apply-box">
      <h3>✨ تقدّم الآن</h3>
      <p>وظيفة remote — دفع {{ trim($job->currency, '$') ?: 'USD' }}</p>
      @auth
        @if($hasApplied)
          <button class="btn-apply btn-apply--done" type="button" disabled id="job-apply-btn">{{ $applicationStatusLabel }}</button>
          <p class="apply-box-note">تم إرسال طلبك لهذه الوظيفة</p>
        @else
          <button class="btn-apply" type="button" id="job-apply-btn" onclick="applyToJob({{ $job->id }})">تقدم الآن</button>
        @endif
      @else
        <a class="btn-apply" href="{{ route('login', ['redirect' => url()->current()]) }}">سجّل الدخول للتقديم</a>
      @endauth
    </div>
    <div class="sidebar-card">
      <h3>📊 معلومات الوظيفة</h3>
      <div class="info-row">
        <div class="info-icon">💰</div>
        <div><div class="info-label">الراتب</div><div class="info-value" style="color:var(--teal);">{{ $job->salary_display }} {{ $job->currency }}/شهر</div></div>
      </div>
      <div class="info-row">
        <div class="info-icon">📍</div>
        <div><div class="info-label">الموقع</div><div class="info-value">{{ $job->location }}</div></div>
      </div>
      <div class="info-row">
        <div class="info-icon">💼</div>
        <div><div class="info-label">نوع الدوام</div><div class="info-value">{{ $job->employment_type }}</div></div>
      </div>
      <div class="info-row">
        <div class="info-icon">🕐</div>
        <div><div class="info-label">Timezone</div><div class="info-value">{{ $job->timezone ?? '—' }}</div></div>
      </div>
      <div class="info-row">
        <div class="info-icon">💳</div>
        <div><div class="info-label">طرق الدفع</div><div class="info-value">{{ implode(' · ', $job->payment_methods ?? []) ?: '—' }}</div></div>
      </div>
    </div>
  </aside>
</div>
@endsection

@section('scripts')
<script>
  window.JOB_APPLY_URL = @json(route('jobs.apply', $job));
</script>
@endsection
