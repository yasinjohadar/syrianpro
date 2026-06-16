@extends('admin.layouts.master')

@section('page-title')
    إنشاء دور جديد
@stop

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            @include('admin.partials.ui.alerts')

            @include('admin.partials.ui.page-header', [
                'title' => 'إنشاء دور جديد',
                'subtitle' => 'أضف دوراً جديداً وحدّد صلاحياته',
                'actions' => '<a href="' . route('admin.roles.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع للقائمة</a>',
            ])

            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf

                <div class="card custom-card form-card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15">
                            <i class="ri-shield-user-line me-1 text-primary"></i> بيانات الدور
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-5">
                                <label class="form-label fw-semibold">اسم الدور</label>
                                <input type="text" class="form-control form-input-enhanced @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" placeholder="مثال: مشرف عام" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card custom-card form-card mb-4">
                    <div class="card-body">
                        @include('admin.pages.roles.partials.permissions-form')
                    </div>
                </div>

                <div class="card custom-card form-card">
                    <div class="card-body py-3">
                        <div class="form-actions border-0 pt-0 mt-0">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-light border px-4">
                                <i class="ri-close-line me-1"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary px-4 btn-wave">
                                <i class="ri-save-line me-1"></i> حفظ الدور
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@stop
