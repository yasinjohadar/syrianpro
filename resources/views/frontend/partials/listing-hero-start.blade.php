@php
  $eyebrow = $eyebrow ?? '';
  $title = $title ?? '';
  $lead = $lead ?? '';
@endphp
<div class="page-header page-header--hero">
  <div class="page-header-bg" aria-hidden="true">
    <div class="page-header-orb page-header-orb-1"></div>
    <div class="page-header-orb page-header-orb-2"></div>
    <div class="page-header-grid"></div>
  </div>

  <div class="page-header-inner">
    @if($eyebrow)
      <span class="page-header-eyebrow">{{ $eyebrow }}</span>
    @endif
    <h1 class="page-header-title">{!! $title !!}</h1>
    <p class="page-header-lead">{!! $lead !!}</p>

    <div class="page-header-search-card">
