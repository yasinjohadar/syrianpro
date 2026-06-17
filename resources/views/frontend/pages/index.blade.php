@extends('frontend.layouts.master')

@section('title', 'تك سوريا — منصة المواهب التقنية السورية')
@section('page', 'home')

@php $activePage = 'home'; @endphp

@section('content')
<!-- Hero -->
<section class="hero">
  <div class="hero-bg" aria-hidden="true">
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>
    <div class="hero-grid"></div>
  </div>
  <div class="hero-inner">
    <div class="hero-content">
      <div class="hero-badge">
        <span class="hero-badge-dot"></span>
        <span>المواهب التقنية السورية — Remote-first</span>
      </div>
      <h1 class="hero-title">اعمل عن بُعد<br><span class="highlight">مع العالم</span></h1>
      <p class="hero-lead">منصة متكاملة للمبرمجين والتقنيين السوريين — اعرض أعمالك، اكتشف وظائف عن بُعد، وتواصل مع الشركات.</p>
    </div>

    <div class="hero-search-card">
      <div class="hero-search">
        <div class="ac-wrap hero-search-field">
          <div class="search-field">
            <span class="icon">🔍</span>
            <input type="text" placeholder="التخصص: React، DevOps، UI/UX..." id="hero-search-q" data-ac="specialty" autocomplete="off">
            <button type="button" class="ac-chevron" tabindex="-1" aria-hidden="true">▾</button>
          </div>
          <div class="ac-menu" dir="rtl"></div>
        </div>
        <div class="ac-wrap hero-search-field">
          <div class="search-field">
            <span class="icon">📍</span>
            <input type="text" placeholder="المدينة: دمشق، حلب، عن بُعد..." id="hero-search-city" data-ac="city" autocomplete="off">
            <button type="button" class="ac-chevron" tabindex="-1" aria-hidden="true">▾</button>
          </div>
          <div class="ac-menu" dir="rtl"></div>
        </div>
        <button class="btn btn-primary hero-search-btn" type="button" onclick="heroSearch()">
          <span>ابحث الآن</span>
          <span class="hero-search-btn-icon" aria-hidden="true">←</span>
        </button>
      </div>
    </div>

    <div class="hero-paths">
      <button class="hero-path-btn primary" type="button" onclick="goTo('{{ route('edit-profile') }}')">
        <span class="hero-path-icon">👤</span>
        <span class="hero-path-text"><strong>أنا تقني</strong><small>أنشئ ملفي</small></span>
      </button>
      <button class="hero-path-btn secondary" type="button" onclick="goTo('{{ route('companies.index') }}')">
        <span class="hero-path-icon">🏢</span>
        <span class="hero-path-text"><strong>أنا شركة</strong><small>تصفح الشركات</small></span>
      </button>
      <button class="hero-path-btn secondary" type="button" onclick="goTo('{{ route('jobs.index') }}')">
        <span class="hero-path-icon">💼</span>
        <span class="hero-path-text"><strong>تصفح الوظائف</strong><small>فرص remote</small></span>
      </button>
    </div>

    <div class="hero-stats">
      <div class="hero-stats-track">
        <div class="stat-item">
          <span class="stat-num">500<span>+</span></span>
          <span class="stat-label">تقني سوري</span>
        </div>
        <div class="stat-item">
          <span class="stat-num">120<span>+</span></span>
          <span class="stat-label">وظيفة remote</span>
        </div>
        <div class="stat-item">
          <span class="stat-num">80<span>+</span></span>
          <span class="stat-label">شركة remote-friendly</span>
        </div>
        <div class="stat-item stat-item--accent">
          <span class="stat-num">95<span>%</span></span>
          <span class="stat-label">دفع بالدولار</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Categories -->
<section class="section section-specialties">
  <div class="section-specialties-bg" aria-hidden="true">
    <div class="section-specialties-orb section-specialties-orb-1"></div>
    <div class="section-specialties-orb section-specialties-orb-2"></div>
  </div>
  <div class="section-inner">
    <div class="section-header">
      <div>
        <div class="section-eyebrow">اكتشف مجالك</div>
        <div class="section-title">تخصصات تقنية</div>
        <div class="section-subtitle">{{ $specialties->pluck('name')->join(' · ') }}</div>
      </div>
      <div class="see-all see-all--pill" onclick="goTo('{{ route('jobs.index') }}')">
        <span>كل التخصصات</span>
        <span class="see-all-icon">←</span>
      </div>
    </div>
    <div class="categories-grid">
      @forelse($specialties as $specialty)
        @php
          $catHues = [
            'Frontend' => 18, 'Backend' => 218, 'Mobile' => 192, 'DevOps' => 165,
            'UI/UX' => 328, 'Data' => 248, 'QA' => 42, 'Product' => 278,
          ];
          $catHue = $catHues[$specialty->name] ?? (($loop->index * 47) % 360);
        @endphp
        <article
          class="cat-card"
          style="--cat-delay: {{ $loop->index * 0.07 }}s; --cat-hue: {{ $catHue }}"
          role="link"
          tabindex="0"
          onclick="goTo('{{ route('jobs.index') }}?q={{ urlencode($specialty->name) }}')"
          onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); goTo('{{ route('jobs.index') }}?q={{ urlencode($specialty->name) }}'); }"
        >
          <span class="cat-card-border" aria-hidden="true"></span>
          <span class="cat-card-mesh" aria-hidden="true"></span>
          <span class="cat-card-dots" aria-hidden="true"></span>
          <span class="cat-card-no">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
          <div class="cat-card-body">
            <span class="cat-icon-ring" aria-hidden="true"></span>
            <span class="cat-icon-wrap">
              <span class="cat-icon">
                @if($specialty->image)
                  <img src="{{ $specialty->iconUrl() }}" alt="" class="cat-icon-img">
                @else
                  {{ $specialty->icon }}
                @endif
              </span>
            </span>
            <div class="cat-name">{{ $specialty->name }}</div>
            <div class="cat-count">
              <span class="cat-count-badge">
                <span class="cat-count-dot"></span>
                {{ $specialty->jobs_count_label }}
              </span>
            </div>
          </div>
          <div class="cat-card-footer">
            <span>استكشف الوظائف</span>
            <span class="cat-card-arrow">←</span>
          </div>
        </article>
      @empty
        <div class="cat-card" style="grid-column: 1 / -1; cursor: default;">
          <div class="cat-name">لا توجد تخصصات معروضة حالياً</div>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Featured Jobs -->
<section class="section" style="background: var(--surface); padding: 64px 24px;">
  <div class="section-inner">
    <div class="section-header">
      <div>
        <div class="section-title">وظائف Remote مميزة 🔥</div>
        <div class="section-subtitle">فرص عن بُعد — دفع USD — Syria-friendly</div>
      </div>
      <div class="see-all" onclick="goTo('{{ route('jobs.index') }}')">مشاهدة الكل ←</div>
    </div>
    <div class="jobs-grid" id="home-jobs-grid">
      @forelse($featuredJobs as $job)
        @include('frontend.partials.featured-job-card', ['job' => $job])
      @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
          <div class="emoji">🔥</div>
          <h3>لا توجد وظائف مميزة حالياً</h3>
          <p>تصفح جميع الفرص المتاحة عن بُعد.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Recommended by Admin -->
@if(isset($recommendedTalents) && $recommendedTalents->isNotEmpty())
<section class="section" style="background: var(--surface); padding: 64px 24px;">
  <div class="section-inner">
    <div class="section-header">
      <div>
        <div class="section-title">موصى به من تك سوريا</div>
        <div class="section-subtitle">اختيار تحريري من فريق المنصة</div>
      </div>
      <div class="see-all" onclick="goTo('{{ route('talents.index') }}')">كل المواهب ←</div>
    </div>
    <div class="talents-grid">
      @foreach($recommendedTalents as $rec)
        @if($rec->talent)
          @include('frontend.partials.talent-card', [
            'talent' => $rec->talent,
            'recommendationReason' => $rec->reason,
          ])
        @endif
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- Featured Talents -->
<section class="section">
  <div class="section-inner">
    <div class="section-header">
      <div>
        <div class="section-title">مواهب مميزة ⭐</div>
        <div class="section-subtitle">تقنيون سوريون — portfolios و skills</div>
      </div>
      <div class="see-all" onclick="goTo('{{ route('talents.index') }}')">كل المواهب ←</div>
    </div>
    <div class="talents-grid" id="home-talents-grid">
      @forelse($featuredTalents as $talent)
        @include('frontend.partials.talent-card', ['talent' => $talent])
      @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
          <div class="emoji">⭐</div>
          <h3>لا توجد مواهب مميزة حالياً</h3>
          <p>تصفح دليل المواهب لاكتشاف التقنيين السوريين.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- Top Companies -->
<section class="section" style="background: var(--surface); padding: 64px 24px;">
  <div class="section-inner">
    <div class="section-header">
      <div>
        <div class="section-title">شركات Remote-friendly 🏢</div>
        <div class="section-subtitle">محلية ودولية تستقطب المواهب السورية</div>
      </div>
      <div class="see-all" onclick="goTo('{{ route('companies.index') }}')">كل الشركات ←</div>
    </div>
    <div class="jobs-grid" id="home-companies-grid">
      @forelse($featuredCompanies as $company)
        @include('frontend.partials.featured-company-card', ['company' => $company])
      @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
          <div class="emoji">🏢</div>
          <h3>لا توجد شركات معروضة حالياً</h3>
          <p>تصفح دليل الشركات التي تستقطب المواهب السورية.</p>
        </div>
      @endforelse
    </div>
  </div>
</section>

<!-- CTA -->
<section class="home-cta">
  <div class="section-inner">
    <div class="home-cta-shell">
      <div class="home-cta-bg" aria-hidden="true">
        <div class="home-cta-orb home-cta-orb-1"></div>
        <div class="home-cta-orb home-cta-orb-2"></div>
        <div class="home-cta-orb home-cta-orb-3"></div>
        <div class="home-cta-grid-lines"></div>
      </div>

      <div class="home-cta-layout">
        <div class="home-cta-content">
          <span class="home-cta-eyebrow">
            <span class="home-cta-eyebrow-dot"></span>
            للشركات والتوظيف عن بُعد
          </span>
          <h2 class="home-cta-title">شركة تبحث عن<br><span>مواهب سورية؟</span></h2>
          <p class="home-cta-lead">تصفّح مئات التقنيين السوريين، وتواصل معنا لتوظيف بعقود B2B — دفع USD وSyria-friendly.</p>
          <div class="home-cta-actions">
            <button class="home-cta-btn home-cta-btn--primary" type="button" onclick="goTo('{{ route('contact') }}')">
              <span>تواصل معنا</span>
              <span aria-hidden="true">←</span>
            </button>
            <button class="home-cta-btn home-cta-btn--ghost" type="button" onclick="goTo('{{ route('talents.index') }}')">
              تصفح المواهب
            </button>
          </div>
        </div>

        <div class="home-cta-aside">
          <div class="home-cta-glass-card">
            <div class="home-cta-glass-icon">🚀</div>
            <div class="home-cta-stat">
              <span class="home-cta-stat-num">500<span>+</span></span>
              <span class="home-cta-stat-label">تقني سوري</span>
            </div>
          </div>
          <div class="home-cta-glass-card home-cta-glass-card--sm">
            <span class="home-cta-chip">🌐 Remote</span>
            <span class="home-cta-chip">💵 USD</span>
            <span class="home-cta-chip">🇸🇾 Syria-friendly</span>
          </div>
          <div class="home-cta-glass-card home-cta-glass-card--quote">
            <p>«منصة واحدة تجمع المواهب السورية مع شركات عالمية — بدون حدود.»</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
