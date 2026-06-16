@extends('admin.layouts.master')

@section('page-title')
لوحة التحكم
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.dashboard-welcome', ['roleLabel' => $roleLabel])

        <div class="row g-3 mb-4">
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'purple',
                'icon' => 'ri-group-line',
                'label' => 'إجمالي المستخدمين',
                'value' => number_format($stats['users_total']),
                'hint' => number_format($stats['users_online']) . ' متصل الآن',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'green',
                'icon' => 'ri-article-line',
                'label' => 'مقالات المدونة',
                'value' => number_format($stats['blog_published']),
                'hint' => 'من أصل ' . number_format($stats['blog_total']) . ' مقال',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'cyan',
                'icon' => 'ri-mail-line',
                'label' => 'رسائل التواصل',
                'value' => number_format($stats['contact_total']),
                'hint' => number_format($stats['contact_unread']) . ' غير مقروءة',
            ])
            @include('admin.partials.ui.stat-card-gradient', [
                'variant' => 'orange',
                'icon' => 'ri-mail-send-line',
                'label' => 'مشتركو النشرة',
                'value' => number_format($stats['newsletter_active']),
                'hint' => number_format($stats['newsletter_today']) . ' اشترك اليوم',
            ])
        </div>

        <div class="shortcut-section">
            <div class="shortcut-section__header mb-3">
                <h5 class="shortcut-section__title mb-1">
                    <i class="ri-flashlight-line text-warning"></i>
                    اختصارات سريعة
                </h5>
                <p class="shortcut-section__subtitle mb-0">انتقل مباشرة إلى أقسام الإدارة</p>
            </div>
            <div class="row g-3 shortcut-grid">
                @foreach($shortcuts as $shortcut)
                    @include('admin.partials.ui.shortcut-card', [
                        'url' => $shortcut['url'],
                        'title' => $shortcut['title'],
                        'description' => $shortcut['description'],
                        'icon' => $shortcut['icon'],
                        'icon_color' => $shortcut['icon_color'] ?? 'primary',
                        'badge' => $shortcut['badge'] ?? null,
                    ])
                @endforeach
            </div>
        </div>

    </div>
</div>
@stop
