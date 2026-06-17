@php
    use App\Models\Company;

    $profile = $profile ?? $company ?? $talent ?? null;
    $socialPlatforms = Company::socialPlatforms();

    $contactEmails = old('contact_emails', $profile && method_exists($profile, 'resolvedContactEmails') ? $profile->resolvedContactEmails() : ($profile?->contact_emails ?? []));
    if ($contactEmails === []) {
        $contactEmails = [['label' => '', 'email' => '']];
    }

    $contactWebsites = old('contact_websites', $profile && method_exists($profile, 'resolvedContactWebsites') ? $profile->resolvedContactWebsites() : ($profile?->contact_websites ?? []));
    if ($contactWebsites === [] && $profile instanceof Company && $profile->website) {
        $contactWebsites = [['label' => 'الموقع الرئيسي', 'url' => $profile->website]];
    }
    if ($contactWebsites === []) {
        $contactWebsites = [['label' => '', 'url' => '']];
    }

    $socialLinks = old('social_links', $profile && method_exists($profile, 'resolvedSocialLinks') ? $profile->resolvedSocialLinks() : ($profile?->social_links ?? []));
    if ($socialLinks === []) {
        $socialLinks = [['platform' => 'linkedin', 'label' => '', 'url' => '']];
    }

    $socialPlatformIcons = collect($socialPlatforms)->mapWithKeys(function ($meta, $key) {
        return [$key => ['icon' => $meta['icon'], 'color' => $meta['icon_color'] ?? '']];
    })->all();
@endphp

<div class="card custom-card form-card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold fs-15"><i class="ri-contacts-book-line me-1 text-primary"></i> التواصل والروابط</h6>
        <span class="text-muted fs-12">أضف أكثر من بريد أو موقع أو حساب تواصل</span>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label fw-semibold mb-0"><i class="ri-mail-line me-1"></i> عناوين البريد</label>
                <button type="button" class="btn btn-sm btn-primary-light btn-wave dynamic-list-add" data-list="contact-emails">
                    <i class="ri-add-line"></i> إضافة بريد
                </button>
            </div>
            <div id="contact-emails-list" class="dynamic-list" data-prefix="contact_emails">
                @foreach($contactEmails as $index => $row)
                    <div class="dynamic-list-row row g-2 align-items-end mb-2">
                        <div class="col-md-4">
                            <input type="text" name="contact_emails[{{ $index }}][label]" class="form-control form-input-enhanced"
                                   value="{{ $row['label'] ?? '' }}" placeholder="مثال: التوظيف">
                        </div>
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    @include('partials.contact-channel-icon', ['channel' => 'email', 'size' => 'fs-16'])
                                </span>
                                <input type="email" name="contact_emails[{{ $index }}][email]" class="form-control form-input-enhanced border-start-0 @error('contact_emails.'.$index.'.email') is-invalid @enderror"
                                       value="{{ $row['email'] ?? '' }}" placeholder="hello@example.com">
                                @error('contact_emails.'.$index.'.email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-light border dynamic-list-remove" title="حذف">
                                <i class="ri-delete-bin-line text-danger"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label fw-semibold mb-0"><i class="ri-global-line me-1"></i> المواقع الإلكترونية</label>
                <button type="button" class="btn btn-sm btn-primary-light btn-wave dynamic-list-add" data-list="contact-websites">
                    <i class="ri-add-line"></i> إضافة موقع
                </button>
            </div>
            <div id="contact-websites-list" class="dynamic-list" data-prefix="contact_websites">
                @foreach($contactWebsites as $index => $row)
                    <div class="dynamic-list-row row g-2 align-items-end mb-2">
                        <div class="col-md-4">
                            <input type="text" name="contact_websites[{{ $index }}][label]" class="form-control form-input-enhanced"
                                   value="{{ $row['label'] ?? '' }}" placeholder="مثال: Portfolio">
                        </div>
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    @include('partials.contact-channel-icon', ['channel' => 'website', 'size' => 'fs-16'])
                                </span>
                                <input type="text" name="contact_websites[{{ $index }}][url]" class="form-control form-input-enhanced border-start-0"
                                       value="{{ $row['url'] ?? '' }}" placeholder="example.com أو https://...">
                            </div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-light border dynamic-list-remove" title="حذف">
                                <i class="ri-delete-bin-line text-danger"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label fw-semibold mb-0"><i class="ri-share-line me-1"></i> وسائل التواصل الاجتماعي</label>
                <button type="button" class="btn btn-sm btn-primary-light btn-wave dynamic-list-add" data-list="social-links">
                    <i class="ri-add-line"></i> إضافة رابط
                </button>
            </div>
            <div id="social-links-list" class="dynamic-list" data-prefix="social_links">
                @foreach($socialLinks as $index => $row)
                    @php $platform = $row['platform'] ?? 'linkedin'; @endphp
                    <div class="dynamic-list-row row g-2 align-items-end mb-2">
                        <div class="col-auto social-platform-icon-wrap">
                            <span class="social-platform-icon badge bg-light border d-inline-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                                @include('partials.contact-channel-icon', ['channel' => 'social', 'platform' => $platform, 'size' => 'fs-18'])
                            </span>
                        </div>
                        <div class="col-md-3">
                            <select name="social_links[{{ $index }}][platform]" class="form-select form-input-enhanced social-platform-select">
                                @foreach($socialPlatforms as $key => $meta)
                                    <option value="{{ $key }}" @selected($platform === $key)>{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 social-custom-label {{ $platform === 'other' ? '' : 'd-none' }}">
                            <input type="text" name="social_links[{{ $index }}][label]" class="form-control form-input-enhanced"
                                   value="{{ $row['label'] ?? '' }}" placeholder="اسم المنصة">
                        </div>
                        <div class="social-url-col {{ $platform === 'other' ? 'col-md-4' : 'col-md-7' }}">
                            <input type="text" name="social_links[{{ $index }}][url]" class="form-control form-input-enhanced"
                                   value="{{ $row['url'] ?? '' }}" placeholder="https://...">
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-light border dynamic-list-remove" title="حذف">
                                <i class="ri-delete-bin-line text-danger"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<template id="contact-emails-template">
    <div class="dynamic-list-row row g-2 align-items-end mb-2">
        <div class="col-md-4">
            <input type="text" data-name="label" class="form-control form-input-enhanced" placeholder="مثال: التوظيف">
        </div>
        <div class="col-md-7">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    @include('partials.contact-channel-icon', ['channel' => 'email', 'size' => 'fs-16'])
                </span>
                <input type="email" data-name="email" class="form-control form-input-enhanced border-start-0" placeholder="hello@example.com">
            </div>
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-sm btn-light border dynamic-list-remove" title="حذف">
                <i class="ri-delete-bin-line text-danger"></i>
            </button>
        </div>
    </div>
</template>

<template id="contact-websites-template">
    <div class="dynamic-list-row row g-2 align-items-end mb-2">
        <div class="col-md-4">
            <input type="text" data-name="label" class="form-control form-input-enhanced" placeholder="مثال: Portfolio">
        </div>
        <div class="col-md-7">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    @include('partials.contact-channel-icon', ['channel' => 'website', 'size' => 'fs-16'])
                </span>
                <input type="text" data-name="url" class="form-control form-input-enhanced border-start-0" placeholder="example.com أو https://...">
            </div>
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-sm btn-light border dynamic-list-remove" title="حذف">
                <i class="ri-delete-bin-line text-danger"></i>
            </button>
        </div>
    </div>
</template>

<template id="social-links-template">
    <div class="dynamic-list-row row g-2 align-items-end mb-2">
        <div class="col-auto social-platform-icon-wrap">
            <span class="social-platform-icon badge bg-light border d-inline-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                @include('partials.contact-channel-icon', ['channel' => 'social', 'platform' => 'linkedin', 'size' => 'fs-18'])
            </span>
        </div>
        <div class="col-md-3">
            <select data-name="platform" class="form-select form-input-enhanced social-platform-select">
                @foreach($socialPlatforms as $key => $meta)
                    <option value="{{ $key }}">{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 social-custom-label d-none">
            <input type="text" data-name="label" class="form-control form-input-enhanced" placeholder="اسم المنصة">
        </div>
        <div class="social-url-col col-md-7">
            <input type="text" data-name="url" class="form-control form-input-enhanced" placeholder="https://...">
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-sm btn-light border dynamic-list-remove" title="حذف">
                <i class="ri-delete-bin-line text-danger"></i>
            </button>
        </div>
    </div>
</template>

@push('scripts')
<script>
window.socialPlatformIcons = @json($socialPlatformIcons);
</script>
@endpush

@include('partials.profile.contact-fields-scripts')
