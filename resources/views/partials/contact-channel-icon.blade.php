@php
    use App\Models\Company;

    $variant = $variant ?? 'remix';
    $channel = $channel ?? 'social';
    $platform = $platform ?? 'other';
    $size = $size ?? 'fs-18';
    $wrapperClass = $wrapperClass ?? '';

    $meta = match ($channel) {
        'email' => Company::contactEmailMeta(),
        'website' => Company::contactWebsiteMeta(),
        default => Company::socialPlatformMeta($platform),
    };
@endphp

@if($variant === 'remix')
    <span class="contact-channel-icon d-inline-flex align-items-center justify-content-center {{ $wrapperClass }}" aria-hidden="true">
        <i class="{{ $meta['icon'] }} {{ $meta['icon_color'] ?? '' }} {{ $size }}"></i>
    </span>
@else
    <span class="contact-channel-icon {{ $wrapperClass }}" aria-hidden="true">{{ $meta['emoji'] }}</span>
@endif
