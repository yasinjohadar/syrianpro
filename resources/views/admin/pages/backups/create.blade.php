@extends('admin.layouts.master')

@section('page-title')
    إنشاء نسخة احتياطية
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'النسخ الاحتياطي', 'url' => route('admin.backups.index')],
                ['label' => 'إنشاء'],
            ],
            'title' => 'إنشاء نسخة احتياطية',
            'subtitle' => 'نسخ يدوي فوري لقاعدة البيانات أو الملفات',
            'actions' => '<a href="' . route('admin.backups.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card form-card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15">
                            <i class="ri-database-2-line me-1 text-primary"></i> بيانات النسخة
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.backups.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">اسم النسخة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', 'backup_' . now()->format('Y-m-d_H-i-s')) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="backup_type" class="form-label">نوع النسخ <span class="text-danger">*</span></label>
                                    <select class="form-select @error('backup_type') is-invalid @enderror" id="backup_type" name="backup_type" required>
                                        @foreach($backupTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('backup_type', 'database') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('backup_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="compression_type" class="form-label">نوع الضغط <span class="text-danger">*</span></label>
                                    <select class="form-select @error('compression_type') is-invalid @enderror" id="compression_type" name="compression_type" required>
                                        @foreach($compressionTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('compression_type', 'zip') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('compression_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="storage_config_id" class="form-label">مكان التخزين <span class="text-danger">*</span></label>
                                    <select class="form-select @error('storage_config_id') is-invalid @enderror" id="storage_config_id" name="storage_config_id" required>
                                        <option value="">اختر مكان التخزين</option>
                                        @foreach($storageConfigs as $config)
                                            <option value="{{ $config->id }}" {{ old('storage_config_id') == $config->id ? 'selected' : '' }}>{{ $config->name }} ({{ \App\Models\AppStorageConfig::DRIVERS[$config->driver] ?? $config->driver }})</option>
                                        @endforeach
                                    </select>
                                    @error('storage_config_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($storageConfigs->isEmpty())
                                        <small class="text-danger">لا توجد أماكن تخزين نشطة. يرجى <a href="{{ route('admin.storage.create') }}">إضافة مكان تخزين</a> أولاً.</small>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="retention_days" class="form-label">أيام الاحتفاظ <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('retention_days') is-invalid @enderror" id="retention_days" name="retention_days" value="{{ old('retention_days', 30) }}" min="1" max="365" required>
                                    @error('retention_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @include('admin.pages.backups.partials.scope-form')

                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="ri-save-line me-1"></i> إنشاء النسخة
                                </button>
                                <a href="{{ route('admin.backups.index') }}" class="btn btn-light border btn-lg px-4">
                                    إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

