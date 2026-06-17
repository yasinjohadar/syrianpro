@extends('layouts.auth')

@php
    $authTab = 'register';
    $selectedRole = old('account_type', 'talent');
@endphp

@section('title', 'إنشاء حساب — تك سوريا')
@section('auth-heading', 'انضم إلى تك سوريا')
@section('auth-subheading', 'اختر نوع حسابك وابدأ خلال دقيقة')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="auth-form" id="register-form" novalidate>
        @csrf

        <div class="form-group">
            <span class="form-label">أريد التسجيل كـ</span>
            <div class="role-select auth-role-select" role="radiogroup" aria-label="نوع الحساب">
                <label class="role-btn auth-role-option {{ $selectedRole === 'talent' ? 'active' : '' }}" data-role="talent">
                    <input type="radio" name="account_type" value="talent" {{ $selectedRole === 'talent' ? 'checked' : '' }} required>
                    <span class="role-icon">👤</span>
                    <span class="role-name">تقني / مطور</span>
                    <span class="role-desc">ملف شخصي ومعرض أعمال</span>
                </label>
                <label class="role-btn auth-role-option {{ $selectedRole === 'company' ? 'active' : '' }}" data-role="company">
                    <input type="radio" name="account_type" value="company" {{ $selectedRole === 'company' ? 'checked' : '' }}>
                    <span class="role-icon">🏢</span>
                    <span class="role-name">شركة</span>
                    <span class="role-desc">نشر وظائف وتوظيف مواهب</span>
                </label>
            </div>
            @error('account_type')
                <div class="auth-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="name" class="form-label" id="name-label">
                {{ $selectedRole === 'company' ? 'اسم الشركة' : 'الاسم الكامل' }}
            </label>
            <input
                id="name"
                type="text"
                name="name"
                class="form-input @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="{{ $selectedRole === 'company' ? 'SyriaDev Studio' : 'أحمد الخطيب' }}"
            >
            @error('name')
                <div class="auth-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group auth-role-field" data-show-for="talent" @if($selectedRole !== 'talent') hidden @endif>
            <label for="title" class="form-label">المسمى الوظيفي</label>
            <input
                id="title"
                type="text"
                name="title"
                class="form-input @error('title') is-invalid @enderror"
                value="{{ old('title') }}"
                placeholder="مطور React · مصمم UI/UX"
            >
            @error('title')
                <div class="auth-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group auth-role-field" data-show-for="company" @if($selectedRole !== 'company') hidden @endif>
            <label for="sector" class="form-label">قطاع الشركة</label>
            <input
                id="sector"
                type="text"
                name="sector"
                class="form-input @error('sector') is-invalid @enderror"
                value="{{ old('sector') }}"
                placeholder="تطوير البرمجيات · DevOps · تصميم"
            >
            @error('sector')
                <div class="auth-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input
                id="email"
                type="email"
                name="email"
                class="form-input @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                required
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
                autocomplete="new-password"
                placeholder="8 أحرف على الأقل"
            >
            @error('password')
                <div class="auth-form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-input"
                required
                autocomplete="new-password"
                placeholder="أعد إدخال كلمة المرور"
            >
        </div>

        <button type="submit" class="btn btn-primary btn-full btn-lg auth-submit-btn">
            إنشاء الحساب مجاناً
        </button>

        <div class="auth-switch">
            لديك حساب؟ <a href="{{ route('login') }}">تسجيل الدخول</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('register-form');
    if (!form) return;

    const options = form.querySelectorAll('.auth-role-option');
    const nameLabel = document.getElementById('name-label');
    const nameInput = document.getElementById('name');
    const roleFields = form.querySelectorAll('.auth-role-field');

    const labels = {
        talent: { label: 'الاسم الكامل', placeholder: 'أحمد الخطيب' },
        company: { label: 'اسم الشركة', placeholder: 'SyriaDev Studio' },
    };

    function setRole(role) {
        options.forEach((opt) => {
            opt.classList.toggle('active', opt.dataset.role === role);
            const radio = opt.querySelector('input[type="radio"]');
            if (radio) radio.checked = opt.dataset.role === role;
        });

        const copy = labels[role] || labels.talent;
        if (nameLabel) nameLabel.textContent = copy.label;
        if (nameInput) nameInput.placeholder = copy.placeholder;

        roleFields.forEach((field) => {
            const show = field.dataset.showFor === role;
            field.hidden = !show;
        });
    }

    options.forEach((opt) => {
        opt.addEventListener('click', () => setRole(opt.dataset.role));
    });
})();
</script>
@endpush
