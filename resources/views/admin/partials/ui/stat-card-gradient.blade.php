{{--
    @include('admin.partials.ui.stat-card-gradient', [
        'variant' => 'purple', // purple|green|cyan|orange
        'icon' => 'ri-group-line',
        'label' => 'إجمالي المستخدمين',
        'value' => '1,368',
        'hint' => 'حسب الفلاتر الحالية',
        'col' => 'col-sm-6 col-xl-3',
    ])
--}}
<div class="{{ $col ?? 'col-sm-6 col-xl-3' }}">
    <div class="stat-card-gradient stat-card-gradient--{{ $variant ?? 'purple' }}">
        <div class="stat-card-gradient__icon"><i class="{{ $icon }}"></i></div>
        <div class="stat-card-gradient__body">
            <div class="stat-card-gradient__label">{{ $label }}</div>
            <div class="stat-card-gradient__value">{{ $value }}</div>
            @if(!empty($hint))
                <div class="stat-card-gradient__hint">{{ $hint }}</div>
            @endif
        </div>
    </div>
</div>
