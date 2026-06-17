@extends('admin.layouts.master')

@section('page-title')توصيات التقنيين@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')], ['label' => 'توصيات التقنيين']],
            'title' => 'توصيات التقنيين',
            'subtitle' => 'إبراز مواهب موصى بها من الإدارة',
            'actions' => '<a href="'.route('admin.talent-recommendations.create').'" class="btn btn-primary btn-wave"><i class="ri-add-line me-1"></i> توصية جديدة</a>',
        ])

        <div class="card custom-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>التقني</th>
                            <th>السبب</th>
                            <th>النطاق</th>
                            <th>الأولوية</th>
                            <th>الحالة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recommendations as $rec)
                            <tr>
                                <td>{{ $rec->talent?->name }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($rec->reason, 60) }}</td>
                                <td>{{ $rec->scopeLabel() }}</td>
                                <td>{{ $rec->priority }}</td>
                                <td>
                                    <span class="badge {{ $rec->is_active ? 'bg-success-transparent' : 'bg-secondary-transparent' }}">
                                        {{ $rec->is_active ? 'نشط' : 'موقوف' }}
                                    </span>
                                </td>
                                <td>
                                    @if($rec->is_active)
                                        <form method="POST" action="{{ route('admin.talent-recommendations.destroy', $rec) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light">إيقاف</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">لا توجد توصيات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($recommendations->hasPages())<div class="card-footer">{{ $recommendations->links() }}</div>@endif
        </div>
    </div>
</div>
@stop
