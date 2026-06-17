<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $fa = asset('frontend/assets');
        $feCssPath = public_path('frontend/assets/css/styles.css');
        $feJsPath = public_path('frontend/assets/js/app.js');
        $feAssetVer = max(
            file_exists($feCssPath) ? filemtime($feCssPath) : 1,
            file_exists($feJsPath) ? filemtime($feJsPath) : 1
        );
    @endphp
    <title>@yield('title', 'تك سوريا')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;700;800;900&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $fa }}/css/styles.css?v={{ $feAssetVer }}">
    @stack('styles')
</head>
<body class="auth-body">
    <button class="auth-theme-toggle" type="button" onclick="toggleTheme()" title="تبديل الوضع" aria-label="تبديل الوضع">🌙</button>

    <div class="auth-split">
        @include('auth.partials.visual')

        <div class="auth-split-panel">
            <a href="{{ route('home') }}" class="auth-split-home">
                <span aria-hidden="true">←</span>
                <span>الرئيسية</span>
            </a>

            @include('auth.partials.tabs', ['active' => $authTab ?? 'login'])

            <div class="auth-split-head">
                <h1>@yield('auth-heading')</h1>
                <p>@yield('auth-subheading')</p>
            </div>

            @yield('content')
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        }
        (function () {
            const saved = localStorage.getItem('theme');
            if (saved === 'dark' || saved === 'light') {
                document.documentElement.setAttribute('data-theme', saved);
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
