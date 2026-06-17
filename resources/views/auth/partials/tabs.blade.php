@php
    $active = $active ?? 'login';
@endphp
<nav class="auth-split-tabs" aria-label="نوع الصفحة">
    <a href="{{ route('login') }}" class="auth-split-tab {{ $active === 'login' ? 'is-active' : '' }}">
        تسجيل الدخول
    </a>
    <a href="{{ route('register') }}" class="auth-split-tab {{ $active === 'register' ? 'is-active' : '' }}">
        إنشاء حساب
    </a>
</nav>
