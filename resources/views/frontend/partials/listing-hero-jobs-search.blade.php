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
  <button class="btn btn-primary page-header-search-btn" type="button" onclick="filterJobs()">بحث</button>
  <select class="sort-select">
    <option>الأحدث أولاً</option>
    <option>الأعلى راتباً</option>
  </select>
</div>
