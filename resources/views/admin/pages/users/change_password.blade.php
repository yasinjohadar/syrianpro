<x-admin.confirm-modal
    :id="'change_password' . $user->id"
    title="تعديل كلمة المرور"
    :subject="$user->name"
    :subject-meta="$user->email"
    :avatar="$user->photo ? asset('storage/' . $user->photo) : null"
    :avatar-initial="mb_strtoupper(mb_substr($user->name, 0, 1))"
    :action="route('admin.users.update-password', $user->id)"
    method="PUT"
    variant="warning"
    icon="ri-key-2-line"
    confirm-text="حفظ كلمة المرور"
    confirm-class="btn-primary"
    :form-id="'changePasswordForm' . $user->id"
>
    <div class="form-fields-panel">
        <div class="mb-3">
            <label for="password{{ $user->id }}" class="form-label fw-semibold">كلمة المرور الجديدة</label>
            <div class="password-input-wrap">
                <input type="password" name="password" id="password{{ $user->id }}"
                       class="form-control form-control-lg" required autocomplete="new-password"
                       minlength="8" placeholder="أدخل كلمة مرور قوية">
                <button type="button" class="password-toggle-btn" data-target="password{{ $user->id }}" aria-label="إظهار كلمة المرور">
                    <i class="ri-eye-line"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-0">
            <label for="password_confirmation{{ $user->id }}" class="form-label fw-semibold">تأكيد كلمة المرور</label>
            <div class="password-input-wrap">
                <input type="password" name="password_confirmation" id="password_confirmation{{ $user->id }}"
                       class="form-control form-control-lg" required autocomplete="new-password"
                       minlength="8" placeholder="أعد إدخال كلمة المرور">
                <button type="button" class="password-toggle-btn" data-target="password_confirmation{{ $user->id }}" aria-label="إظهار كلمة المرور">
                    <i class="ri-eye-line"></i>
                </button>
            </div>
        </div>
    </div>
</x-admin.confirm-modal>
