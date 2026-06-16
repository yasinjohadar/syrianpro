<nav class="settings-nav" id="settingsNav" aria-label="أقسام إعدادات الموقع">
    @foreach($sections as $id => $section)
        <button type="button"
                class="settings-nav__item {{ ($activeSection ?? config('site-settings.default')) === $id ? 'is-active' : '' }}"
                id="settings-nav-{{ $id }}"
                data-section="{{ $id }}"
                aria-selected="{{ ($activeSection ?? config('site-settings.default')) === $id ? 'true' : 'false' }}">
            <span class="settings-nav__icon"><i class="{{ $section['icon'] }}"></i></span>
            <span class="settings-nav__label">{{ $section['label'] }}</span>
        </button>
    @endforeach
</nav>
