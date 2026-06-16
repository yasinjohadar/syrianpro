        <!-- Start::app-sidebar -->
        <aside class="app-sidebar sticky sidebar-premium" id="sidebar">

            <!-- Start::main-sidebar-header -->
            <div class="main-sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="header-logo">
                    <svg class="desktop-logo" width="120" height="40" viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                        <text x="60" y="26" font-family="Arial, sans-serif" font-size="16" font-weight="700" fill="#1e293b" text-anchor="middle">لوحة التحكم</text>
                    </svg>
                    <svg class="toggle-logo" width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <text x="20" y="25" font-family="Arial, sans-serif" font-size="12" font-weight="700" fill="#2563eb" text-anchor="middle">LD</text>
                    </svg>
                    <svg class="desktop-white" width="120" height="40" viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                        <text x="60" y="26" font-family="Arial, sans-serif" font-size="16" font-weight="700" fill="#f8fafc" text-anchor="middle">لوحة التحكم</text>
                    </svg>
                    <svg class="toggle-white" width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <text x="20" y="25" font-family="Arial, sans-serif" font-size="12" font-weight="700" fill="#f8fafc" text-anchor="middle">LD</text>
                    </svg>
                </a>
            </div>
            <!-- End::main-sidebar-header -->

            <!-- Start::main-sidebar -->
            <div class="main-sidebar" id="sidebar-scroll">

                <!-- Start::nav -->
                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="slide-left" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
                    </div>
                    @php
                        $isSettingsOpen = request()->routeIs([
                            'admin.settings.*',
                            'admin.ai.*',
                            'admin.storage.*',
                            'admin.storage-disk-mappings.*',
                            'admin.backups.*',
                            'admin.backup-schedules.*',
                            'admin.settings.backup.*',
                            'admin.settings.storage.*',
                            'admin.storage-migration.*',
                            'admin.media.*',
                            'admin.media-monitoring.*',
                            'admin.whatsapp*',
                        ]);
                    @endphp
                    <ul class="main-menu">
                        <li class="slide {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--dashboard">
                                    <i class="ri-dashboard-3-line"></i>
                                </span>
                                <span class="side-menu__label">لوحة التحكم</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}" class="side-menu__item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--users">
                                    <i class="ri-group-line"></i>
                                </span>
                                <span class="side-menu__label">المستخدمون</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.tech-specialties.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.tech-specialties.index') }}" class="side-menu__item {{ request()->routeIs('admin.tech-specialties.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--roles">
                                    <i class="ri-code-box-line"></i>
                                </span>
                                <span class="side-menu__label">التخصصات التقنية</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.jobs.index') }}" class="side-menu__item {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--users">
                                    <i class="ri-briefcase-line"></i>
                                </span>
                                <span class="side-menu__label">الوظائف</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.job-applications.index') }}" class="side-menu__item {{ request()->routeIs('admin.job-applications.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--consultation">
                                    <i class="ri-file-list-3-line"></i>
                                </span>
                                <span class="side-menu__label">طلبات التوظيف</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.talents.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.talents.index') }}" class="side-menu__item {{ request()->routeIs('admin.talents.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--roles">
                                    <i class="ri-user-star-line"></i>
                                </span>
                                <span class="side-menu__label">المواهب</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.companies.index') }}" class="side-menu__item {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--users">
                                    <i class="ri-building-line"></i>
                                </span>
                                <span class="side-menu__label">الشركات</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.roles.index') }}" class="side-menu__item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--roles">
                                    <i class="ri-shield-user-line"></i>
                                </span>
                                <span class="side-menu__label">الصلاحيات</span>
                            </a>
                        </li>

                        <li class="slide has-sub {{ request()->routeIs('admin.blog.*') ? 'open active' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--blog">
                                    <i class="ri-article-line"></i>
                                </span>
                                <span class="side-menu__label">المدونة</span>
                                <i class="ri-arrow-left-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide {{ request()->routeIs('admin.blog.posts.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.blog.posts.index') }}" class="side-menu__item">المقالات</a>
                                </li>
                                <li class="slide {{ request()->routeIs('admin.blog.ai-posts.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.blog.ai-posts.create') }}" class="side-menu__item">مقال بالذكاء الاصطناعي</a>
                                </li>
                                <li class="slide {{ request()->routeIs('admin.blog.categories.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.blog.categories.index') }}" class="side-menu__item">التصنيفات</a>
                                </li>
                                <li class="slide {{ request()->routeIs('admin.blog.tags.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.blog.tags.index') }}" class="side-menu__item">الوسوم</a>
                                </li>
                            </ul>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.contact-messages.index') }}" class="side-menu__item {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--contact">
                                    <i class="ri-mail-line"></i>
                                </span>
                                <span class="side-menu__label">رسائل التواصل</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.newsletter-subscribers.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.newsletter-subscribers.index') }}" class="side-menu__item {{ request()->routeIs('admin.newsletter-subscribers.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--newsletter">
                                    <i class="ri-mail-send-line"></i>
                                </span>
                                <span class="side-menu__label">النشرة البريدية</span>
                            </a>
                        </li>

                        <li class="slide {{ request()->routeIs('admin.consultation-requests.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.consultation-requests.index') }}" class="side-menu__item {{ request()->routeIs('admin.consultation-requests.*') ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--consultation">
                                    <i class="ri-calendar-check-line"></i>
                                </span>
                                <span class="side-menu__label">طلبات الاستشارة</span>
                            </a>
                        </li>

                        <li class="slide has-sub {{ $isSettingsOpen ? 'open active' : '' }}">
                            <a href="javascript:void(0);" class="side-menu__item {{ $isSettingsOpen ? 'active' : '' }}">
                                <span class="side-menu__icon side-menu__icon--boxed side-menu__icon--settings">
                                    <i class="ri-settings-3-line"></i>
                                </span>
                                <span class="side-menu__label">الإعدادات</span>
                                <i class="ri-arrow-left-s-line side-menu__angle"></i>
                            </a>
                            <ul class="slide-menu child1">
                                <li class="slide {{ request()->routeIs('admin.settings.site.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.settings.site.index') }}" class="side-menu__item">إعدادات الموقع</a>
                                </li>

                                <li class="slide has-sub {{ request()->routeIs('admin.ai.*') ? 'open active' : '' }}">
                                    <a href="javascript:void(0);" class="side-menu__item">
                                        الذكاء الاصطناعي
                                        <i class="ri-arrow-left-s-line side-menu__angle"></i>
                                    </a>
                                    <ul class="slide-menu child2">
                                        <li class="slide {{ request()->routeIs('admin.ai.settings.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.ai.settings.index') }}" class="side-menu__item">المزودون والإعدادات</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.ai.models.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.ai.models.index') }}" class="side-menu__item">الموديلات</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="slide has-sub {{ request()->routeIs('admin.settings.email.*') ? 'open active' : '' }}">
                                    <a href="javascript:void(0);" class="side-menu__item">
                                        إعدادات البريد
                                        <i class="ri-arrow-left-s-line side-menu__angle"></i>
                                    </a>
                                    <ul class="slide-menu child2">
                                        <li class="slide {{ request()->routeIs('admin.settings.email.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.settings.email.index') }}" class="side-menu__item">جميع الإعدادات</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.settings.email.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.settings.email.create') }}" class="side-menu__item">إضافة إعداد</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="slide has-sub {{ request()->routeIs('admin.whatsapp*') ? 'open active' : '' }}">
                                    <a href="javascript:void(0);" class="side-menu__item">
                                        واتساب
                                        <i class="ri-arrow-left-s-line side-menu__angle"></i>
                                    </a>
                                    <ul class="slide-menu child2">
                                        <li class="slide {{ request()->routeIs('admin.whatsapp-messages.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.whatsapp-messages.index') }}" class="side-menu__item">الرسائل</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.whatsapp-settings.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.whatsapp-settings.index') }}" class="side-menu__item">إعدادات الربط</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.whatsapp-web.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.whatsapp-web.connect') }}" class="side-menu__item">ربط واتساب ويب</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.whatsapp-web-settings.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.whatsapp-web-settings.index') }}" class="side-menu__item">إعدادات واتساب ويب</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="slide has-sub {{ request()->routeIs('admin.storage.*') || request()->routeIs('admin.storage-migration.*') || request()->routeIs('admin.media.*') || request()->routeIs('admin.media-monitoring.*') || request()->routeIs('admin.settings.storage.*') ? 'open active' : '' }}">
                                    <a href="javascript:void(0);" class="side-menu__item">
                                        التخزين السحابي
                                        <i class="ri-arrow-left-s-line side-menu__angle"></i>
                                    </a>
                                    <ul class="slide-menu child2">
                                        <li class="slide {{ request()->routeIs('admin.storage.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.storage.index') }}" class="side-menu__item">أماكن التخزين</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.storage.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.storage.create') }}" class="side-menu__item">إضافة مكان</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.storage.analytics') ? 'active' : '' }}">
                                            <a href="{{ route('admin.storage.analytics') }}" class="side-menu__item">الإحصائيات</a>
                                        </li>
                                        @can('storage-settings-view')
                                        <li class="slide {{ request()->routeIs('admin.settings.storage.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.settings.storage.index') }}" class="side-menu__item">إعدادات التخزين</a>
                                        </li>
                                        @endcan
                                        @can('storage-migration-view')
                                        <li class="slide {{ request()->routeIs('admin.storage-migration.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.storage-migration.index') }}" class="side-menu__item">ترحيل للسحابة</a>
                                        </li>
                                        @endcan
                                        @can('media-list')
                                        <li class="slide {{ request()->routeIs('admin.media.index') || request()->routeIs('admin.media.show') ? 'active' : '' }}">
                                            <a href="{{ route('admin.media.index') }}" class="side-menu__item">الوسائط</a>
                                        </li>
                                        @endcan
                                        @can('media-monitoring-view')
                                        <li class="slide {{ request()->routeIs('admin.media-monitoring.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.media-monitoring.index') }}" class="side-menu__item">مراقبة الوسائط</a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>

                                <li class="slide {{ request()->routeIs('admin.storage-disk-mappings.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.storage-disk-mappings.index') }}" class="side-menu__item">ربط الأقراص</a>
                                </li>

                                <li class="slide has-sub {{ request()->routeIs('admin.backups.*') || request()->routeIs('admin.backup-schedules.*') || request()->routeIs('admin.settings.backup.*') ? 'open active' : '' }}">
                                    <a href="javascript:void(0);" class="side-menu__item">
                                        النسخ الاحتياطي
                                        <i class="ri-arrow-left-s-line side-menu__angle"></i>
                                    </a>
                                    <ul class="slide-menu child2">
                                        <li class="slide {{ request()->routeIs('admin.backups.index') || request()->routeIs('admin.backups.show') ? 'active' : '' }}">
                                            <a href="{{ route('admin.backups.index') }}" class="side-menu__item">النسخ الاحتياطية</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.backups.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.backups.create') }}" class="side-menu__item">إنشاء نسخة</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.backup-schedules.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.backup-schedules.index') }}" class="side-menu__item">الجداول الزمنية</a>
                                        </li>
                                        <li class="slide {{ request()->routeIs('admin.storage.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.storage.index') }}" class="side-menu__item">أماكن التخزين</a>
                                        </li>
                                        @can('backup-settings-view')
                                        <li class="slide {{ request()->routeIs('admin.settings.backup.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.settings.backup.index') }}" class="side-menu__item">إعدادات النسخ</a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    </ul>
                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
                </nav>
                <!-- End::nav -->

            </div>
            <!-- End::main-sidebar -->

        </aside>
        <!-- End::app-sidebar -->
