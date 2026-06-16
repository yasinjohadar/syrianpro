<div class="card custom-card form-card mb-0">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="ri-facebook-circle-line me-1 text-primary"></i> فيسبوك</label>
            <input type="url" name="facebook_url" class="form-control form-input-enhanced @error('facebook_url') is-invalid @enderror"
                   value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" placeholder="https://facebook.com/..." dir="ltr">
            @error('facebook_url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="ri-youtube-line me-1 text-danger"></i> يوتيوب</label>
            <input type="url" name="youtube_url" class="form-control form-input-enhanced @error('youtube_url') is-invalid @enderror"
                   value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" placeholder="https://youtube.com/..." dir="ltr">
            @error('youtube_url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="ri-instagram-line me-1 text-danger"></i> انستغرام</label>
            <input type="url" name="instagram_url" class="form-control form-input-enhanced @error('instagram_url') is-invalid @enderror"
                   value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" placeholder="https://instagram.com/..." dir="ltr">
            @error('instagram_url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="ri-linkedin-box-line me-1 text-info"></i> لينكد إن</label>
            <input type="url" name="linkedin_url" class="form-control form-input-enhanced @error('linkedin_url') is-invalid @enderror"
                   value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}" placeholder="https://linkedin.com/..." dir="ltr">
            @error('linkedin_url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="ri-github-line me-1"></i> جيت هاب</label>
            <input type="url" name="github_url" class="form-control form-input-enhanced @error('github_url') is-invalid @enderror"
                   value="{{ old('github_url', $settings['github_url'] ?? '') }}" placeholder="https://github.com/..." dir="ltr">
            @error('github_url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-0">
            <label class="form-label fw-semibold"><i class="ri-telegram-line me-1 text-info"></i> تليجرام</label>
            <input type="url" name="telegram_url" class="form-control form-input-enhanced @error('telegram_url') is-invalid @enderror"
                   value="{{ old('telegram_url', $settings['telegram_url'] ?? '') }}" placeholder="https://t.me/..." dir="ltr">
            @error('telegram_url')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
