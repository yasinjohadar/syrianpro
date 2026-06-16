@extends('admin.layouts.master')

@section('page-title')
    إضافة Disk Mapping
@stop

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        @include('admin.partials.ui.alerts')

        @include('admin.partials.ui.page-header', [
            'breadcrumbs' => [
                ['label' => 'لوحة التحكم', 'url' => route('admin.dashboard')],
                ['label' => 'التخزين السحابي', 'url' => route('admin.storage-disk-mappings.index')],
                ['label' => 'إضافة'],
            ],
            'title' => 'إضافة ربط قرص',
            'subtitle' => 'ربط قرص Laravel بمكان تخزين سحابي',
            'actions' => '<a href="' . route('admin.storage-disk-mappings.index') . '" class="btn btn-light border btn-wave"><i class="ri-arrow-right-line me-1"></i> رجوع</a>',
        ])

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card custom-card form-card">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold fs-15">
                            <i class="ri-links-line me-1 text-primary"></i> بيانات الربط
                        </h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.storage-disk-mappings.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="disk_name" class="form-label">Disk Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('disk_name') is-invalid @enderror" id="disk_name" name="disk_name" value="{{ old('disk_name') }}" required placeholder="images, documents, videos, etc.">
                                <small class="text-muted">مثال: images, documents, videos, attachments, avatars, library</small>
                                @error('disk_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="label" class="form-label">التسمية <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label') }}" required>
                                @error('label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="primary_storage_id" class="form-label">التخزين الأساسي <span class="text-danger">*</span></label>
                                <select class="form-select @error('primary_storage_id') is-invalid @enderror" id="primary_storage_id" name="primary_storage_id" required>
                                    <option value="">اختر التخزين الأساسي</option>
                                    @foreach($storages as $storage)
                                        <option value="{{ $storage->id }}" {{ old('primary_storage_id') == $storage->id ? 'selected' : '' }}>
                                            {{ $storage->name }} ({{ App\Models\AppStorageConfig::DRIVERS[$storage->driver] ?? $storage->driver }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('primary_storage_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">التخزين الاحتياطي (Fallback)</label>
                                @foreach($storages as $storage)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fallback_storage_ids[]" value="{{ $storage->id }}" id="fallback_{{ $storage->id }}">
                                        <label class="form-check-label" for="fallback_{{ $storage->id }}">
                                            {{ $storage->name }} ({{ App\Models\AppStorageConfig::DRIVERS[$storage->driver] ?? $storage->driver }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mb-3">
                                <label class="form-label">أنواع الملفات المدعومة (اختياري)</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="file_types[]" value="image" id="file_type_image">
                                            <label class="form-check-label" for="file_type_image">صور</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="file_types[]" value="document" id="file_type_document">
                                            <label class="form-check-label" for="file_type_document">وثائق</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="file_types[]" value="video" id="file_type_video">
                                            <label class="form-check-label" for="file_type_video">فيديو</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">نشط</label>
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="ri-save-line me-1"></i> حفظ
                                </button>
                                <a href="{{ route('admin.storage-disk-mappings.index') }}" class="btn btn-light border btn-lg px-4">
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

