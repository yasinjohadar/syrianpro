@php
    $activePage = $activePage ?? '';
    $navItems = [
        ['id' => 'home', 'label' => 'الرئيسية', 'href' => route('home')],
        ['id' => 'jobs', 'label' => 'الوظائف', 'href' => route('jobs.index')],
        ['id' => 'talents', 'label' => 'المواهب', 'href' => route('talents.index')],
        ['id' => 'companies', 'label' => 'الشركات', 'href' => route('companies.index')],
        ['id' => 'post-job', 'label' => 'أضف وظيفة', 'href' => route('post-job')],
    ];
@endphp
<nav class="navbar">
    <div class="nav-inner">
        <a class="nav-logo" href="{{ route('home') }}" style="cursor:pointer; text-decoration:none; color:inherit">تك سوريا<span>.</span></a>
        <div class="nav-links">
            @foreach ($navItems as $item)
                <a class="nav-link{{ $activePage === $item['id'] ? ' active' : '' }}" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
            @endforeach
        </div>
        <div class="nav-actions">
            <button class="theme-toggle" onclick="toggleTheme()" title="تبديل الوضع" type="button">🌙</button>
            @auth
                <a class="btn btn-outline btn-sm" href="{{ route(auth()->user()->dashboardRouteName()) }}">لوحتي</a>
            @else
                <a class="btn btn-outline btn-sm" href="{{ route('login') }}">تسجيل الدخول</a>
                <button class="btn btn-primary btn-sm" type="button" onclick="openModal('register')">ابدأ مجاناً ✨</button>
            @endauth
        </div>
    </div>
</nav>
