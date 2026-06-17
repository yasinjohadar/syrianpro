@extends('frontend.layouts.master')

@section('title', 'تعديل الملف - تك سوريا')
@section('page', 'edit-profile')

@php $activePage = ''; @endphp

@section('content')
<div class="post-job-layout">
  <div class="breadcrumb" style="padding:0 0 20px;">
    <span onclick="goTo('index.html')">الرئيسية</span>
    <span class="sep">›</span>
    <span style="color:var(--text);">تعديل الملف الشخصي</span>
  </div>
  <div class="form-card">
    <div class="form-title">✏️ ملفك التقني</div>
    <div class="form-subtitle">اعرض مهاراتك ومشاريعك للشركات والعالم</div>

    <form onsubmit="saveEditProfile(event)">
      <div class="form-section-title">المعلومات الأساسية</div>
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">الاسم الكامل <span>*</span></label>
          <input type="text" class="form-input" id="ep-name" placeholder="أحمد الخطيب" required>
        </div>
        <div class="form-group">
          <label class="form-label">المسمى / التخصص <span>*</span></label>
          <input type="text" class="form-input" id="ep-title" placeholder="مطور React" required>
        </div>
        <div class="form-group">
          <label class="form-label">المدينة</label>
          <input type="text" class="form-input" id="ep-city" placeholder="دمشق">
        </div>
        <div class="form-group">
          <label class="form-label">المعدل ($/ساعة)</label>
          <input type="text" class="form-input" id="ep-rate" placeholder="20-30/ساعة">
        </div>
        <div class="form-group">
          <label class="form-label">التوفر</label>
          <select class="form-select" id="ep-availability">
            <option>متاح فوراً</option>
            <option>متاح خلال أسبوع</option>
            <option>متاح خلال شهر</option>
            <option>مشغول حالياً</option>
          </select>
        </div>
        <div class="form-group full">
          <label class="form-label">المهارات (مفصولة بفاصلة)</label>
          <input type="text" class="form-input" id="ep-skills" placeholder="React, TypeScript, Node.js">
        </div>
        <div class="form-group full">
          <label class="form-label">نبذة عنك</label>
          <textarea class="form-textarea" id="ep-bio" placeholder="اكتب نبذة قصيرة عن خبرتك وتخصصك..."></textarea>
        </div>
      </div>

      <div class="form-section-title" style="margin-top:24px;">معرض المشاريع</div>
      <div id="ep-projects-list"></div>
      <button type="button" class="btn btn-outline" style="margin-bottom:24px;" onclick="addEditProject()">+ إضافة مشروع</button>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-lg">💾 حفظ الملف</button>
        <button type="button" class="btn btn-outline btn-lg" onclick="goTo('talent-profile.html?id=99')">معاينة الملف</button>
        <button type="button" class="btn btn-outline btn-lg" onclick="window.location.href='{{ route('talent.dashboard') }}'">لوحتي</button>
      </div>
    </form>
  </div>
</div>
@endsection
