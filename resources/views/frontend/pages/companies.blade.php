@extends('frontend.layouts.master')

@section('title', 'الشركات - تك سوريا')
@section('page', 'companies')

@php $activePage = 'companies'; @endphp

@section('content')
<div class="page-header">
  <div class="page-header-inner">
    <h1>🏢 شركات Remote-friendly</h1>
    <p>أكثر من <strong id="companies-count">{{ $companies->count() }}</strong> شركة تستقطب المواهب السورية عن بُعد</p>
    <div class="search-bar">
      <div class="search-input-wrap">
        <span class="icon">🔍</span>
        <input type="text" placeholder="ابحث عن شركة..." id="companies-search" value="{{ $searchQuery }}">
      </div>
      <button class="btn btn-primary" type="button" onclick="filterCompanies()">بحث</button>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:14px;">
      <div class="chip selected" data-category="">الكل</div>
      <div class="chip" data-category="tech">تقنية</div>
      <div class="chip" data-category="design">تصميم</div>
      <div class="chip" data-category="data">بيانات</div>
      <div class="chip" data-category="education">تعليم</div>
    </div>
  </div>
</div>

<div class="section-inner" style="padding: 40px 24px;">
  <div class="jobs-grid" id="companies-grid">
    @foreach($companies as $company)
      @include('frontend.partials.company-card', ['company' => $company])
    @endforeach
  </div>
</div>
@endsection

@section('scripts')
<script>
  window.__COMPANIES__ = @json($companiesJson);
</script>
@endsection
