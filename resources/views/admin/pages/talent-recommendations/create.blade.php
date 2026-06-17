@extends('admin.layouts.master')

@section('page-title')توصية جديدة@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">
        @include('admin.partials.ui.alerts')
        <div class="card custom-card form-card">
            <div class="card-header"><h6 class="mb-0">أوصِ بتقني</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.talent-recommendations.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">التقني</label>
                            <select name="talent_id" class="form-select" required>
                                <option value="">— اختر —</option>
                                @foreach($talents as $t)
                                    <option value="{{ $t->id }}" @selected(old('talent_id', $talent?->id) == $t->id)>{{ $t->name }} — {{ $t->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">النطاق</label>
                            <select name="scope" class="form-select" required>
                                @foreach($scopes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('scope') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">التخصص (عند اختيار تخصص)</label>
                            <select name="scope_id" class="form-select">
                                <option value="">—</option>
                                @foreach($specialties as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">الأولوية</label>
                            <input type="number" name="priority" class="form-control" value="{{ old('priority', 0) }}" min="0" max="100">
                        </div>
                        <div class="col-12">
                            <label class="form-label">سبب التوصية</label>
                            <textarea name="reason" class="form-control" rows="3" required maxlength="500">{{ old('reason') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">تاريخ انتهاء (اختياري)</label>
                            <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">حفظ التوصية</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
