<div class="card custom-card form-card mb-0">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">البريد الإلكتروني</label>
            <input type="email" name="site_email" class="form-control form-input-enhanced @error('site_email') is-invalid @enderror"
                   value="{{ old('site_email', $settings['site_email'] ?? '') }}" placeholder="info@example.com" dir="ltr">
            @error('site_email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">رقم الهاتف</label>
            <input type="text" name="site_phone" class="form-control form-input-enhanced @error('site_phone') is-invalid @enderror"
                   value="{{ old('site_phone', $settings['site_phone'] ?? '') }}" placeholder="+963 XXX XXX XXX" dir="ltr">
            @error('site_phone')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">رقم الواتساب</label>
            <input type="text" name="site_whatsapp" class="form-control form-input-enhanced @error('site_whatsapp') is-invalid @enderror"
                   value="{{ old('site_whatsapp', $settings['site_whatsapp'] ?? '') }}" placeholder="963912345678" dir="ltr">
            <small class="text-muted fs-12">يُستخدم لزر واتساب في الموقع (wa.me)</small>
            @error('site_whatsapp')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">العنوان</label>
            <input type="text" name="site_address" class="form-control form-input-enhanced @error('site_address') is-invalid @enderror"
                   value="{{ old('site_address', $settings['site_address'] ?? '') }}" placeholder="سوريا">
            @error('site_address')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-0">
            <label class="form-label fw-semibold">ساعات العمل</label>
            <input type="text" name="site_working_hours" class="form-control form-input-enhanced @error('site_working_hours') is-invalid @enderror"
                   value="{{ old('site_working_hours', $settings['site_working_hours'] ?? '') }}" placeholder="السبت - الخميس: 9:00 ص - 6:00 م">
            @error('site_working_hours')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
