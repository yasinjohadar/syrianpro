@extends('admin.layouts.master')

@section('page-title', $mode === 'custom' ? 'إضافة موديل مخصص' : 'إضافة موديل AI')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الذكاء الاصطناعي', 'url' => route('admin.ai.models.index')],
                ['label' => 'إضافة'],
            ],
            'title' => $mode === 'custom' ? 'إضافة موديل مخصص' : 'إضافة موديل من الكتالوج',
            'subtitle' => $mode === 'custom'
                ? 'أدخل اسم الموديل ومعرّف API يدوياً كما في لوحة المزود'
                : 'اختر من الكتالوج أو <a href="' . route('admin.ai.models.create', ['mode' => 'custom']) . '">أضف موديلاً مخصصاً</a>',
            'actions' => '<a href="' . route('admin.ai.models.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card form-card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15">
                            <i class="ri-cpu-line me-1 text-primary"></i> بيانات الموديل
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.ai.models.store') }}" method="POST" id="ai-model-form">
                            @csrf
                            @include('admin.ai.models._form', ['mode' => $mode])
                            <div class="mt-4 d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="ri-save-line me-1"></i> حفظ الموديل
                                </button>
                                <button type="button" class="btn btn-info btn-lg px-4"
                                        onclick="testAiModelConnectionFromForm(this)">
                                    <i class="ri-wifi-line me-1"></i> فحص الاتصال
                                </button>
                                <a href="{{ route('admin.ai.models.index') }}" class="btn btn-light border btn-lg px-4">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('admin.ai.models._test-connection')

@push('scripts')
@include('admin.ai.models._scripts', ['mode' => $mode, 'catalog' => $catalog])
@endpush
