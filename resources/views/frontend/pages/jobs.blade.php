@extends('frontend.layouts.master')

@section('title', 'الوظائف - تك سوريا')
@section('page', 'jobs')

@php $activePage = 'jobs'; @endphp

@section('content')
@include('frontend.partials.listing-hero-start', [
  'eyebrow' => '💼 فرص عن بُعد · USD · Syria-friendly',
  'title' => 'وظائف <span>Remote</span>',
  'lead' => 'اكتشف <strong id="jobs-count">' . $jobs->count() . '</strong> وظيفة عن بُعد — دفع بالدولار وفرص Syria-friendly',
])
  @include('frontend.partials.listing-hero-jobs-search', ['searchQuery' => $searchQuery])
  <div class="page-header-chips">
    @include('frontend.partials.listing-hero-jobs-chips')
  </div>
@include('frontend.partials.listing-hero-end')

<div class="jobs-layout">
  <aside class="filters-panel filters-panel--pro">
    <div class="filters-panel-head">
      <div class="filters-panel-head-main">
        <span class="filters-panel-head-icon" aria-hidden="true">🎛️</span>
        <div>
          <h2 class="filters-panel-title">تصفية الوظائف</h2>
          <p class="filters-panel-sub">ابحث عن الفرصة المناسبة بسرعة</p>
        </div>
      </div>
      <button type="button" class="filter-clear-all" onclick="resetJobFilters()">مسح الكل</button>
    </div>

    <div class="filter-block">
      <div class="filter-block-head">
        <span class="filter-block-icon" aria-hidden="true">🌐</span>
        <span class="filter-block-title">Remote</span>
      </div>
      <div class="filter-check-list">
        <label class="filter-check">
          <input type="checkbox" id="filter-remote" onchange="filterJobs()">
          <span class="filter-check-mark" aria-hidden="true"></span>
          <span class="filter-check-text">عن بُعد كامل</span>
        </label>
        <label class="filter-check">
          <input type="checkbox" id="filter-syria" onchange="filterJobs()">
          <span class="filter-check-mark" aria-hidden="true"></span>
          <span class="filter-check-text">Syria-friendly 🇸🇾</span>
        </label>
      </div>
    </div>

    <div class="filter-block">
      <div class="filter-block-head">
        <span class="filter-block-icon" aria-hidden="true">💵</span>
        <span class="filter-block-title">الراتب (USD/شهر)</span>
      </div>
      <div class="filter-range-wrap">
        <input
          type="range"
          id="filter-job-salary"
          class="range-slider range-slider--pro"
          min="500"
          max="3000"
          step="100"
          value="2000"
          oninput="document.getElementById('filter-job-salary-val').textContent = Number(this.value).toLocaleString('en-US')"
        >
        <div class="filter-range-meta">
          <span>$500</span>
          <span class="filter-range-value">حتى $<strong id="filter-job-salary-val">2,000</strong>/شهر</span>
          <span>$3000+</span>
        </div>
      </div>
    </div>

    <div class="filter-block">
      <div class="filter-block-head">
        <span class="filter-block-icon" aria-hidden="true">📍</span>
        <span class="filter-block-title">الموقع</span>
      </div>
      <div class="filter-chip-group">
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-city-cb" data-city="عن بُعد" onchange="filterJobs()">
          <span>🌐 عن بُعد</span>
        </label>
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-city-cb" data-city="دمشق" onchange="filterJobs()">
          <span>دمشق</span>
        </label>
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-city-cb" data-city="حلب" onchange="filterJobs()">
          <span>حلب</span>
        </label>
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-city-cb" data-city="اللاذقية" onchange="filterJobs()">
          <span>اللاذقية</span>
        </label>
        <label class="filter-chip-toggle">
          <input type="checkbox" class="filter-city-cb" data-city="أوروبا" onchange="filterJobs()">
          <span>أوروبا</span>
        </label>
      </div>
    </div>

    <div class="filter-block filter-block--last">
      <div class="filter-block-head">
        <span class="filter-block-icon" aria-hidden="true">💳</span>
        <span class="filter-block-title">طرق الدفع</span>
      </div>
      <div class="filter-check-list">
        <label class="filter-check">
          <input type="checkbox" name="payment_wise">
          <span class="filter-check-mark" aria-hidden="true"></span>
          <span class="filter-check-text">Wise</span>
        </label>
        <label class="filter-check">
          <input type="checkbox" name="payment_paypal">
          <span class="filter-check-mark" aria-hidden="true"></span>
          <span class="filter-check-text">PayPal</span>
        </label>
        <label class="filter-check">
          <input type="checkbox" name="payment_bank">
          <span class="filter-check-mark" aria-hidden="true"></span>
          <span class="filter-check-text">Bank Transfer</span>
        </label>
      </div>
    </div>

    <div class="filters-panel-foot">
      <button class="btn btn-primary btn-full filters-panel-apply" type="button" onclick="filterJobs()">تطبيق الفلاتر</button>
    </div>
  </aside>
  <div>
    <div class="results-bar">
      <div class="results-count">عرض <strong id="jobs-count-bar">{{ $jobs->count() }}</strong> وظيفة remote</div>
    </div>
    <div class="jobs-grid" id="jobs-grid">
      @foreach($jobs as $job)
        @include('frontend.partials.job-card', ['job' => $job])
      @endforeach
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  window.__JOBS__ = @json($jobsJson);
</script>
@endsection