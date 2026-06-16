@extends('frontend.layouts.master')

@section('title', 'لوحة الشركة - تك سوريا')
@section('page', 'dashboard-company')

@php $activePage = ''; @endphp

@section('content')
<div class="dashboard-layout">
  <div class="dashboard-sidebar">
    <div class="dash-nav-section">
      <div class="dash-nav-label">الرئيسي</div>
      <div class="dash-nav-item active"><span class="dash-icon">📊</span> لوحة التحكم</div>
      <div class="dash-nav-item" onclick="goTo('post-job.html')"><span class="dash-icon">➕</span> أضف وظيفة</div>
      <div class="dash-nav-item" onclick="goTo('talents.html')"><span class="dash-icon">⭐</span> قاعدة المواهب</div>
    </div>
    <div class="dash-nav-section">
      <div class="dash-nav-label">المتقدمون</div>
      <div class="dash-nav-item"><span class="dash-icon">👥</span> الطلبات <span class="dash-badge">12</span></div>
      <div class="dash-nav-item"><span class="dash-icon">⏳</span> قيد المراجعة</div>
      <div class="dash-nav-item"><span class="dash-icon">✅</span> مقبولون</div>
    </div>
    <div class="dash-nav-section">
      <div class="dash-nav-label">الشركة</div>
      <div class="dash-nav-item" onclick="goTo('company-profile.html?id=1')"><span class="dash-icon">🏢</span> صفحة الشركة</div>
      <div class="dash-nav-item"><span class="dash-icon">⚙️</span> الإعدادات</div>
    </div>
  </div>
  <div class="dashboard-main">
    <div class="dash-header">
      <h1>مرحباً، SyriaDev Studio 👋</h1>
      <p>لوحة تحكم الشركة — وظائف remote ومواهب سورية</p>
    </div>
    <div class="dash-stats-grid">
      <div class="dash-stat">
        <div class="dash-stat-icon accent">📢</div>
        <div><div class="dash-stat-num">8</div><div class="dash-stat-label">وظائف نشطة</div></div>
      </div>
      <div class="dash-stat">
        <div class="dash-stat-icon teal">📥</div>
        <div><div class="dash-stat-num">47</div><div class="dash-stat-label">طلبات جديدة</div></div>
      </div>
      <div class="dash-stat">
        <div class="dash-stat-icon gold">⭐</div>
        <div><div class="dash-stat-num">500+</div><div class="dash-stat-label">موهبة في القاعدة</div></div>
      </div>
      <div class="dash-stat">
        <div class="dash-stat-icon blue">🌐</div>
        <div><div class="dash-stat-num">100%</div><div class="dash-stat-label">Remote</div></div>
      </div>
    </div>
    <div class="dash-grid">
      <div class="dash-card">
        <div class="dash-card-header">
          <div class="dash-card-title">📥 أحدث الطلبات</div>
        </div>
        <div class="application-row">
          <div class="app-avatar">أ</div>
          <div class="app-info"><div class="app-name">أحمد الخطيب</div><div class="app-job">مطور React أول</div></div>
          <span class="app-status status-review">مراجعة</span>
        </div>
        <div class="application-row">
          <div class="app-avatar">س</div>
          <div class="app-info"><div class="app-name">سارة النجار</div><div class="app-job">مصمم UI/UX</div></div>
          <span class="app-status status-pending">انتظار</span>
        </div>
        <div class="application-row">
          <div class="app-avatar">م</div>
          <div class="app-info"><div class="app-name">محمد العيسى</div><div class="app-job">مهندس DevOps</div></div>
          <span class="app-status status-accepted">مقبول</span>
        </div>
      </div>
      <div>
        <div class="dash-card" style="margin-bottom:16px;">
          <div class="dash-card-header">
            <div class="dash-card-title">📋 وظائفي النشطة</div>
            <div class="dash-card-action" onclick="goTo('post-job.html')">+ إضافة</div>
          </div>
          <div style="display:flex; flex-direction:column; gap:12px;">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px; background:var(--surface2); border-radius:10px;">
              <div><div style="font-weight:600;">مطور React أول</div><div style="font-size:0.78rem; color:var(--text3);">عن بُعد · 12 متقدم</div></div>
              <span class="tag tag-teal">نشط</span>
            </div>
            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px; background:var(--surface2); border-radius:10px;">
              <div><div style="font-weight:600;">Backend Node.js</div><div style="font-size:0.78rem; color:var(--text3);">عن بُعد · 8 متقدمين</div></div>
              <span class="tag tag-teal">نشط</span>
            </div>
          </div>
        </div>
        <div class="dash-card">
          <div class="dash-card-header">
            <div class="dash-card-title">⭐ تصفح المواهب</div>
            <div class="dash-card-action" onclick="goTo('talents.html')">عرض الكل</div>
          </div>
          <p style="font-size:0.9rem; color:var(--text2); margin-bottom:12px;">اكتشف 500+ تقني سوري — portfolios و skills</p>
          <button class="btn btn-primary btn-full" type="button" onclick="goTo('talents.html')">قاعدة المواهب</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
