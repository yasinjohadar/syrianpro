@extends('company.layouts.master')

@section('page-title')القائمة المختصرة@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [['label' => 'لوحة الشركة', 'url' => route('company.dashboard')], ['label' => 'القائمة المختصرة']],
            'title' => 'القائمة المختصرة',
            'subtitle' => 'مرشحون مفضلون — خاص بشركتك',
        ])
        <div class="card custom-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>التقني</th><th>التقييم</th><th>تاريخ الإضافة</th><th></th></tr></thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->talent?->name }}</td>
                                <td>{{ $item->fit_rating ? $item->fit_rating.'/5' : '—' }}</td>
                                <td>{{ $item->created_at->translatedFormat('j M Y') }}</td>
                                <td><a href="{{ route('company.talents.show', $item->talent) }}" class="btn btn-sm btn-primary-light">الملف</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">القائمة فارغة</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items->hasPages())<div class="card-footer">{{ $items->links() }}</div>@endif
        </div>
    </div>
</div>
@stop
