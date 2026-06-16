<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackupSettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BackupSettingsController extends Controller
{
    public function __construct(
        protected BackupSettingsService $backupSettings
    ) {
        $this->middleware('permission:backup-settings-view')->only('index');
        $this->middleware('permission:backup-settings-edit')->only(['update', 'testWebhook']);
    }

    public function index()
    {
        $this->backupSettings->initializeDefaults();
        $settings = $this->backupSettings->getSettings();

        return view('admin.settings.backup.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'notify_email' => ['nullable', 'email', 'max:255'],
            'webhook_url' => ['nullable', 'url', 'max:500'],
            'default_retention_days' => ['required', 'integer', 'min:1', 'max:365'],
            'job_timeout' => ['required', 'integer', 'min:60', 'max:3600'],
        ], [], [
            'notify_email' => 'بريد التنبيهات',
            'webhook_url' => 'رابط Webhook',
            'default_retention_days' => 'أيام الاحتفاظ الافتراضية',
            'job_timeout' => 'مهلة المهمة (ثانية)',
        ]);

        $payload = array_merge($validated, [
            'notifications_enabled' => $request->boolean('notifications_enabled'),
            'use_queue' => $request->boolean('use_queue'),
            'sync_in_local' => $request->boolean('sync_in_local'),
            'prefer_mysqldump' => $request->boolean('prefer_mysqldump'),
        ]);

        $this->backupSettings->updateSettings($payload);

        return redirect()
            ->route('admin.settings.backup.index')
            ->with('success', 'تم حفظ إعدادات النسخ الاحتياطي بنجاح');
    }

    public function testWebhook(Request $request)
    {
        $url = $this->backupSettings->webhookUrl();

        if (! $url) {
            return back()->with('error', 'لم يتم تعيين رابط Webhook. احفظ الإعدادات أولاً.');
        }

        try {
            $response = Http::timeout(10)->post($url, [
                'type' => 'test',
                'message' => 'اختبار اتصال من لوحة التحكم',
                'timestamp' => now()->toIso8601String(),
            ]);

            if ($response->successful()) {
                return back()->with('success', 'تم إرسال رسالة الاختبار إلى Webhook بنجاح.');
            }

            return back()->with('error', 'فشل الاختبار. رمز الاستجابة: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Backup webhook test failed: ' . $e->getMessage());

            return back()->with('error', 'فشل الاتصال: ' . $e->getMessage());
        }
    }
}
