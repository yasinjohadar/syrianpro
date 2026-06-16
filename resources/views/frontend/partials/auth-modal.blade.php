<div class="modal-overlay" id="auth-modal" onclick="closeModalOutside(event)">
    <div class="modal">
        <button class="modal-close" type="button" onclick="closeModal()">✕</button>
        <div class="modal-tabs">
            <button class="tab-btn active" type="button" onclick="switchTab('login')">تسجيل الدخول</button>
            <button class="tab-btn" type="button" onclick="switchTab('register')">إنشاء حساب</button>
        </div>
        <div id="login-form">
            <h2>مرحباً بعودتك</h2>
            <p>سجّل دخولك للوصول لملفك ووظائفك</p>
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-input" placeholder="your@email.com">
            </div>
            <div class="form-group">
                <label class="form-label">كلمة المرور</label>
                <input type="password" class="form-input" placeholder="••••••••">
            </div>
            <button class="btn btn-primary btn-full btn-lg" type="button" onclick="loginSuccess()">تسجيل الدخول</button>
            <div class="divider">أو</div>
            <div class="social-btns">
                <button class="social-btn" type="button">🇬 Google</button>
                <button class="social-btn" type="button">🐙 GitHub</button>
            </div>
            <div class="auth-switch">ليس لديك حساب؟ <a onclick="switchTab('register')">إنشاء حساب جديد</a></div>
        </div>
        <div id="register-form" style="display:none;">
            <h2>انضم إلى تك سوريا</h2>
            <p>أنشئ ملفك واعرض أعمالك للعالم</p>
            <div class="role-select">
                <button class="role-btn active" id="role-seeker" type="button" onclick="selectRole('seeker')">
                    <span class="role-icon">👤</span>
                    <span class="role-name">تقني / مطور</span>
                </button>
                <button class="role-btn" id="role-company" type="button" onclick="selectRole('company')">
                    <span class="role-icon">🏢</span>
                    <span class="role-name">شركة</span>
                </button>
            </div>
            <div class="form-grid" style="gap:14px;">
                <div class="form-group">
                    <label class="form-label">الاسم</label>
                    <input type="text" class="form-input" id="reg-name" placeholder="أحمد الخطيب">
                </div>
                <div class="form-group">
                    <label class="form-label">التخصص</label>
                    <input type="text" class="form-input" id="reg-title" placeholder="مطور React">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-input" placeholder="your@email.com">
            </div>
            <div class="form-group">
                <label class="form-label">كلمة المرور</label>
                <input type="password" class="form-input" placeholder="8 أحرف على الأقل">
            </div>
            <button class="btn btn-primary btn-full btn-lg" type="button" onclick="registerSuccess()">إنشاء الحساب مجاناً 🎉</button>
            <div class="auth-switch">لديك حساب؟ <a onclick="switchTab('login')">تسجيل الدخول</a></div>
        </div>
    </div>
</div>
