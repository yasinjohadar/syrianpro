<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $fa = asset('frontend/assets');
    @endphp
    <title>@yield('title', 'تك سوريا — منصة المواهب التقنية السورية')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $fa }}/css/styles.css">
    @yield('styles')
</head>
<body data-page="@yield('page')" @isset($resourceId) data-resource-id="{{ $resourceId }}" @endisset @hasSection('bodyClass') class="@yield('bodyClass')" @endif>

    @include('frontend.layouts.navbar')

    @yield('content')

    @include('frontend.layouts.footer')

    @hasSection('hideAuthModal')
    @else
    @include('frontend.partials.auth-modal')
    @endif

    <div class="toast-container" id="toast-container"></div>
    <script>
        window.FRONTEND_ROUTES = {
            home: @json(route('home')),
            jobs: @json(route('jobs.index')),
            talents: @json(route('talents.index')),
            companies: @json(route('companies.index')),
            postJob: @json(route('post-job')),
            editProfile: @json(route('edit-profile')),
            dashboardSeeker: @json(route('dashboard.seeker')),
            dashboardCompany: @json(route('dashboard.company')),
            profile: @json(route('dashboard.seeker')),
            login: @json(route('login')),
        };
        window.FRONTEND_AUTH = {
            loggedIn: @json(auth()->check()),
            dashboardUrl: @json(auth()->check() ? route('admin.dashboard') : null),
        };
    </script>
    <script src="{{ $fa }}/js/app.js"></script>
    @yield('scripts')
</body>
</html>
