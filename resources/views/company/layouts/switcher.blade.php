    <!-- Start Switcher -->
    <div class="offcanvas offcanvas-end theme-switcher-premium" tabindex="-1" id="switcher-canvas" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header theme-switcher-premium__header">
            <div>
                <h5 class="offcanvas-title mb-0" id="offcanvasRightLabel">
                    <i class="ri-palette-line me-2"></i>إعدادات العرض
                </h5>
                <p class="theme-switcher-premium__subtitle mb-0">خصّص مظهر لوحة التحكم</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="إغلاق"></button>
        </div>
        <div class="offcanvas-body theme-switcher-premium__body">
            <nav class="theme-switcher-premium__tabs">
                <div class="nav nav-pills nav-justified" id="switcher-main-tab" role="tablist">
                    <button class="nav-link active" id="switcher-home-tab" data-bs-toggle="tab" data-bs-target="#switcher-home"
                        type="button" role="tab" aria-controls="switcher-home" aria-selected="true">
                        <i class="ri-layout-line me-1"></i> أنماط السمة
                    </button>
                    <button class="nav-link" id="switcher-profile-tab" data-bs-toggle="tab" data-bs-target="#switcher-profile"
                        type="button" role="tab" aria-controls="switcher-profile" aria-selected="false">
                        <i class="ri-paint-brush-line me-1"></i> ألوان السمة
                    </button>
                </div>
            </nav>
            <div class="tab-content theme-switcher-premium__content" id="nav-tabContent">
                <div class="tab-pane fade show active border-0" id="switcher-home" role="tabpanel" aria-labelledby="switcher-home-tab"
                    tabindex="0">

                    <div class="switcher-section">
                        <p class="switcher-style-head">وضع لون السمة</p>
                        <div class="row switcher-style gx-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="theme-style" id="switcher-light-theme" checked>
                                    <label class="form-check-label" for="switcher-light-theme">
                                        <i class="ri-sun-line me-1"></i> فاتح
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="theme-style" id="switcher-dark-theme">
                                    <label class="form-check-label" for="switcher-dark-theme">
                                        <i class="ri-moon-line me-1"></i> داكن
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section">
                        <p class="switcher-style-head">اتجاه الواجهة</p>
                        <div class="row switcher-style gx-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="direction" id="switcher-rtl" checked>
                                    <label class="form-check-label" for="switcher-rtl">
                                        <i class="ri-text-direction-r me-1"></i> يمين لليسار
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="direction" id="switcher-ltr">
                                    <label class="form-check-label" for="switcher-ltr">
                                        <i class="ri-text-direction-l me-1"></i> يسار لليمين
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section">
                        <p class="switcher-style-head">نمط التنقل</p>
                        <div class="row switcher-style gx-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="navigation-style" id="switcher-vertical" checked>
                                    <label class="form-check-label" for="switcher-vertical">
                                        <i class="ri-menu-line me-1"></i> عمودي
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="navigation-style" id="switcher-horizontal">
                                    <label class="form-check-label" for="switcher-horizontal">
                                        <i class="ri-layout-top-line me-1"></i> أفقي
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section navigation-menu-styles">
                        <p class="switcher-style-head">تفاعل القائمة</p>
                        <div class="row switcher-style gx-2 gy-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles" id="switcher-menu-click">
                                    <label class="form-check-label" for="switcher-menu-click">نقر القائمة</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles" id="switcher-menu-hover">
                                    <label class="form-check-label" for="switcher-menu-hover">تمرير القائمة</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles" id="switcher-icon-click">
                                    <label class="form-check-label" for="switcher-icon-click">نقر الأيقونة</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="navigation-menu-styles" id="switcher-icon-hover">
                                    <label class="form-check-label" for="switcher-icon-hover">تمرير الأيقونة</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section sidemenu-layout-styles">
                        <p class="switcher-style-head">تخطيط القائمة الجانبية</p>
                        <div class="row switcher-style gx-2 gy-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles" id="switcher-default-menu" checked>
                                    <label class="form-check-label" for="switcher-default-menu">افتراضي</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles" id="switcher-closed-menu">
                                    <label class="form-check-label" for="switcher-closed-menu">مغلقة</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles" id="switcher-icontext-menu">
                                    <label class="form-check-label" for="switcher-icontext-menu">نص + أيقونة</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles" id="switcher-icon-overlay">
                                    <label class="form-check-label" for="switcher-icon-overlay">تراكب الأيقونة</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles" id="switcher-detached">
                                    <label class="form-check-label" for="switcher-detached">منفصل</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="sidemenu-layout-styles" id="switcher-double-menu">
                                    <label class="form-check-label" for="switcher-double-menu">قائمة مزدوجة</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section">
                        <p class="switcher-style-head">نمط الصفحة</p>
                        <div class="row switcher-style gx-2">
                            <div class="col-4">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-regular" checked>
                                    <label class="form-check-label" for="switcher-regular">عادي</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-classic">
                                    <label class="form-check-label" for="switcher-classic">كلاسيكي</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="page-styles" id="switcher-modern">
                                    <label class="form-check-label" for="switcher-modern">عصري</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section">
                        <p class="switcher-style-head">عرض التخطيط</p>
                        <div class="row switcher-style gx-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="layout-width" id="switcher-full-width" checked>
                                    <label class="form-check-label" for="switcher-full-width">عرض كامل</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="layout-width" id="switcher-boxed">
                                    <label class="form-check-label" for="switcher-boxed">محدود</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section">
                        <p class="switcher-style-head">موضع القائمة</p>
                        <div class="row switcher-style gx-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-fixed" checked>
                                    <label class="form-check-label" for="switcher-menu-fixed">ثابت</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="menu-positions" id="switcher-menu-scroll">
                                    <label class="form-check-label" for="switcher-menu-scroll">قابل للتمرير</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section">
                        <p class="switcher-style-head">موضع الهيدر</p>
                        <div class="row switcher-style gx-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="header-positions" id="switcher-header-fixed" checked>
                                    <label class="form-check-label" for="switcher-header-fixed">ثابت</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="header-positions" id="switcher-header-scroll">
                                    <label class="form-check-label" for="switcher-header-scroll">قابل للتمرير</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section">
                        <p class="switcher-style-head">شاشة التحميل</p>
                        <div class="row switcher-style gx-2">
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="page-loader" id="switcher-loader-enable" checked>
                                    <label class="form-check-label" for="switcher-loader-enable">تفعيل</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check switch-select switch-chip">
                                    <input class="form-check-input" type="radio" name="page-loader" id="switcher-loader-disable">
                                    <label class="form-check-label" for="switcher-loader-disable">تعطيل</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade border-0" id="switcher-profile" role="tabpanel" aria-labelledby="switcher-profile-tab" tabindex="0">
                    <div class="switcher-section theme-colors">
                        <p class="switcher-style-head">ألوان القائمة</p>
                        <div class="d-flex flex-wrap switcher-style switcher-colors-row pb-2">
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="قائمة فاتحة" type="radio" name="menu-colors"
                                    id="switcher-menu-light" checked>
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="قائمة داكنة" type="radio" name="menu-colors"
                                    id="switcher-menu-dark">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="قائمة ملونة" type="radio" name="menu-colors"
                                    id="switcher-menu-primary">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="قائمة متدرجة" type="radio" name="menu-colors"
                                    id="switcher-menu-gradient">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="قائمة شفافة" type="radio" name="menu-colors"
                                    id="switcher-menu-transparent">
                            </div>
                        </div>
                        <p class="switcher-style-note">يمكنك تغيير لون القائمة ديناميكياً من محدّد اللون الأساسي أدناه.</p>
                    </div>

                    <div class="switcher-section theme-colors">
                        <p class="switcher-style-head">ألوان الهيدر</p>
                        <div class="d-flex flex-wrap switcher-style switcher-colors-row pb-2">
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-white" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="هيدر فاتح" type="radio" name="header-colors"
                                    id="switcher-header-light" checked>
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-dark" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="هيدر داكن" type="radio" name="header-colors"
                                    id="switcher-header-dark">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="هيدر ملون" type="radio" name="header-colors"
                                    id="switcher-header-primary">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-gradient" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="هيدر متدرج" type="radio" name="header-colors"
                                    id="switcher-header-gradient">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-transparent" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="هيدر شفاف" type="radio" name="header-colors"
                                    id="switcher-header-transparent">
                            </div>
                        </div>
                        <p class="switcher-style-note">يمكنك تغيير لون الهيدر ديناميكياً من محدّد اللون الأساسي أدناه.</p>
                    </div>

                    <div class="switcher-section theme-colors">
                        <p class="switcher-style-head">اللون الأساسي</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style switcher-colors-row">
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-primary-1" type="radio"
                                    name="theme-primary" id="switcher-primary">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-primary-2" type="radio"
                                    name="theme-primary" id="switcher-primary1">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-primary-3" type="radio" name="theme-primary"
                                    id="switcher-primary2">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-primary-4" type="radio" name="theme-primary"
                                    id="switcher-primary3">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-primary-5" type="radio" name="theme-primary"
                                    id="switcher-primary4">
                            </div>
                            <div class="form-check switch-select ps-0 mt-1 color-primary-light">
                                <div class="theme-container-primary"></div>
                                <div class="pickr-container-primary"></div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section theme-colors">
                        <p class="switcher-style-head">خلفية السمة</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style switcher-colors-row">
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-bg-1" type="radio"
                                    name="theme-background" id="switcher-background">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-bg-2" type="radio"
                                    name="theme-background" id="switcher-background1">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-bg-3" type="radio" name="theme-background"
                                    id="switcher-background2">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-bg-4" type="radio"
                                    name="theme-background" id="switcher-background3">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input color-input color-bg-5" type="radio"
                                    name="theme-background" id="switcher-background4">
                            </div>
                            <div class="form-check switch-select ps-0 mt-1 tooltip-static-demo color-bg-transparent">
                                <div class="theme-container-background"></div>
                                <div class="pickr-container-background"></div>
                            </div>
                        </div>
                    </div>

                    <div class="switcher-section menu-image">
                        <p class="switcher-style-head">خلفية صورة للقائمة</p>
                        <div class="d-flex flex-wrap align-items-center switcher-style switcher-bg-images">
                            <div class="form-check switch-select">
                                <input class="form-check-input bgimage-input bg-img1" type="radio"
                                    name="theme-background" id="switcher-bg-img">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input bgimage-input bg-img2" type="radio"
                                    name="theme-background" id="switcher-bg-img1">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input bgimage-input bg-img3" type="radio" name="theme-background"
                                    id="switcher-bg-img2">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input bgimage-input bg-img4" type="radio"
                                    name="theme-background" id="switcher-bg-img3">
                            </div>
                            <div class="form-check switch-select">
                                <input class="form-check-input bgimage-input bg-img5" type="radio"
                                    name="theme-background" id="switcher-bg-img4">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="theme-switcher-premium__footer">
                    <a href="javascript:void(0);" id="reset-all" class="btn theme-switcher-premium__reset w-100">
                        <i class="ri-restart-line me-1"></i> إعادة ضبط
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Switcher -->
