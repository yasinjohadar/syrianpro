<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyTalentAction;
use App\Models\Job;
use App\Models\Talent;
use App\Models\User;
use App\Notifications\JobInviteNotification;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CompanyTalentActionService
{
    public function invite(Company $company, User $user, Talent $talent, Job $job, ?string $message = null): CompanyTalentAction
    {
        if ($job->company_id !== $company->id) {
            throw ValidationException::withMessages(['job' => 'لا يمكنك دعوة تقنيين لوظيفة لا تخص شركتك.']);
        }

        $weekStart = Carbon::now()->subDays(7);
        $recentInvites = CompanyTalentAction::query()
            ->where('company_id', $company->id)
            ->where('type', CompanyTalentAction::TYPE_INVITE)
            ->where('created_at', '>=', $weekStart)
            ->count();

        if ($recentInvites >= config('marketplace.invite_weekly_limit', 10)) {
            throw ValidationException::withMessages([
                'invite' => 'تجاوزت الحد الأسبوعي للدعوات.',
            ]);
        }

        $pending = CompanyTalentAction::query()
            ->where('company_id', $company->id)
            ->where('talent_id', $talent->id)
            ->where('job_listing_id', $job->id)
            ->where('type', CompanyTalentAction::TYPE_INVITE)
            ->whereIn('status', [CompanyTalentAction::STATUS_PENDING, CompanyTalentAction::STATUS_VIEWED])
            ->exists();

        if ($pending) {
            throw ValidationException::withMessages([
                'invite' => 'لديك دعوة معلقة لهذا التقني على نفس الوظيفة.',
            ]);
        }

        $action = CompanyTalentAction::create([
            'company_id' => $company->id,
            'talent_id' => $talent->id,
            'job_listing_id' => $job->id,
            'user_id' => $user->id,
            'type' => CompanyTalentAction::TYPE_INVITE,
            'message' => $message,
            'status' => CompanyTalentAction::STATUS_PENDING,
        ]);

        if ($talent->user) {
            $talent->user->notify(new JobInviteNotification($action));
        }

        return $action;
    }

    public function toggleShortlist(Company $company, User $user, Talent $talent, ?int $fitRating = null): CompanyTalentAction
    {
        $existing = CompanyTalentAction::query()
            ->where('company_id', $company->id)
            ->where('talent_id', $talent->id)
            ->where('type', CompanyTalentAction::TYPE_SHORTLIST)
            ->where('status', CompanyTalentAction::STATUS_ACTIVE)
            ->first();

        if ($existing) {
            $existing->update(['status' => CompanyTalentAction::STATUS_REMOVED]);

            return $existing;
        }

        return CompanyTalentAction::create([
            'company_id' => $company->id,
            'talent_id' => $talent->id,
            'user_id' => $user->id,
            'type' => CompanyTalentAction::TYPE_SHORTLIST,
            'fit_rating' => $fitRating,
            'status' => CompanyTalentAction::STATUS_ACTIVE,
        ]);
    }

    public function addNote(Company $company, User $user, Talent $talent, string $message, ?int $fitRating = null): CompanyTalentAction
    {
        return CompanyTalentAction::create([
            'company_id' => $company->id,
            'talent_id' => $talent->id,
            'user_id' => $user->id,
            'type' => CompanyTalentAction::TYPE_NOTE,
            'message' => $message,
            'fit_rating' => $fitRating,
            'status' => CompanyTalentAction::STATUS_ACTIVE,
        ]);
    }

    public function markInviteApplied(CompanyTalentAction $action): void
    {
        if ($action->type !== CompanyTalentAction::TYPE_INVITE) {
            return;
        }

        $action->update([
            'status' => CompanyTalentAction::STATUS_APPLIED,
            'responded_at' => now(),
        ]);
    }

    public function declineInvite(CompanyTalentAction $action, Talent $talent): void
    {
        if ($action->talent_id !== $talent->id || $action->type !== CompanyTalentAction::TYPE_INVITE) {
            throw ValidationException::withMessages(['invite' => 'لا يمكن رفض هذه الدعوة.']);
        }

        $action->update([
            'status' => CompanyTalentAction::STATUS_DECLINED,
            'responded_at' => now(),
        ]);
    }
}
