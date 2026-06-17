<footer class="footer">
    <div class="footer-bg">
        <div class="footer-orb footer-orb-1"></div>
        <div class="footer-orb footer-orb-2"></div>
        <div class="footer-orb footer-orb-3"></div>
    </div>
    <div class="footer-wrap">
        <div class="footer-cta">
            <div class="footer-cta-content">
                <span class="footer-cta-badge">🌐 Remote-first</span>
                <h3>جاهز تعمل عن بُعد مع العالم؟</h3>
                <p>انضم لـ <strong>500+</strong> تقني سوري — اعرض مهاراتك أو وظّف أفضل المواهب.</p>
            </div>
            <div class="footer-cta-actions">
                <a class="btn btn-primary" href="{{ route('edit-profile') }}">👤 أنشئ ملفك</a>
                <a class="btn footer-cta-outline" href="{{ route('contact') }}">🏢 تواصل معنا</a>
            </div>
        </div>

        <div class="footer-inner">
            <div class="footer-brand">
                <div class="footer-logo">تك سوريا<span>.</span></div>
                <p class="footer-tagline">منصة المواهب التقنية السورية — وظائف remote، معرض أعمال، وتواصل مباشر مع الشركات.</p>
                <div class="footer-mini-stats">
                    <div class="footer-stat"><span class="footer-stat-num">500+</span><span class="footer-stat-label">تقني</span></div>
                    <div class="footer-stat"><span class="footer-stat-num">120+</span><span class="footer-stat-label">وظيفة</span></div>
                    <div class="footer-stat"><span class="footer-stat-num">80+</span><span class="footer-stat-label">شركة</span></div>
                </div>
                <div class="footer-social">
                    <a class="social-icon" href="#" title="X" aria-label="X">𝕏</a>
                    <a class="social-icon" href="#" title="LinkedIn" aria-label="LinkedIn">in</a>
                    <a class="social-icon" href="#" title="GitHub" aria-label="GitHub">🐙</a>
                    <a class="social-icon" href="#" title="YouTube" aria-label="YouTube">▶</a>
                </div>
            </div>

            <div class="footer-col">
                <h4><span class="footer-col-dot"></span> للتقنيين</h4>
                <ul>
                    <li><a href="{{ route('jobs.index') }}"><span>وظائف عن بُعد</span><span class="footer-link-arrow">←</span></a></li>
                    <li><a href="{{ route('talents.index') }}"><span>دليل المواهب</span><span class="footer-link-arrow">←</span></a></li>
                    <li><a href="{{ route('edit-profile') }}"><span>إنشاء ملف</span><span class="footer-link-arrow">←</span></a></li>
                    <li><a href="{{ route('talent.dashboard') }}"><span>لوحة التحكم</span><span class="footer-link-arrow">←</span></a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4><span class="footer-col-dot"></span> للشركات</h4>
                <ul>
                    <li><a href="{{ route('contact') }}"><span>تواصل معنا</span><span class="footer-link-arrow">←</span></a></li>
                    <li><a href="{{ route('company.dashboard') }}"><span>لوحة الشركة</span><span class="footer-link-arrow">←</span></a></li>
                    <li><a href="{{ route('talents.index') }}"><span>قاعدة المواهب</span><span class="footer-link-arrow">←</span></a></li>
                    <li><a href="{{ route('companies.index') }}"><span>الشركات</span><span class="footer-link-arrow">←</span></a></li>
                </ul>
            </div>

            <div class="footer-col footer-newsletter">
                <h4><span class="footer-col-dot"></span> ابقَ على اطلاع</h4>
                <p class="footer-newsletter-text">وظائف remote جديدة ونصائح تقنية — أسبوعياً.</p>
                <div class="footer-newsletter-form">
                    <input type="email" class="footer-email-input" placeholder="بريدك الإلكتروني">
                    <button class="footer-subscribe-btn" type="button" onclick="showToast('✅ تم الاشتراك بنجاح!', 'success')">اشترك</button>
                </div>
                <div class="footer-badges">
                    <span class="footer-badge">💵 USD</span>
                    <span class="footer-badge">🌐 Remote</span>
                    <span class="footer-badge">🇸🇾 Syria</span>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© {{ date('Y') }} تك سوريا. جميع الحقوق محفوظة.</p>
            <div class="footer-bottom-links">
                <span>سياسة الخصوصية</span>
                <span class="footer-bottom-sep">·</span>
                <span>الشروط والأحكام</span>
                <span class="footer-bottom-sep">·</span>
                <span>اتصل بنا</span>
            </div>
            <p class="footer-made">صُنع بـ <span class="footer-heart">❤️</span> للمواهب السورية</p>
        </div>
    </div>
</footer>
