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

        @if (!empty($demoAccounts))
            <div class="auth-demo-login">
                <div class="auth-demo-divider">
                    <span>دخول سريع للتجربة</span>
                </div>
                <p class="auth-demo-note">للتطوير المحلي فقط — بيانات من الـ seed</p>
                <div class="auth-demo-grid">
                    @foreach ($demoAccounts as $key => $account)
                        <form method="POST" action="{{ route('login.demo', $key) }}">
                            @csrf
                            <button type="submit" class="auth-demo-btn auth-demo-btn--{{ $key }}">
                                <span class="auth-demo-btn-label">{{ $account['label'] }}</span>
                                <span class="auth-demo-btn-desc">{{ $account['description'] ?? $account['email'] }}</span>
                            </button>
                        </form>
                    @endforeach
                </div>
                <p class="auth-demo-hint">
                    كلمة المرور الافتراضية: <code>{{ $demoPassword }}</code>
                    · المستخدم العام: <code>password</code>
                </p>
            </div>
        @endif
    </div>
</section>
@endsection
