@extends('frontend.layouts.master')

@section('title', 'تسجيل الدخول — تك سوريا')
@section('hideAuthModal', true)

@section('content')
<section class="auth-page">
    <div class="auth-page-card">
        <div class="auth-page-header">
            <a href="{{ route('home') }}" class="auth-page-logo">تك سوريا<span>.</span></a>
            <h1>مرحباً بعودتك</h1>
            <p>سجّل دخولك للوصول لملفك ووظائفك</p>
        </div>

        @if (session('status'))
            <div class="auth-alert auth-alert-success" role="alert">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-input @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="your@email.com"
                >
                @error('email')
                    <div class="auth-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">كلمة المرور</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input @error('password') is-invalid @enderror"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                >
                @error('password')
                    <div class="auth-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-form-row">
                <label for="remember_me" class="auth-remember">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>تذكرني</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-forgot">نسيت كلمة المرور؟</a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg">تسجيل الدخول</button>

            @if (Route::has('register'))
                <div class="auth-switch">
                    ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a>
                </div>
            @endif
        </form>
    </div>
</section>
@endsection
