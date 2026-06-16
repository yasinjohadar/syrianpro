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
      <div class="dash-nav-item" onclick="goTo('{{ route('edit-profile') }}')"><span class="dash-icon">✏️</span> تعديل الملف</div>
    </div>
    <div class="dash-nav-section">
      <div class="dash-nav-label">الوظائف</div>
      <div class="dash-nav-item" onclick="goTo('{{ route('jobs.index') }}')"><span class="dash-icon">🔍</span> تصفح الوظائف</div>
      <div class="dash-nav-item active"><span class="dash-icon">📤</span> طلباتي</div>
    </div>
  </div>
  <div class="dashboard-main">
    <div class="dash-header">
      <h1>مرحباً، <span>{{ $user->name }}</span> 👋</h1>
      <p>{{ $user->email }}</p>
    </div>

    <div class="dash-grid">
      <div class="dash-card">
        <div class="dash-card-header">
          <div class="dash-card-title">📤 طلباتي</div>
          <a class="dash-card-action" href="{{ route('jobs.index') }}">تصفح وظائف</a>
        </div>
        <div id="sd-applications" data-server-rendered="1">
          @forelse($applications as $application)
            <div class="app-item">
              <div>
                <strong>
                  @if($application->job)
                    <a href="{{ route('jobs.show', $application->job) }}" style="color:inherit; text-decoration:none;">
                      {{ $application->job->title }}
                    </a>
                  @else
                    وظيفة محذوفة
                  @endif
                </strong>
                @if($application->job)
                  — {{ $application->job->company_name }}
                @endif
              </div>
              <span class="tag tag-teal">{{ $application->statusLabel() }}</span>
              <span style="font-size:0.82rem;color:var(--text3)">{{ $application->created_at->diffForHumans() }}</span>
            </div>
          @empty
            <p style="color:var(--text3)">لم تتقدم لأي وظيفة بعد — <a href="{{ route('jobs.index') }}" style="color:var(--accent)">تصفح الوظائف</a></p>
          @endforelse
        </div>
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
