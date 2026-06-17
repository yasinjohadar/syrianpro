@extends('company.layouts.master')

@section('page-title')
تعديل وظيفة
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة الشركة', 'url' => route('company.dashboard')],
                ['label' => 'وظائفي', 'url' => route('company.jobs.index')],
                ['label' => 'تعديل'],
            ],
            'title' => 'تعديل الوظيفة',
            'subtitle' => $job->title,
            'actions' => '<a href="' . route('company.jobs.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        @include('company.partials.jobs.form')
    </div>
</div>
@stop
