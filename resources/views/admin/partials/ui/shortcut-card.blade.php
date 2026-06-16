{{--
    @include('admin.partials.ui.shortcut-card', [
        'url' => route('admin.users.index'),
        'title' => 'المستخدمون',
        'description' => 'إدارة المستخدمين',
        'icon' => 'ri-group-line',
        'icon_color' => 'primary',
        'badge' => null,
        'col' => 'col-lg-2 col-md-4 col-sm-6',
    ])
--}}
<div class="{{ $col ?? 'col-lg-2 col-md-4 col-sm-6' }}">
    <a href="{{ $url }}" class="shortcut-card text-decoration-none">
        <div class="shortcut-card__icon shortcut-card__icon--{{ $icon_color ?? 'primary' }}">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="shortcut-card__title">{{ $title }}</div>
        <div class="shortcut-card__desc">{{ $description }}</div>
        @if(!empty($badge))
            <span class="shortcut-card__badge">{{ $badge }}</span>
        @endif
    </a>
</div>
