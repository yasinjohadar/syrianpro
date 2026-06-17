@extends('company.layouts.master')

@section('page-title')
أضف وظيفة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'وظائفي', 'url' => route('company.jobs.index')],
                ['label' => 'إضافة'],
            ],
            'title' => 'أضف وظيفة Remote',
            'subtitle' => 'انشر فرصة عمل واجذب المواهب السورية — USD · Wise · PayPal',
            'actions' => '<a href="' . route('company.jobs.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        @include('company.partials.jobs.form', ['job' => null])
    </div>
</div>
@stop
