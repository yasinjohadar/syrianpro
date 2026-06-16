{{--
    مودال تأكيد موحّد — استخدم في أي صفحة:

    <x-admin.confirm-modal
        id="delete{{ $item->id }}"
        title="تأكيد الحذف"
        message="لا يمكن التراجع عن هذا الإجراء."
        :subject="$item->name"
        :subject-meta="$item->email"
        :action="route('admin.items.destroy', $item)"
        method="DELETE"
        confirm-text="نعم، احذف"
    />

    — مع صورة/حرف:
        :avatar="..." :avatar-initial="A"

    — مودال ديناميكي (JS):
        dynamic form-id="deleteForm" subject-id="deleteSubject" ...

    — محتوى إضافي (حقول):
        <x-admin.confirm-modal ...> ... حقول ... </x-admin.confirm-modal>
--}}
@props([
    'id',
    'title',
    'message' => null,
    'subject' => null,
    'subjectMeta' => null,
    'avatar' => null,
    'avatarInitial' => null,
    'action' => null,
    'method' => 'POST',
    'variant' => 'danger',
    'icon' => null,
    'confirmText' => 'تأكيد',
    'cancelText' => 'إلغاء',
    'confirmClass' => null,
    'dynamic' => false,
    'formId' => null,
    'subjectId' => null,
    'subjectMetaId' => null,
    'messageId' => null,
    'ajaxConfirm' => false,
    'confirmId' => null,
])

@php
    $icons = [
        'danger'  => 'ri-delete-bin-7-line',
        'warning' => 'ri-alert-line',
        'info'    => 'ri-information-line',
        'success' => 'ri-checkbox-circle-line',
        'primary' => 'ri-shield-check-line',
    ];
    $icon = $icon ?? ($icons[$variant] ?? 'ri-question-line');
    $confirmClass = $confirmClass ?? match ($variant) {
        'warning' => 'btn-warning',
        'primary' => 'btn-primary',
        'success' => 'btn-success',
        'info'    => 'btn-info',
        default   => 'btn-danger',
    };
    $titleClass = match ($variant) {
        'warning' => 'text-warning',
        'primary' => 'text-primary',
        'success' => 'text-success',
        'info'    => 'text-info',
        default   => 'text-danger',
    };
    $hasAvatar = $avatar || $avatarInitial;
    $httpMethod = strtoupper($method);
    $needsMethodOverride = ! in_array($httpMethod, ['GET', 'POST'], true);
    $confirmId = $confirmId ?? ($id . 'ConfirmBtn');
    $useForm = ! $ajaxConfirm;
@endphp

<div class="modal fade modal-user-action modal-confirm modal-confirm--{{ $variant }}"
     id="{{ $id }}"
     tabindex="-1"
     aria-labelledby="{{ $id }}Label"
     aria-hidden="true"
     @if($dynamic) data-dynamic="true" @endif
     @if($ajaxConfirm) data-ajax-confirm="true" @endif>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-confirm__accent" aria-hidden="true"></div>

            @if($useForm)
            <form method="{{ in_array($httpMethod, ['GET', 'POST']) ? $httpMethod : 'POST' }}"
                  @if(!$dynamic && $action) action="{{ $action }}" @endif
                  @if($formId) id="{{ $formId }}" @endif>
                @csrf
                @if($needsMethodOverride)
                    @method($httpMethod)
                @endif
            @endif

                <div class="modal-header">
                    <h5 class="modal-title visually-hidden" id="{{ $id }}Label">{{ $title }}</h5>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>

                <div class="modal-body text-center">
                    @if($hasAvatar)
                        <div class="modal-confirm__avatar-wrap" @if($ajaxConfirm) data-confirm-avatar-wrap @endif>
                            @if($avatar)
                                <img src="{{ $avatar }}" alt="" class="modal-confirm__avatar modal-confirm__avatar--img" @if($ajaxConfirm) data-confirm-avatar-img @endif>
                            @else
                                <span class="modal-confirm__avatar" @if($ajaxConfirm) data-confirm-avatar-initial @endif>{{ $avatarInitial }}</span>
                            @endif
                            <span class="modal-confirm__avatar-badge modal-confirm__avatar-badge--{{ $variant }}" @if($ajaxConfirm) data-confirm-avatar-badge @endif>
                                <i class="{{ $icon }}" @if($ajaxConfirm) data-confirm-icon @endif></i>
                            </span>
                        </div>
                    @else
                        <div class="action-modal-icon action-modal-icon--{{ $variant }}" aria-hidden="true" @if($ajaxConfirm) data-confirm-icon-wrap @endif>
                            <i class="{{ $icon }}" @if($ajaxConfirm) data-confirm-icon @endif></i>
                        </div>
                    @endif

                    <h4 class="modal-confirm__title mb-2 fw-bold {{ $titleClass }}"
                        @if($ajaxConfirm) data-confirm-title @endif>{{ $title }}</h4>

                    @if($subject || $subjectId || $ajaxConfirm)
                        <p class="action-modal-user mb-1"
                           @if($subjectId) id="{{ $subjectId }}" @endif
                           @if($subjectId || $ajaxConfirm) data-confirm-subject @endif>{{ $subject }}</p>
                    @endif

                    @if($subjectMeta || $subjectMetaId || $ajaxConfirm)
                        <p class="modal-confirm__meta mb-3"
                           @if($subjectMetaId) id="{{ $subjectMetaId }}" @endif
                           @if($subjectMetaId || $ajaxConfirm) data-confirm-subject-meta @endif>{{ $subjectMeta }}</p>
                    @endif

                    @if($message || $messageId || $ajaxConfirm)
                        <p class="modal-confirm__message mb-0"
                           @if($messageId) id="{{ $messageId }}" @endif
                           @if($messageId || $ajaxConfirm) data-confirm-message @endif>{{ $message }}</p>
                    @endif

                    @if(trim($slot))
                        <div class="modal-confirm__extra mt-4">
                            {{ $slot }}
                        </div>
                    @endif
                </div>

                <div class="modal-footer justify-content-center flex-wrap">
                    <button type="button" class="btn btn-light border btn-lg px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i> {{ $cancelText }}
                    </button>
                    @if($ajaxConfirm)
                        <button type="button" id="{{ $confirmId }}" class="btn {{ $confirmClass }} btn-lg px-4 modal-confirm__submit" data-confirm-submit>
                            <i class="{{ $icon }} me-1" data-confirm-submit-icon></i>
                            <span data-confirm-submit-text>{{ $confirmText }}</span>
                        </button>
                    @else
                        <button type="submit" class="btn {{ $confirmClass }} btn-lg px-4 modal-confirm__submit">
                            @if($variant === 'danger')
                                <i class="ri-delete-bin-line me-1"></i>
                            @elseif($variant === 'warning')
                                <i class="ri-lock-password-line me-1"></i>
                            @else
                                <i class="ri-check-line me-1"></i>
                            @endif
                            {{ $confirmText }}
                        </button>
                    @endif
                </div>

            @if($useForm)
            </form>
            @endif
        </div>
    </div>
</div>
