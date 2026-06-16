@extends('frontend.layouts.master')

@section('title', 'لوحة التقني - تك سوريا')
@section('page', 'dashboard-seeker')

@php $activePage = ''; @endphp

@section('content')
<div class="dashboard-layout">
  <div class="dashboard-sidebar">
    <div class="dash-nav-section">
      <div class="dash-nav-label">حسابي</div>
      <div class="dash-nav-item active"><span class="dash-icon">📊</span> لوحة التحكم</div>
      <div class="dash-nav-item" onclick="goTo('edit-profile.html')"><span class="dash-icon">✏️</span> تعديل الملف</div>
      <div class="dash-nav-item" onclick="goTo('talent-profile.html?id=99')"><span class="dash-icon">👁️</span> الملف العام</div>
    </div>
    <div class="dash-nav-section">
      <div class="dash-nav-label">الوظائف</div>
      <div class="dash-nav-item" onclick="goTo('jobs.html')"><span class="dash-icon">🔍</span> تصفح الوظائف</div>
      <div class="dash-nav-item"><span class="dash-icon">📤</span> طلباتي</div>
      <div class="dash-nav-item"><span class="dash-icon">🔖</span> محفوظاتي</div>
    </div>
  </div>
  <div class="dashboard-main">
    <div class="dash-header">
      <h1>مرحباً، <span id="sd-name">تقني</span> 👋</h1>
      <p id="sd-title">—</p>
    </div>

    <div class="profile-card" style="position:static; margin-bottom:24px; display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
      <div class="profile-avatar" id="sd-avatar" style="margin:0;">أ</div>
      <div style="flex:1; min-width:200px;">
        <div class="profile-complete" style="margin-bottom:8px;">
          <div class="profile-complete-fill" id="sd-completion-fill" style="width:60%"></div>
        </div>
        <div class="profile-complete-text" id="sd-completion-text">اكتمال الملف: 60%</div>
      </div>
      <button class="btn btn-primary" type="button" onclick="goTo('edit-profile.html')">أكمل ملفك</button>
    </div>

    <div class="dash-grid">
      <div class="dash-card">
        <div class="dash-card-header">
          <div class="dash-card-title">📤 طلباتي</div>
          <div class="dash-card-action" onclick="goTo('jobs.html')">تصفح وظائف</div>
        </div>
        <div id="sd-applications"></div>
      </div>
      <div class="dash-card">
        <div class="dash-card-header">
          <div class="dash-card-title">🔖 وظائف محفوظة</div>
        </div>
        <div id="sd-saved-jobs"></div>
      </div>
    </div>
  </div>
</div>
@endsection
