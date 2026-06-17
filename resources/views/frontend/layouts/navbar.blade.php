@php
    $activePage = $activePage ?? '';
    $navItems = [
        ['id' => 'home', 'label' => 'الرئيسية', 'icon' => '🏠', 'href' => route('home')],
        ['id' => 'jobs', 'label' => 'الوظائف', 'icon' => '💼', 'href' => route('jobs.index')],
        ['id' => 'talents', 'label' => 'المواهب', 'icon' => '⭐', 'href' => route('talents.index')],
        ['id' => 'companies', 'label' => 'الشركات', 'icon' => '🏢', 'href' => route('companies.index')],
    ];
@endphp
<nav class="navbar" id="site-navbar" aria-label="التنقل الرئيسي">
    <div class="nav-glow" aria-hidden="true"></div>
    <div class="nav-inner">
        <a class="nav-brand" href="{{ route('home') }}">
            <span class="nav-brand-mark" aria-hidden="true">TS</span>
            <span class="nav-brand-text">تك سوريا<span class="nav-brand-dot">.</span></span>
        </a>

        <div class="nav-links" role="navigation" aria-label="روابط الصفحات">
            @foreach ($navItems as $item)
                <a class="nav-link{{ $activePage === $item['id'] ? ' active' : '' }}" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
            @endforeach
        </div>

        <div class="nav-end">
            <div class="nav-actions">
                <button class="theme-toggle" onclick="toggleTheme()" title="تبديل الوضع" type="button" aria-label="تبديل الوضع">🌙</button>
                @auth
                    <a class="btn btn-outline btn-sm nav-btn-dashboard" href="{{ route(auth()->user()->dashboardRouteName()) }}">لوحتي</a>
                @else
                    <a class="btn btn-outline btn-sm nav-btn-login" href="{{ route('login') }}">تسجيل الدخول</a>
                    <button class="btn btn-primary btn-sm nav-btn-register" type="button" onclick="openModal('register')">
                        <span class="nav-btn-full-label">ابدأ مجاناً ✨</span>
                        <span class="nav-btn-short-label">ابدأ ✨</span>
                    </button>
                @endauth
            </div>

            <button class="nav-burger" type="button" aria-label="فتح القائمة" aria-expanded="false" aria-controls="nav-drawer">
                <span class="nav-burger-line"></span>
                <span class="nav-burger-line"></span>
                <span class="nav-burger-line"></span>
            </button>
        </div>
    </div>

    <div class="nav-overlay" aria-hidden="true"></div>

    <aside class="nav-drawer" id="nav-drawer" aria-hidden="true" aria-label="قائمة التنقل">
        <div class="nav-drawer-head">
            <a class="nav-brand nav-brand--drawer" href="{{ route('home') }}">
                <span class="nav-brand-mark" aria-hidden="true">TS</span>
                <span class="nav-brand-text">تك سوريا<span class="nav-brand-dot">.</span></span>
            </a>
            <button class="nav-drawer-close" type="button" aria-label="إغلاق القائمة">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="nav-drawer-links" aria-label="روابط الصفحات">
            @foreach ($navItems as $item)
                <a class="nav-drawer-link{{ $activePage === $item['id'] ? ' active' : '' }}" href="{{ $item['href'] }}">
                    <span class="nav-drawer-icon" aria-hidden="true">{{ $item['icon'] }}</span>
                    <span class="nav-drawer-label">{{ $item['label'] }}</span>
                    @if ($activePage === $item['id'])
                        <span class="nav-drawer-active-dot" aria-hidden="true"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="nav-drawer-foot">
            <button class="theme-toggle theme-toggle--drawer" onclick="toggleTheme()" title="تبديل الوضع" type="button" aria-label="تبديل الوضع">🌙</button>
            @auth
                <a class="btn btn-primary btn-full" href="{{ route(auth()->user()->dashboardRouteName()) }}">لوحتي</a>
            @else
                <a class="btn btn-outline btn-full" href="{{ route('login') }}">تسجيل الدخول</a>
                <button class="btn btn-primary btn-full" type="button" onclick="openModal('register'); closeNavDrawer();">ابدأ مجاناً ✨</button>
            @endauth
        </div>
    </aside>
</nav>
