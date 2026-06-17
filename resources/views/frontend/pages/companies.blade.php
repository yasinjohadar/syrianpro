@extends('frontend.layouts.master')

@section('title', 'الشركات - تك سوريا')
@section('page', 'companies')

@php $activePage = 'companies'; @endphp

@section('content')
@include('frontend.partials.listing-hero-start', [
  'eyebrow' => '🏢 شركات Remote-friendly',
  'title' => 'شركات <span>تستقطب</span> المواهب',
  'lead' => 'أكثر من <strong id="companies-count">' . $companies->count() . '</strong> شركة تستقطب المواهب السورية عن بُعد',
])
  <div class="search-bar">
    <div class="search-input-wrap">
      <span class="icon">🔍</span>
      <input type="text" placeholder="ابحث عن شركة..." id="companies-search" value="{{ $searchQuery }}">
    </div>
    <button class="btn btn-primary page-header-search-btn" type="button" onclick="filterCompanies()">بحث</button>
  </div>
  <div class="page-header-chips">
    <div class="chip selected" data-category="">الكل</div>
    <div class="chip" data-category="tech">تقنية</div>
    <div class="chip" data-category="design">تصميم</div>
    <div class="chip" data-category="data">بيانات</div>
    <div class="chip" data-category="education">تعليم</div>
  </div>
@include('frontend.partials.listing-hero-end')

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
