{{--
    نموذج فلترة Ajax موحّد — يُستخدم في كل صفحات الجداول:

    <x-admin.ajax-filter-form
        :action="route('admin.users.index')"
        target="#usersAjaxTarget"
        modals-target="#usersModalsHost"
        count-target="#usersFilteredCount"
        :reset-url="route('admin.users.index')"
        id="usersFilterForm"
    >
        ... حقول الفلتر ...
    </x-admin.ajax-filter-form>
--}}
@props([
    'action',
    'target',
    'modalsTarget' => null,
    'countTarget' => null,
    'resetUrl' => null,
    'toggleUrl' => null,
    'toggleModal' => null,
    'debounce' => 450,
    'statusClass' => 'ajax-filter-status mt-2',
])

<form method="GET"
      action="{{ $action }}"
      data-admin-ajax-filter
      data-target="{{ $target }}"
      @if($modalsTarget) data-modals-target="{{ $modalsTarget }}" @endif
      @if($countTarget) data-count-target="{{ $countTarget }}" @endif
      data-reset-url="{{ $resetUrl ?? $action }}"
      @if($toggleUrl) data-toggle-url="{{ $toggleUrl }}" @endif
      @if($toggleModal) data-toggle-modal="{{ $toggleModal }}" @endif
      data-debounce="{{ $debounce }}"
      {{ $attributes }}>
    {{ $slot }}
    <div class="{{ $statusClass }}" aria-live="polite"></div>
</form>
