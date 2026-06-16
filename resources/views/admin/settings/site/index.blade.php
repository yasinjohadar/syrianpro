@extends('admin.layouts.master')

@section('page-title', 'إعدادات الموقع')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="admin-toast-container" id="adminToastContainer"></div>
        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الإعدادات'],
                ['label' => 'إعدادات الموقع'],
            ],
            'title' => 'إعدادات الموقع',
            'subtitle' => 'إدارة معلومات التواصل وروابط السوشيال ميديا المعروضة في الموقع',
        ])

        <div class="settings-layout row g-4"
             id="siteSettingsTabs"
             data-default-section="{{ config('site-settings.default', 'contact') }}"
             data-active-section="{{ $activeSection }}"
             data-section-keys='@json($sectionKeys)'>

            <div class="col-lg-3">
                <div class="settings-nav-wrap">
                    @include('admin.settings.site.partials.nav')
                </div>
            </div>

            <div class="col-lg-9">
                <form action="{{ route('admin.settings.site.update') }}" method="POST" id="siteSettingsForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_active_section" id="activeSectionInput" value="{{ $activeSection }}">

                    @foreach($sections as $id => $section)
                        <div class="settings-panel {{ $activeSection === $id ? 'is-active' : '' }}"
                             data-section="{{ $id }}"
                             role="tabpanel"
                             aria-labelledby="settings-nav-{{ $id }}">
                            <div class="settings-panel__header mb-3">
                                <h5 class="settings-panel__title mb-1">
                                    <i class="{{ $section['icon'] }} me-1 text-primary"></i>
                                    {{ $section['label'] }}
                                </h5>
                                @if(!empty($section['description']))
                                    <p class="settings-panel__desc mb-0">{{ $section['description'] }}</p>
                                @endif
                            </div>
                            @include($section['partial'])
                        </div>
                    @endforeach

                    @include('admin.settings.site.partials.actions')
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
