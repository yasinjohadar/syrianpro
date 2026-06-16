<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StorageSettingsService;
use Illuminate\Http\Request;

class StorageSettingsController extends Controller
{
    public function __construct(
        protected StorageSettingsService $storageSettings
    ) {
        $this->middleware('permission:storage-settings-view')->only('index');
        $this->middleware('permission:storage-settings-edit')->only('update');
    }

    public function index()
    {
        $this->storageSettings->initializeDefaults();
        $settings = $this->storageSettings->getSettings();
        $driverModes = $this->storageSettings->driverModes();

        return view('admin.settings.storage.index', compact('settings', 'driverModes'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'storage_driver_mode' => ['required', 'string', 'in:local_only,cloud_only,cloud_first,local_first,dual_write'],
            'storage_fallback_disk' => ['required', 'string', 'max:64'],
            'storage_default_cloud_disk' => ['nullable', 'string', 'max:64'],
            'storage_sync_queue' => ['required', 'string', 'max:64'],
            'storage_sync_retries' => ['required', 'integer', 'min:0', 'max:10'],
            'storage_sync_backoff_seconds' => ['required', 'integer', 'min:5', 'max:600'],
            'storage_max_upload_size_mb' => ['required', 'integer', 'min:1', 'max:5000'],
            'storage_retention_days' => ['required', 'integer', 'min:1', 'max:365'],
        ], [], [
            'storage_driver_mode' => 'وضع التخزين',
            'storage_fallback_disk' => 'القرص المحلي الاحتياطي',
            'storage_max_upload_size_mb' => 'أقصى حجم رفع',
        ]);

        $payload = array_merge($validated, [
            'storage_media_presigned_urls' => $request->boolean('storage_media_presigned_urls'),
            'storage_deduplication_enabled' => $request->boolean('storage_deduplication_enabled'),
            'storage_auto_generate_thumbnails' => $request->boolean('storage_auto_generate_thumbnails'),
            'storage_auto_optimize_images' => $request->boolean('storage_auto_optimize_images'),
            'storage_virus_scan_enabled' => $request->boolean('storage_virus_scan_enabled'),
            'storage_log_uploads' => $request->boolean('storage_log_uploads'),
        ]);

        $this->storageSettings->updateSettings($payload);

        return redirect()
            ->route('admin.settings.storage.index')
            ->with('success', 'تم حفظ إعدادات التخزين بنجاح');
    }
}
