<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Concerns\ParsesContactChannels;
use App\Http\Controllers\Controller;
use App\Models\Talent;
use App\Models\TechSpecialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use ParsesContactChannels;

    public function __construct()
    {
        $this->middleware(['auth', 'check.user.active', 'role:talent']);
    }

    public function edit(Request $request): View
    {
        return view('talents.pages.profile.edit', [
            'user' => $request->user(),
            'talent' => $request->user()->talent,
            'specialties' => TechSpecialty::query()->orderBy('order')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $talent = $user->talent;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'avatar' => 'nullable|string|max:10',
            'avatar_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'bio' => 'nullable|string',
            'skills_text' => 'nullable|string',
            'availability' => 'nullable|string|max:255',
            'rate_min' => 'nullable|integer|min:0',
            'rate_max' => 'nullable|integer|min:0',
            'tech_specialty_id' => 'nullable|exists:tech_specialties,id',
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
            'is_remote' => 'nullable|boolean',
            'remove_avatar_image' => 'nullable|boolean',
        ], [
            'name.required' => 'الاسم مطلوب',
            'title.required' => 'المسمى الوظيفي مطلوب',
            'city.required' => 'المدينة مطلوبة',
            'contact_emails.*.email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
        ]);

        if ($request->hasFile('avatar_image')) {
            if ($talent?->avatar_image) {
                Storage::disk('public')->delete($talent->avatar_image);
            }
            $validated['avatar_image'] = $request->file('avatar_image')->store('talents/avatars', 'public');
        }

        if ($request->boolean('remove_avatar_image') && $talent?->avatar_image) {
            Storage::disk('public')->delete($talent->avatar_image);
            $validated['avatar_image'] = null;
        }

        $validated['skills'] = $this->csvToArray($request->input('skills_text'));
        $validated['contact_emails'] = $this->parseLabeledRows($request->input('contact_emails'), 'email');
        $validated['contact_websites'] = $this->parseLabeledRows($request->input('contact_websites'), 'url');
        $validated['social_links'] = $this->parseSocialLinks($request->input('social_links'));
        $validated['links'] = $this->legacyTalentLinks($validated['social_links'], $validated['contact_websites']);
        $validated['is_remote'] = $request->boolean('is_remote', true);
        $validated['user_id'] = $user->id;

        unset(
            $validated['skills_text'],
            $validated['remove_avatar_image']
        );

        if ($talent) {
            if ($talent->name !== $validated['name']) {
                $validated['slug'] = Talent::generateUniqueSlug($validated['name'], $talent->id);
            }
            $talent->update($validated);
        } else {
            $validated['slug'] = Talent::generateUniqueSlug($validated['name']);
            $validated['is_active'] = true;
            $validated['order'] = (Talent::max('order') ?? 0) + 1;
            Talent::create($validated);
        }

        if ($user->name !== $validated['name']) {
            $user->update(['name' => $validated['name']]);
        }

        return redirect()
            ->route('talent.profile.edit')
            ->with('success', 'تم حفظ ملفك بنجاح');
    }

    private function csvToArray(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
