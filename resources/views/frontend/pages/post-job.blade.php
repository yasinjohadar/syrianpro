@extends('frontend.layouts.master')

@section('title', 'إضافة وظيفة - تك سوريا')
@section('page', 'post-job')

@php $activePage = 'post-job'; @endphp

@section('content')
<div class="post-job-layout">
  <div class="breadcrumb" style="padding:0 0 20px;">
    <span onclick="goTo('index.html')">الرئيسية</span>
    <span class="sep">›</span>
    <span style="color:var(--text);">نشر وظيفة Remote</span>
  </div>
  <div class="form-card">
    <div class="form-title">📢 أضف وظيفة Remote</div>
    <div class="form-subtitle">اجذب المواهب السورية — USD · Wise · PayPal</div>

    <div class="form-section-title">المعلومات الأساسية</div>
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">المسمى الوظيفي <span>*</span></label>
        <input type="text" class="form-input" placeholder="مثال: مطور React">
      </div>
      <div class="form-group">
        <label class="form-label">التخصص <span>*</span></label>
        <select class="form-select">
          <option>Frontend</option>
          <option>Backend</option>
          <option>Mobile</option>
          <option>DevOps</option>
          <option>UI/UX</option>
          <option>Data</option>
          <option>QA</option>
          <option>Product</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">نوع العمل <span>*</span></label>
        <select class="form-select">
          <option>عن بُعد كامل</option>
          <option>هجين</option>
          <option>دوام كامل</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Timezone</label>
        <select class="form-select">
          <option>UTC+2 (سوريا)</option>
          <option>UTC+1 (أوروبا)</option>
          <option>Flexible</option>
          <option>UTC+3</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">الراتب الأدنى (USD/شهر)</label>
        <input type="number" class="form-input" placeholder="800">
      </div>
      <div class="form-group">
        <label class="form-label">الراتب الأعلى (USD/شهر)</label>
        <input type="number" class="form-input" placeholder="1500">
      </div>
      <div class="form-group">
        <label class="form-label">طرق الدفع</label>
        <select class="form-select" multiple style="height:80px;">
          <option selected>Wise</option>
          <option selected>PayPal</option>
          <option>Bank Transfer</option>
          <option>Crypto</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Syria-friendly</label>
        <select class="form-select">
          <option>نعم — نرحب بالمواهب السورية</option>
          <option>لا</option>
        </select>
      </div>
      <div class="form-group full">
        <label class="form-label">المهارات المطلوبة</label>
        <div class="tag-input-wrap" id="skills-wrap" onclick="document.getElementById('skills-input').focus()">
          <span class="tag-item">React <span class="tag-remove" onclick="removeTag(this)">✕</span></span>
          <span class="tag-item">TypeScript <span class="tag-remove" onclick="removeTag(this)">✕</span></span>
          <input type="text" id="skills-input" placeholder="أضف مهارة واضغط Enter" onkeydown="addTag(event, 'skills-wrap')">
        </div>
      </div>
    </div>

    <div class="form-section-title" style="margin-top:16px;">الوصف</div>
    <div class="form-group">
      <label class="form-label">وصف الوظيفة <span>*</span></label>
      <textarea class="form-textarea" style="min-height:120px;" placeholder="وصف الوظيفة Remote..."></textarea>
    </div>
    <div class="form-group">
      <label class="form-label">المهام والمتطلبات</label>
      <textarea class="form-textarea" placeholder="- مهمة أولى&#10;- متطلب أول..."></textarea>
    </div>

    <div class="form-actions">
      <button class="btn btn-primary btn-lg" type="button" onclick="showToast('🎉 تم نشر الوظيفة بنجاح!', 'success')">🚀 نشر الوظيفة</button>
      <button class="btn btn-outline btn-lg" type="button" onclick="showToast('💾 تم حفظ المسودة', 'success')">💾 مسودة</button>
      <button class="btn btn-outline btn-lg" type="button" onclick="window.location.href='{{ route('company.dashboard') }}'">لوحة الشركة</button>
    </div>
  </div>
</div>
@endsection
