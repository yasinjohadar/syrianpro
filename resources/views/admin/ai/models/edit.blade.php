@extends('admin.layouts.master')

@section('page-title', 'تعديل موديل AI')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'الذكاء الاصطناعي', 'url' => route('admin.ai.models.index')],
                ['label' => 'تعديل'],
            ],
            'title' => 'تعديل: ' . $entry['name'],
            'subtitle' => 'تحديث إعدادات الموديل وقدراته',
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
                        <form action="{{ route('admin.ai.models.update', $entry['id']) }}" method="POST" id="ai-model-form">
                            @csrf
                            @method('PUT')
                            @include('admin.ai.models._form', ['entry' => $entry, 'mode' => 'edit'])
                            <div class="mt-4 d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="ri-save-line me-1"></i> تحديث
                                </button>
                                <button type="button" class="btn btn-info btn-lg px-4"
                                        onclick="testAiModelConnectionFromForm(this)">
                                    <i class="ri-wifi-line me-1"></i> فحص الاتصال
                                </button>
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
@include('admin.ai.models._scripts', ['mode' => 'edit', 'catalog' => $catalog ?? []])
@endpush
