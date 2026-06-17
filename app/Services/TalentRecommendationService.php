<?php

namespace App\Services;

use App\Models\Talent;
use App\Models\TalentRecommendation;
use App\Models\User;
use App\Notifications\TalentRecommendedNotification;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class TalentRecommendationService
{
    public function create(User $admin, Talent $talent, array $data): TalentRecommendation
    {
        if (! $talent->is_active) {
            throw ValidationException::withMessages(['talent' => 'لا يمكن التوصية بتقني غير نشط.']);
        }

        if ($data['scope'] === TalentRecommendation::SCOPE_HOMEPAGE) {
            $activeCount = TalentRecommendation::query()
                ->active()
                ->forScope(TalentRecommendation::SCOPE_HOMEPAGE)
                ->count();

            if ($activeCount >= config('marketplace.max_homepage_recommendations', 20)) {
                throw ValidationException::withMessages([
                    'scope' => 'تم الوصول للحد الأقصى من التوصيات على الصفحة الرئيسية.',
                ]);
            }
        }

        $recommendation = TalentRecommendation::create([
            'talent_id' => $talent->id,
            'recommended_by' => $admin->id,
            'reason' => $data['reason'],
            'scope' => $data['scope'],
            'scope_id' => $data['scope_id'] ?? null,
            'priority' => $data['priority'] ?? 0,
            'starts_at' => $data['starts_at'] ?? now(),
            'expires_at' => $data['expires_at'] ?? null,
            'is_active' => true,
        ]);

        if ($talent->user) {
            $talent->user->notify(new TalentRecommendedNotification($recommendation));
        }

        return $recommendation;
    }

    public function deactivate(TalentRecommendation $recommendation): void
    {
        $recommendation->update(['is_active' => false]);
    }

    public function activeForHomepage(int $limit = 6): Collection
    {
        return TalentRecommendation::query()
            ->active()
            ->forScope(TalentRecommendation::SCOPE_HOMEPAGE)
            ->with(['talent.techSpecialty', 'talent.activePublicHiringRequest'])
            ->ordered()
            ->limit($limit)
            ->get();
    }
}
