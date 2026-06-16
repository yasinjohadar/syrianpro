@php
    $presets = app(\App\Services\Backup\BackupScopeResolver::class)->presetOptions();
    $diskMappings = \App\Models\StorageDiskMapping::with('primaryStorage')->get();
    $defaultConfigFiles = config('backup.default_scope.config.files', []);
    $selectedPreset = old('scope_preset', 'standard');
@endphp

<div class="card border mb-4">
    <div class="card-header bg-light py-2">
        <h6 class="mb-0 fw-bold">محتوى النسخة</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-semibold">قالب سريع</label>
            <select name="scope_preset" id="scope_preset" class="form-select">
                @foreach ($presets as $key => $label)
                    <option value="{{ $key }}" @selected($selectedPreset === $key)>{{ $label }}</option>
                @endforeach
                <option value="custom" @selected($selectedPreset === 'custom')>مخصص</option>
            </select>
        </div>

        <div id="scope-custom-fields">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="scope_database" id="scope_database" value="1"
                    @checked(old('scope_database', true))>
                <label class="form-check-label" for="scope_database">قاعدة البيانات</label>
            </div>

            <div class="mb-3 ps-3 border-start">
                <label class="form-label fw-semibold">الملفات</label>
                @if ($diskMappings->isNotEmpty())
                    <p class="text-muted small mb-2">أقراص التخزين المربوطة:</p>
                    @foreach ($diskMappings as $mapping)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="scope_disks[]"
                                value="{{ $mapping->disk_name }}" id="disk_{{ $mapping->disk_name }}"
                                @checked(in_array($mapping->disk_name, old('scope_disks', ['public'])))>
                            <label class="form-check-label" for="disk_{{ $mapping->disk_name }}">
                                {{ $mapping->disk_name }}
                                @if ($mapping->primaryStorage)
                                    <small class="text-muted">({{ $mapping->primaryStorage->name }})</small>
                                @endif
                            </label>
                        </div>
                    @endforeach
                @endif
                <label class="form-label mt-2 small">مسارات إضافية (سطر لكل مسار)</label>
                <textarea name="scope_file_paths_text" class="form-control form-control-sm" rows="2"
                    placeholder="storage/app/public">{{ old('scope_file_paths_text', "storage/app/public\n") }}</textarea>
            </div>

            <div class="mb-2 ps-3 border-start">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="scope_config_enabled" id="scope_config_enabled"
                        value="1" @checked(old('scope_config_enabled', true))>
                    <label class="form-check-label" for="scope_config_enabled">ملفات الإعدادات</label>
                </div>
                @foreach ($defaultConfigFiles as $file)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="scope_config_files[]"
                            value="{{ $file }}" id="cfg_{{ md5($file) }}"
                            @checked(in_array($file, old('scope_config_files', $defaultConfigFiles)))>
                        <label class="form-check-label" for="cfg_{{ md5($file) }}">{{ $file }}</label>
                    </div>
                @endforeach
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="scope_include_env" id="scope_include_env"
                        value="1" @checked(old('scope_include_env', false))>
                    <label class="form-check-label text-warning" for="scope_include_env">تضمين ملف .env (حساس)</label>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const preset = document.getElementById('scope_preset');
    const custom = document.getElementById('scope-custom-fields');
    function toggle() {
        if (custom) custom.style.display = preset?.value === 'custom' ? '' : 'none';
    }
    preset?.addEventListener('change', toggle);
    toggle();
});
</script>
@endpush
