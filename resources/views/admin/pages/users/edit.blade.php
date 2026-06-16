@extends('admin.layouts.master')

@section('page-title')
    تعديل المستخدم
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'المستخدمون', 'url' => route('admin.users.index')],
                ['label' => $user->name],
            ],
            'title' => 'تعديل المستخدم',
            'subtitle' => $user->email,
            'actions' => '<a href="' . route('admin.users.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-4 order-lg-2">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-image-line me-1 text-primary"></i> صورة المستخدم</h6>
                        </div>
                        <div class="card-body">
                            <div class="user-avatar-upload-wrap">
                                @php $initial = mb_strtoupper(mb_substr($user->name, 0, 1)); @endphp
                                <div class="user-avatar-preview-wrap" id="photo-preview-wrap">
                                    @if($user->photo)
                                        <img id="photo-preview" class="user-avatar-preview"
                                             src="{{ asset('storage/' . $user->photo) }}" alt="">
                                        <span id="photo-initial" class="user-avatar-initial d-none">{{ $initial }}</span>
                                    @else
                                        <img id="photo-preview" class="user-avatar-preview d-none" src="" alt="">
                                        <span id="photo-initial" class="user-avatar-initial">{{ $initial }}</span>
                                    @endif
                                </div>
                                <label for="photo-input" class="user-avatar-upload-btn">
                                    <i class="ri-camera-line"></i> تغيير الصورة
                                </label>
                                <input type="file" name="photo" id="photo-input" accept="image/*">
                            </div>
                            @error('photo')
                                <div class="text-danger small mt-2 text-center">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-shield-check-line me-1 text-primary"></i> حالة الحساب</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">حالة المستخدم</label>
                                <select class="form-select form-input-enhanced @error('status') is-invalid @enderror" name="status">
                                    <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>محظور</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="account-switch-panel">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                           id="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">تفعيل الحساب</label>
                                </div>
                                <p class="text-muted fs-12 mb-0 mt-2">عند التفعيل يمكن للمستخدم تسجيل الدخول.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 order-lg-1">
                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-user-line me-1 text-primary"></i> المعلومات الأساسية</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">الاسم الكامل <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-input-enhanced @error('name') is-invalid @enderror"
                                           name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">اسم المستخدم</label>
                                    <input type="text" class="form-control form-input-enhanced @error('username') is-invalid @enderror"
                                           name="username" value="{{ old('username', $user->username) }}">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-input-enhanced @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email', $user->email) }}" dir="ltr" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">رقم الهاتف</label>
                                    <input type="tel" class="form-control form-input-enhanced @error('phone') is-invalid @enderror"
                                           name="phone" value="{{ old('phone', $user->phone) }}" dir="ltr">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-lock-password-line me-1 text-primary"></i> كلمة المرور</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted fs-13 mb-3">اترك الحقول فارغة إذا لم ترد تغيير كلمة المرور.</p>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control form-input-enhanced @error('password') is-invalid @enderror"
                                           name="password" placeholder="••••••••">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">تأكيد كلمة المرور</label>
                                    <input type="password" class="form-control form-input-enhanced @error('password_confirmation') is-invalid @enderror"
                                           name="password_confirmation" placeholder="••••••••">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card form-card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold fs-15"><i class="ri-shield-user-line me-1 text-primary"></i> الأدوار</h6>
                        </div>
                        <div class="card-body">
                            @php $selectedRoles = old('roles', $user->getRoleNames()->toArray()); @endphp
                            <div class="role-check-grid @error('roles') is-invalid @enderror">
                                @foreach ($roles as $role)
                                    <label class="role-check-chip">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                               {{ in_array($role->name, $selectedRoles) ? 'checked' : '' }}>
                                        <span><i class="ri-user-settings-line"></i> {{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <p class="text-muted fs-12 mb-0 mt-2">يمكن اختيار أكثر من دور للمستخدم.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-card form-card">
                <div class="card-body py-3">
                    <div class="form-actions border-0 pt-0 mt-0">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light border px-4">
                            <i class="ri-close-line me-1"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary px-4 btn-wave">
                            <i class="ri-save-line me-1"></i> حفظ التعديلات
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var photoInput = document.getElementById('photo-input');
    var preview = document.getElementById('photo-preview');
    var initial = document.getElementById('photo-initial');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (e) {
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                if (initial) initial.classList.add('d-none');
            };
            reader.readAsDataURL(this.files[0]);
        });
    }
});
</script>
@endpush
