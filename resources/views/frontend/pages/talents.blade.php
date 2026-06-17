@extends('frontend.layouts.master')

@section('title', 'المواهب - تك سوريا')
@section('page', 'talents')

@php $activePage = 'talents'; @endphp

@section('content')
<div class="page-header">
  <div class="page-header-inner">
    <h1>⭐ دليل المواهب التقنية</h1>
    <p>تصفّح <strong id="talents-count">{{ $talents->count() }}</strong> تقني سوري — skills، portfolios، availability</p>
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
      <button class="btn btn-primary" type="button" onclick="filterTalents()">بحث</button>
      <button class="btn btn-outline" type="button" onclick="goTo('{{ route('edit-profile') }}')">+ أنشئ ملفك</button>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:14px;">
      <div class="chip selected">الكل</div>
      <div class="chip">Frontend</div>
      <div class="chip">Backend</div>
      <div class="chip">Mobile</div>
      <div class="chip">DevOps</div>
      <div class="chip">UI/UX</div>
    </div>
  </div>
</div>

<div class="jobs-layout">
  <aside class="filters-panel">
    <div style="font-weight:800; font-size:1rem; margin-bottom:20px;">🎛️ تصفية المواهب</div>
    <div class="filter-section">
      <div class="filter-title">التوفر</div>
      <label class="filter-option"><input type="checkbox" id="filter-available" onchange="filterTalents()"> متاح فوراً فقط</label>
      <label class="filter-option"><input type="checkbox" id="filter-talent-remote" checked onchange="filterTalents()"> يعمل عن بُعد</label>
      <label class="filter-option"><input type="checkbox" id="filter-open-to-work" onchange="filterTalents()" {{ request('open_to_work') ? 'checked' : '' }}> يبحث عن عمل فقط</label>
    </div>
    <div class="filter-section">
      <div class="filter-title">المدينة</div>
      <label class="filter-option"><input type="checkbox" class="filter-talent-city-cb" data-city="عن بُعد" onchange="filterTalents()"> عن بُعد</label>
      <label class="filter-option"><input type="checkbox" class="filter-talent-city-cb" data-city="دمشق" onchange="filterTalents()"> دمشق</label>
      <label class="filter-option"><input type="checkbox" class="filter-talent-city-cb" data-city="حلب" onchange="filterTalents()"> حلب</label>
      <label class="filter-option"><input type="checkbox" class="filter-talent-city-cb" data-city="اللاذقية" onchange="filterTalents()"> اللاذقية</label>
    </div>
    <div class="filter-section">
      <div class="filter-title">المعدل ($/ساعة)</div>
      <input type="range" class="range-slider" min="10" max="50" value="30">
    </div>
    <button class="btn btn-primary btn-full" type="button" onclick="filterTalents()">تطبيق</button>
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
