        <!-- Start::app-sidebar -->
        <aside class="app-sidebar sticky sidebar-premium" id="sidebar">
            <div class="main-sidebar-header">
                <a href="{{ route('talent.dashboard') }}" class="header-logo">
                    <svg class="desktop-logo" width="120" height="40" viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                        <text x="60" y="26" font-family="Arial, sans-serif" font-size="16" font-weight="700" fill="#1e293b" text-anchor="middle">لوحة التقني</text>
                    </svg>
                    <svg class="toggle-logo" width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <text x="20" y="25" font-family="Arial, sans-serif" font-size="12" font-weight="700" fill="#2563eb" text-anchor="middle">TL</text>
                    </svg>
                    <svg class="desktop-white" width="120" height="40" viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                        <text x="60" y="26" font-family="Arial, sans-serif" font-size="16" font-weight="700" fill="#f8fafc" text-anchor="middle">لوحة التقني</text>
                    </svg>
                    <svg class="toggle-white" width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <text x="20" y="25" font-family="Arial, sans-serif" font-size="12" font-weight="700" fill="#f8fafc" text-anchor="middle">TL</text>
                    </svg>
                </a>
            </div>

            <div class="main-sidebar" id="sidebar-scroll">
                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="slide-left" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path></svg>
                    </div>
                    <ul class="main-menu">
                        <li class="slide {{ request()->routeIs('talent.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('talent.dashboard') }}" class="side-menu__item {{ request()->routeIs('talent.dashboard') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--dashboard">
                                    <i class="ri-dashboard-3-line"></i>
                                </span>
                                <span class="side-menu__label">لوحة التحكم</span>
                            </a>
                        </li>
                        <li class="slide {{ request()->routeIs('talent.applications.*') ? 'active' : '' }}">
                            <a href="{{ route('talent.applications.index') }}" class="side-menu__item {{ request()->routeIs('talent.applications.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--consultation">
                                    <i class="ri-send-plane-line"></i>
                                </span>
                                <span class="side-menu__label">طلباتي</span>
                            </a>
                        </li>
                        <li class="slide {{ request()->routeIs('talent.hiring-request.*') ? 'active' : '' }}">
                            <a href="{{ route('talent.hiring-request.index') }}" class="side-menu__item {{ request()->routeIs('talent.hiring-request.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--roles">
                                    <i class="ri-user-search-line"></i>
                                </span>
                                <span class="side-menu__label">أبحث عن عمل</span>
                            </a>
                        </li>
                        <li class="slide {{ request()->routeIs('talent.hires.*') ? 'active' : '' }}">
                            <a href="{{ route('talent.hires.index') }}" class="side-menu__item {{ request()->routeIs('talent.hires.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--roles">
                                    <i class="ri-trophy-line"></i>
                                </span>
                                <span class="side-menu__label">سجل التوظيف</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('jobs.index') }}" class="side-menu__item">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--users">
                                    <i class="ri-search-line"></i>
                                </span>
                                <span class="side-menu__label">تصفح الوظائف</span>
                            </a>
                        </li>
                        <li class="slide {{ request()->routeIs('talent.profile.*') ? 'active' : '' }}">
                            <a href="{{ route('talent.profile.edit') }}" class="side-menu__item {{ request()->routeIs('talent.profile.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--roles">
                                    <i class="ri-user-settings-line"></i>
                                </span>
                                <span class="side-menu__label">تعديل الملف</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('home') }}" class="side-menu__item">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--dashboard">
                                    <i class="ri-global-line"></i>
                                </span>
                                <span class="side-menu__label">الموقع العام</span>
                            </a>
                        </li>
                    </ul>
                    <div class="slide-right" id="slide-right">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path></svg>
                    </div>
                </nav>
            </div>
        </aside>
