@extends('admin.layouts.master')

@section('page-title') {{ isset($company) ? 'تعديل شركة' : 'إضافة شركة' }} @stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')], ['label' => 'الشركات', 'url' => route('admin.companies.index')], ['label' => isset($company) ? 'تعديل' : 'إضافة']],
            'title' => isset($company) ? 'تعديل الشركة' : 'إضافة شركة',
            'actions' => '<a href="' . route('admin.companies.index') . '" class="btn btn-light border">رجوع</a>',
        ])

        <form method="POST" action="{{ isset($company) ? route('admin.companies.update', $company) : route('admin.companies.store') }}" enctype="multipart/form-data">
            @csrf @if(isset($company)) @method('PUT') @endif
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card form-card mb-4">
                        <div class="card-body">
                            <label class="form-label fw-semibold">شعار (emoji)</label>
                            <input type="text" name="logo" class="form-control mb-3" value="{{ old('logo', $company->logo ?? '') }}">
                            <label class="form-label fw-semibold">صورة الشعار</label>
                            <input type="file" name="logo_image" class="form-control mb-3" accept="image/*">
                            @if(isset($company) && $company->logo_image)
                                <div class="form-check"><input type="checkbox" name="remove_logo_image" value="1" id="remove_logo_image"><label for="remove_logo_image">حذف الصورة</label></div>
                            @endif
                            <div class="account-switch-panel mt-3"><div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $company->is_active ?? true))><label class="form-check-label">نشطة</label></div></div>
                            <div class="account-switch-panel mt-2"><div class="form-check form-switch"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $company->is_featured ?? false))><label class="form-check-label">مميزة</label></div></div>
                            <div class="account-switch-panel mt-2"><div class="form-check form-switch"><input type="checkbox" name="is_verified" value="1" @checked(old('is_verified', $company->is_verified ?? false))><label class="form-check-label">موثّقة</label></div></div>
                            <div class="account-switch-panel mt-2"><div class="form-check form-switch"><input type="checkbox" name="is_remote_friendly" value="1" @checked(old('is_remote_friendly', $company->is_remote_friendly ?? true))><label class="form-check-label">Remote-friendly</label></div></div>
                            <div class="account-switch-panel mt-2"><div class="form-check form-switch"><input type="checkbox" name="is_syria_friendly" value="1" @checked(old('is_syria_friendly', $company->is_syria_friendly ?? true))><label class="form-check-label">Syria-friendly</label></div></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card form-card mb-4"><div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-semibold">الاسم *</label><input type="text" name="name" class="form-control" value="{{ old('name', $company->name ?? '') }}" required></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">القطاع *</label><input type="text" name="sector" class="form-control" value="{{ old('sector', $company->sector ?? '') }}" required></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">التصنيف *</label>
                                <select name="category" class="form-select" required>
                                    @foreach(['tech' => 'تقنية', 'design' => 'تصميم', 'data' => 'بيانات', 'education' => 'تعليم'] as $val => $label)
                                        <option value="{{ $val }}" @selected(old('category', $company->category ?? 'tech') === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4"><label class="form-label fw-semibold">الموقع *</label><input type="text" name="location" class="form-control" value="{{ old('location', $company->location ?? '') }}" required></div>
                            <div class="col-md-4"><label class="form-label fw-semibold">عدد الوظائف</label><input type="number" name="jobs_count" min="0" class="form-control" value="{{ old('jobs_count', $company->jobs_count ?? 0) }}"></div>
                            <div class="col-md-3"><label class="form-label fw-semibold">التقييم</label><input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" value="{{ old('rating', $company->rating ?? 0) }}"></div>
                            <div class="col-md-3"><label class="form-label fw-semibold">التأسيس</label><input type="text" name="founded" class="form-control" value="{{ old('founded', $company->founded ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label fw-semibold">حجم الفريق</label><input type="text" name="team_size" class="form-control" value="{{ old('team_size', $company->team_size ?? '') }}"></div>
                            <div class="col-md-3"><label class="form-label fw-semibold">الترتيب</label><input type="number" name="order" min="0" class="form-control" value="{{ old('order', $company->order ?? 0) }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">الموقع الإلكتروني</label><input type="text" name="website" class="form-control" value="{{ old('website', $company->website ?? '') }}"></div>
                            <div class="col-md-6"><label class="form-label fw-semibold">Timezone</label><input type="text" name="timezone" class="form-control" value="{{ old('timezone', $company->timezone ?? '') }}"></div>
                            <div class="col-12"><label class="form-label fw-semibold">طرق الدفع</label><input type="text" name="payment_methods_text" class="form-control" value="{{ old('payment_methods_text', isset($company) ? implode(', ', $company->payment_methods ?? []) : '') }}"></div>
                            <div class="col-12"><label class="form-label fw-semibold">التقنيات</label><input type="text" name="tech_stack_text" class="form-control" value="{{ old('tech_stack_text', isset($company) ? implode(', ', $company->tech_stack ?? []) : '') }}"></div>
                            <div class="col-12"><label class="form-label fw-semibold">نبذة</label><textarea name="about" rows="3" class="form-control">{{ old('about', $company->about ?? '') }}</textarea></div>
                            <div class="col-12"><label class="form-label fw-semibold">الرؤية</label><textarea name="mission" rows="2" class="form-control">{{ old('mission', $company->mission ?? '') }}</textarea></div>
                            <div class="col-12"><label class="form-label fw-semibold">القيم (سطر لكل قيمة)</label><textarea name="values_text" rows="3" class="form-control">{{ old('values_text', isset($company) ? implode("\n", $company->values ?? []) : '') }}</textarea></div>
                            <div class="col-12"><label class="form-label fw-semibold">المميزات</label><textarea name="perks_text" rows="3" class="form-control">{{ old('perks_text', isset($company) ? implode("\n", $company->perks ?? []) : '') }}</textarea></div>
                            <div class="col-12"><label class="form-label fw-semibold">ثقافة العمل</label><textarea name="culture_text" rows="3" class="form-control">{{ old('culture_text', isset($company) ? implode("\n", $company->culture ?? []) : '') }}</textarea></div>
                        </div>
                    </div></div>
                    <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> حفظ</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
