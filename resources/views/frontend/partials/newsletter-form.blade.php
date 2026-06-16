@php
    $variant = $variant ?? 'cta';
    $source = $source ?? 'general';
@endphp
<form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form newsletter-form-{{ $variant }}">
    @csrf
    <input type="hidden" name="source" value="{{ $source }}">
    @if(session('newsletter_success'))
        <div class="newsletter-message newsletter-success">
            <i class="fas fa-check-circle"></i> {{ session('newsletter_success') }}
        </div>
    @endif
    @if($errors->has('newsletter_email'))
        <div class="newsletter-message newsletter-error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first('newsletter_email') }}
        </div>
    @endif
    <div class="newsletter-form-inner">
        <input type="email" name="newsletter_email" value="{{ old('newsletter_email') }}" placeholder="بريدك الإلكتروني..." required
            class="newsletter-input @error('newsletter_email') is-invalid @enderror"
            aria-label="البريد الإلكتروني">
        <button type="submit" class="newsletter-btn">
            <i class="fas fa-paper-plane"></i> {{ ($variant ?? '') === 'home' ? 'اشترك الآن' : 'اشتراك' }}
        </button>
    </div>
</form>
