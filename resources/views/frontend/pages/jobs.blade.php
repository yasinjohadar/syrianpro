@extends('frontend.layouts.master')

@section('title', 'الوظائف - تك سوريا')
@section('page', 'jobs')

@php $activePage = 'jobs'; @endphp

@section('content')
<div class="page-header">
  <div class="page-header-inner">
    <h1>🔍 وظائف Remote</h1>
    <p>اكتشف <strong id="jobs-count">{{ $jobs->count() }}</strong> وظيفة عن بُعد — USD · Syria-friendly</p>
    <div class="search-bar">
      <div class="ac-wrap">
        <div class="search-input-wrap">
          <span class="icon">🔍</span>
          <input type="text" placeholder="التخصص: React، DevOps..." id="jobs-search" data-ac="specialty" autocomplete="off" value="{{ $searchQuery }}">
          <button type="button" class="ac-chevron" tabindex="-1" aria-hidden="true">▾</button>
        </div>
        <div class="ac-menu" dir="rtl"></div>
      </div>
      <div class="ac-wrap ac-wrap--narrow">
        <div class="search-input-wrap">
          <span class="icon">📍</span>
          <input type="text" placeholder="المدينة: دمشق، حلب..." id="jobs-city" data-ac="city" autocomplete="off">
          <button type="button" class="ac-chevron" tabindex="-1" aria-hidden="true">▾</button>
        </div>
        <div class="ac-menu" dir="rtl"></div>
      </div>
      <button class="btn btn-primary" type="button" onclick="filterJobs()">بحث</button>
      <select class="sort-select">
        <option>الأحدث أولاً</option>
        <option>الأعلى راتباً</option>
      </select>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:14px;">
      <div class="chip selected">الكل</div>
      <div class="chip">عن بُعد 🌐</div>
      <div class="chip">Frontend</div>
      <div class="chip">Backend</div>
      <div class="chip">DevOps</div>
      <div class="chip">تصميم</div>
    </div>
  </div>
</div>

<div class="jobs-layout">
  <aside class="filters-panel">
    <div style="font-weight:800; font-size:1rem; margin-bottom:20px;">🎛️ تصفية</div>
    <div class="filter-section">
      <div class="filter-title">Remote</div>
      <label class="filter-option"><input type="checkbox" id="filter-remote" onchange="filterJobs()"> عن بُعد كامل</label>
      <label class="filter-option"><input type="checkbox" id="filter-syria" onchange="filterJobs()"> Syria-friendly 🇸🇾</label>
    </div>
    <div class="filter-section">
      <div class="filter-title">الراتب (USD/شهر)</div>
      <input type="range" class="range-slider" min="500" max="3000" value="2000">
      <div class="range-labels"><span>$500</span><span>حتى $2000</span><span>$3000+</span></div>
    </div>
    <div class="filter-section">
      <div class="filter-title">الموقع</div>
      <label class="filter-option"><input type="checkbox" class="filter-city-cb" data-city="عن بُعد" onchange="filterJobs()"> عن بُعد</label>
      <label class="filter-option"><input type="checkbox" class="filter-city-cb" data-city="دمشق" onchange="filterJobs()"> دمشق</label>
      <label class="filter-option"><input type="checkbox" class="filter-city-cb" data-city="حلب" onchange="filterJobs()"> حلب</label>
      <label class="filter-option"><input type="checkbox" class="filter-city-cb" data-city="اللاذقية" onchange="filterJobs()"> اللاذقية</label>
      <label class="filter-option"><input type="checkbox" class="filter-city-cb" data-city="أوروبا" onchange="filterJobs()"> أوروبا</label>
    </div>
    <div class="filter-section">
      <div class="filter-title">طرق الدفع</div>
      <label class="filter-option"><input type="checkbox"> Wise</label>
      <label class="filter-option"><input type="checkbox"> PayPal</label>
      <label class="filter-option"><input type="checkbox"> Bank Transfer</label>
    </div>
    <button class="btn btn-primary btn-full" type="button" onclick="filterJobs()">تطبيق الفلاتر</button>
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