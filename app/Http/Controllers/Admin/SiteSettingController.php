<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteSettingsService;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function __construct(
        protected SiteSettingsService $siteSettings
    ) {}

    /**
     * Display site settings form.
     */
    public function index()
    {
        $this->siteSettings->initializeDefaults();
        $settings = $this->siteSettings->getSettings();
        $sections = config('site-settings.sections', []);
        $defaultSection = config('site-settings.default', 'contact');
        $activeSection = session('active_section', $defaultSection);

        if (! array_key_exists($activeSection, $sections)) {
            $activeSection = $defaultSection;
        }

        $sectionKeys = collect($sections)->mapWithKeys(fn ($section, $id) => [$id => $section['keys'] ?? []])->toArray();

        return view('admin.settings.site.index', compact('settings', 'sections', 'activeSection', 'sectionKeys'));
    }

    /**
     * Update site settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_email' => ['nullable', 'string', 'email', 'max:255'],
            'site_phone' => ['nullable', 'string', 'max:50'],
            'site_whatsapp' => ['nullable', 'string', 'max:50'],
            'site_address' => ['nullable', 'string', 'max:500'],
            'site_working_hours' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:500'],
            'youtube_url' => ['nullable', 'string', 'max:500'],
            'instagram_url' => ['nullable', 'string', 'max:500'],
            'linkedin_url' => ['nullable', 'string', 'max:500'],
            'github_url' => ['nullable', 'string', 'max:500'],
            'telegram_url' => ['nullable', 'string', 'max:500'],
        ], [], [
            'site_email' => 'البريد الإلكتروني',
            'site_phone' => 'رقم الهاتف',
            'site_whatsapp' => 'رقم الواتساب',
            'site_address' => 'العنوان',
            'site_working_hours' => 'ساعات العمل',
            'facebook_url' => 'رابط فيسبوك',
            'youtube_url' => 'رابط يوتيوب',
            'instagram_url' => 'رابط انستغرام',
            'linkedin_url' => 'رابط لينكد إن',
            'github_url' => 'رابط جيت هاب',
            'telegram_url' => 'رابط تليجرام',
        ]);

        $this->siteSettings->updateSettings($validated);

        $activeSection = $request->input('_active_section', config('site-settings.default', 'contact'));
        $sections = config('site-settings.sections', []);
        if (! array_key_exists($activeSection, $sections)) {
            $activeSection = config('site-settings.default', 'contact');
        }

        return redirect()
            ->route('admin.settings.site.index')
            ->with('success', 'تم حفظ إعدادات الموقع بنجاح')
            ->with('active_section', $activeSection);
    }
}
