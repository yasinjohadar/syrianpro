<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Concerns\ParsesContactChannels;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use ParsesContactChannels;

    public function __construct()
    {
        $this->middleware(['auth', 'check.user.active', 'role:company']);
    }

    public function edit(Request $request): View
    {
        return view('company.pages.profile.edit', [
            'user' => $request->user(),
            'company' => $request->user()->company,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $company = $user->company;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:50',
            'team_size' => 'nullable|string|max:50',
            'founded' => 'nullable|string|max:20',
            'logo' => 'nullable|string|max:10',
            'logo_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'about' => 'nullable|string',
            'mission' => 'nullable|string',
            'payment_methods_text' => 'nullable|string',
            'tech_stack_text' => 'nullable|string',
            'perks_text' => 'nullable|string',
            'contact_emails' => 'nullable|array',
            'contact_emails.*.label' => 'nullable|string|max:100',
            'contact_emails.*.email' => 'nullable|email|max:255',
            'contact_websites' => 'nullable|array',
            'contact_websites.*.label' => 'nullable|string|max:100',
            'contact_websites.*.url' => 'nullable|string|max:500',
            'social_links' => 'nullable|array',
            'social_links.*.platform' => 'nullable|string|max:50',
            'social_links.*.label' => 'nullable|string|max:100',
            'social_links.*.url' => 'nullable|string|max:500',
            'is_remote_friendly' => 'nullable|boolean',
            'is_syria_friendly' => 'nullable|boolean',
            'remove_logo_image' => 'nullable|boolean',
        ], [
            'name.required' => 'اسم الشركة مطلوب',
            'contact_emails.*.email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
        ]);

        if ($request->hasFile('logo_image')) {
            if ($company?->logo_image) {
                Storage::disk('public')->delete($company->logo_image);
            }
            $validated['logo_image'] = $request->file('logo_image')->store('companies/logos', 'public');
        }

        if ($request->boolean('remove_logo_image') && $company?->logo_image) {
            Storage::disk('public')->delete($company->logo_image);
            $validated['logo_image'] = null;
        }

        $validated['payment_methods'] = $this->csvToArray($request->input('payment_methods_text'));
        $validated['tech_stack'] = $this->csvToArray($request->input('tech_stack_text'));
        $validated['perks'] = $this->linesToArray($request->input('perks_text'));
        $validated['contact_emails'] = $this->parseLabeledRows($request->input('contact_emails'), 'email');
        $validated['contact_websites'] = $this->parseLabeledRows($request->input('contact_websites'), 'url');
        $validated['social_links'] = $this->parseSocialLinks($request->input('social_links'));
        $validated['website'] = $this->legacyWebsiteFromWebsites($validated['contact_websites']);
        $validated['is_remote_friendly'] = $request->boolean('is_remote_friendly', true);
        $validated['is_syria_friendly'] = $request->boolean('is_syria_friendly', true);
        $validated['user_id'] = $user->id;

        unset(
            $validated['payment_methods_text'],
            $validated['tech_stack_text'],
            $validated['perks_text'],
            $validated['remove_logo_image']
        );

        if ($company) {
            if ($company->name !== $validated['name']) {
                $validated['slug'] = Company::generateUniqueSlug($validated['name'], $company->id);
            }
            $company->update($validated);
        } else {
            $validated['slug'] = Company::generateUniqueSlug($validated['name']);
            $validated['is_active'] = true;
            $validated['order'] = (Company::max('order') ?? 0) + 1;
            Company::create($validated);
        }

        return redirect()
            ->route('company.profile.edit')
            ->with('success', 'تم حفظ ملف الشركة بنجاح');
    }

    private function linesToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value))));
    }

    private function csvToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
