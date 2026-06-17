@extends('frontend.layouts.master')

@section('title', 'المواهب - تك سوريا')
@section('page', 'talents')

@php $activePage = 'talents'; @endphp

@section('content')
@include('frontend.partials.listing-hero-start', [
  'eyebrow' => '⭐ مواهب تقنية · Remote-ready',
  'title' => 'دليل <span>المواهب</span> التقنية',
  'lead' => 'تصفّح <strong id="talents-count">' . $talents->count() . '</strong> تقني سوري — skills، portfolios، availability',
])
  <div class="search-bar">
    <div class="ac-wrap">
      <div class="search-input-wrap">
        <span class="icon">🔍</span>
        <input type="text" placeholder="التخصص: React، DevOps..." id="talents-search" data-ac="specialty" autocomplete="off" value="{{ $searchQuery }}">
        <button type="button" class="ac-chevron" tabindex="-1" aria-hidden="true">▾</button>
      </div>
      <div class="ac-menu" dir="rtl"></div>
    </div>
    <div class="ac-wrap ac-wrap--narrow">
      <div class="search-input-wrap">
        <span class="icon">📍</span>
        <input type="text" placeholder="المدينة: دمشق، حلب..." id="talents-city" data-ac="city" autocomplete="off">
        <button type="button" class="ac-chevron" tabindex="-1" aria-hidden="true">▾</button>
      </div>
      <div class="ac-menu" dir="rtl"></div>
    </div>
    <button class="btn btn-primary page-header-search-btn" type="button" onclick="filterTalents()">بحث</button>
    <button class="btn btn-outline page-header-outline-btn" type="button" onclick="goTo('{{ route('edit-profile') }}')">+ أنشئ ملفك</button>
  </div>
  <div class="page-header-chips">
    <div class="chip selected">الكل</div>
    <div class="chip">Frontend</div>
    <div class="chip">Backend</div>
    <div class="chip">Mobile</div>
    <div class="chip">DevOps</div>
    <div class="chip">UI/UX</div>
  </div>
@include('frontend.partials.listing-hero-end')

<div class="jobs-layout">
  <aside class="filters-panel filters-panel--pro">
    <div class="filters-panel-head">
      <div class="filters-panel-head-main">
        <span class="filters-panel-head-icon" aria-hidden="true">🎛️</span>
        <div>
          <h2 class="filters-panel-title">تصفية المواهب</h2>
          <p class="filters-panel-sub">ضيّق النتائج حسب تفضيلاتك</p>
        </div>
      </div>
      <button type="button" class="filter-clear-all" onclick="resetTalentFilters()">مسح الكل</button>
    </div>

    <div class="filter-block">
      <div class="filter-block-head">
        <span class="filter-block-icon" aria-hidden="true">⏱️</span>
        <span class="filter-block-title">التوفر</span>
      </div>
      <div class="filter-check-list">
        <label class="filter-check">
          <input type="checkbox" id="filter-available" onchange="filterTalents()">
          <span class="filter-check-mark" aria-hidden="true"></span>
          <span class="filter-check-text">متاح فوراً فقط</span>
        </label>
        <label class="filter-check">
          <input type="checkbox" id="filter-talent-remote" checked onchange="filterTalents()">
          <span class="filter-check-mark" aria-hidden="true"></span>
          <span class="filter-check-text">يعمل عن بُعد</span>
        </label>
        <label class="filter-check">
          <input type="checkbox" id="filter-open-to-work" onchange="filterTalents()" {{ request('open_to_work') ? 'checked' : '' }}>
          <span class="filter-check-mark" aria-hidden="true"></span>
          <span class="filter-check-text">يبحث عن عمل فقط</span>
        </label>
      </div>
    </div>

    <div class="filter-block">
      <div class="filter-block-head">
        <span class="filter-block-icon" aria-hidden="true">📍</span>
        <span class="filter-block-title">المدينة</span>
      </div>
      <div class="filter-chip-group">
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-talent-city-cb" data-city="عن بُعد" onchange="filterTalents()">
          <span>🌐 عن بُعد</span>
        </label>
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-talent-city-cb" data-city="دمشق" onchange="filterTalents()">
          <span>دمشق</span>
        </label>
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-talent-city-cb" data-city="حلب" onchange="filterTalents()">
          <span>حلب</span>
        </label>
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-talent-city-cb" data-city="اللاذقية" onchange="filterTalents()">
          <span>اللاذقية</span>
        </label>
      </div>
    </div>

    <div class="filter-block filter-block--last">
      <div class="filter-block-head">
        <span class="filter-block-icon" aria-hidden="true">💵</span>
        <span class="filter-block-title">المعدل ($/ساعة)</span>
      </div>
      <div class="filter-range-wrap">
        <input
          type="range"
          id="filter-talent-rate"
          class="range-slider range-slider--pro"
          min="10"
          max="50"
          value="30"
          oninput="document.getElementById('filter-talent-rate-val').textContent = this.value"
        >
        <div class="filter-range-meta">
          <span>$10</span>
          <span class="filter-range-value">حتى $<strong id="filter-talent-rate-val">30</strong>/ساعة</span>
          <span>$50+</span>
        </div>
      </div>
    </div>

    <div class="filters-panel-foot">
      <button class="btn btn-primary btn-full filters-panel-apply" type="button" onclick="filterTalents()">تطبيق الفلاتر</button>
    </div>
  </aside>
  <div>
    <div class="results-bar">
      <div class="results-count">قاعدة المواهب — <strong id="talents-count-bar">{{ $talents->count() }}</strong> تقني</div>
    </div>
    <div class="talents-grid jobs-grid" id="talents-grid">
      @foreach($talents as $talent)
        @include('frontend.partials.talent-card', ['talent' => $talent])
      @endforeach
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  window.__TALENTS__ = @json($talentsJson);
</script>
@endsection
