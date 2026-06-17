<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Talent;
use App\Models\TalentHiringRequest;
use App\Models\TalentHiringRequestResponse;
use App\Models\User;
use App\Notifications\HiringRequestResponseNotification;
use App\Notifications\TalentHiredNotification;
use App\Services\HireRecordService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TalentHiringRequestService
{
    public function __construct(
        private HireRecordService $hireRecordService
    ) {}
    public function upsertPublicRequest(User $user, Talent $talent, array $data): TalentHiringRequest
    {
        return DB::transaction(function () use ($user, $talent, $data) {
            $existing = TalentHiringRequest::query()
                ->forTalent($talent->id)
                ->public()
                ->whereIn('status', [
                    TalentHiringRequest::STATUS_DRAFT,
                    TalentHiringRequest::STATUS_ACTIVE,
                    TalentHiringRequest::STATUS_PAUSED,
                ])
                ->first();

            $payload = $this->buildPayload($user, $talent, $data, null);
            $publish = (bool) ($data['publish'] ?? false);

            if ($existing) {
                $existing->update($payload);
                $request = $existing->fresh();
            } else {
                $request = TalentHiringRequest::create($payload);
            }

            if ($publish) {
                $this->activateRequest($request, $talent, syncOpenToWork: true);
            }

            return $request->fresh();
        });
    }

    public function createPitch(User $user, Talent $talent, Company $company, array $data): TalentHiringRequest
    {
        return DB::transaction(function () use ($user, $talent, $company, $data) {
            $exists = TalentHiringRequest::query()
                ->forTalent($talent->id)
                ->where('company_id', $company->id)
                ->whereIn('status', [
                    TalentHiringRequest::STATUS_DRAFT,
                    TalentHiringRequest::STATUS_ACTIVE,
                    TalentHiringRequest::STATUS_PAUSED,
                ])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'company_id' => 'لديك بالفعل عرض موجّه لهذه الشركة.',
                ]);
            }

            $payload = $this->buildPayload($user, $talent, $data, $company->id);
            $payload['status'] = TalentHiringRequest::STATUS_ACTIVE;
            $payload['published_at'] = now();

            return TalentHiringRequest::create($payload);
        });
    }

    public function activateRequest(TalentHiringRequest $request, Talent $talent, bool $syncOpenToWork = false): void
    {
        if ($request->isPublic()) {
            TalentHiringRequest::query()
                ->forTalent($talent->id)
                ->public()
                ->where('id', '!=', $request->id)
                ->where('status', TalentHiringRequest::STATUS_ACTIVE)
                ->update(['status' => TalentHiringRequest::STATUS_PAUSED]);
        }

        $request->update([
            'status' => TalentHiringRequest::STATUS_ACTIVE,
            'published_at' => $request->published_at ?? now(),
        ]);

        if ($syncOpenToWork && $request->isPublic()) {
            $talent->update(['is_open_to_work' => true]);
        }
    }

    public function pauseRequest(TalentHiringRequest $request, Talent $talent): void
    {
        $request->update(['status' => TalentHiringRequest::STATUS_PAUSED]);

        if ($request->isPublic()) {
            $this->syncOpenToWorkFlag($talent);
        }
    }

    public function resumeRequest(TalentHiringRequest $request, Talent $talent): void
    {
        $this->activateRequest($request, $talent, syncOpenToWork: $request->isPublic());
    }

    public function closeRequest(TalentHiringRequest $request, Talent $talent): void
    {
        $request->update(['status' => TalentHiringRequest::STATUS_CLOSED]);

        if ($request->isPublic()) {
            $this->syncOpenToWorkFlag($talent);
        }
    }

    public function toggleOpenToWork(Talent $talent, bool $enabled): void
    {
        $talent->update(['is_open_to_work' => $enabled]);

        if (! $enabled) {
            TalentHiringRequest::query()
                ->forTalent($talent->id)
                ->public()
                ->where('status', TalentHiringRequest::STATUS_ACTIVE)
                ->update(['status' => TalentHiringRequest::STATUS_PAUSED]);

            return;
        }

        $publicRequest = TalentHiringRequest::query()
            ->forTalent($talent->id)
            ->public()
            ->whereIn('status', [
                TalentHiringRequest::STATUS_DRAFT,
                TalentHiringRequest::STATUS_PAUSED,
            ])
            ->latest()
            ->first();

        if ($publicRequest) {
            $this->activateRequest($publicRequest, $talent);
        }
    }

    public function respond(
        TalentHiringRequest $request,
        Company $company,
        User $user,
        string $status,
        ?string $message = null
    ): TalentHiringRequestResponse {
        if ($request->isPitch() && $request->company_id !== $company->id) {
            throw ValidationException::withMessages([
                'company' => 'لا يمكنك الرد على هذا الطلب.',
            ]);
        }

        $response = TalentHiringRequestResponse::updateOrCreate(
            [
                'hiring_request_id' => $request->id,
                'company_id' => $company->id,
            ],
            [
                'user_id' => $user->id,
                'status' => $status,
                'message' => $message,
            ]
        );

        $request->load('user');
        if ($request->user) {
            $request->user->notify(new HiringRequestResponseNotification($request, $response, $company));
        }

        return $response;
    }

    public function markAsHiredByCompany(TalentHiringRequest $request, Company $company, User $user): void
    {
        if (! $request->isPitch()) {
            throw ValidationException::withMessages([
                'request' => 'يمكن للشركة تأكيد التوظيف على العروض الموجّهة فقط.',
            ]);
        }

        if ($request->company_id !== $company->id) {
            throw ValidationException::withMessages([
                'company' => 'لا يمكنك تأكيد التوظيف لهذا الطلب.',
            ]);
        }

        if (in_array($request->status, [TalentHiringRequest::STATUS_CLOSED, TalentHiringRequest::STATUS_HIRED], true)) {
            throw ValidationException::withMessages([
                'request' => 'هذا الطلب مغلق بالفعل.',
            ]);
        }

        $response = $request->responses()
            ->where('company_id', $company->id)
            ->first();

        if (! $response || ! in_array($response->status, [
            TalentHiringRequestResponse::STATUS_INTERESTED,
            TalentHiringRequestResponse::STATUS_CONTACTED,
        ], true)) {
            throw ValidationException::withMessages([
                'status' => 'يجب أن تكون حالتك «مهتم» أو «تم التواصل» قبل تأكيد التوظيف.',
            ]);
        }

        DB::transaction(function () use ($request, $company) {
            $talent = $request->talent;
            $request->update(['status' => TalentHiringRequest::STATUS_HIRED]);

            if ($talent) {
                $talent->update(['is_open_to_work' => false]);
                $this->pauseOtherActivePublicRequests($talent, $request->id);
            }

            $request->load('user');
            if ($request->user) {
                $request->user->notify(new TalentHiredNotification($request, $company));
            }

            $this->hireRecordService->recordFromHiringRequest($request->fresh(), $company);
        });
    }

    public function markAsHiredByTalent(TalentHiringRequest $request, Talent $talent): void
    {
        if ($request->talent_id !== $talent->id) {
            throw ValidationException::withMessages([
                'talent' => 'لا يمكنك تأكيد التوظيف لهذا الطلب.',
            ]);
        }

        if (! $request->isPublic()) {
            throw ValidationException::withMessages([
                'request' => 'يمكن للتقني تأكيد التوظيف على الطلب العام فقط.',
            ]);
        }

        if (in_array($request->status, [TalentHiringRequest::STATUS_CLOSED, TalentHiringRequest::STATUS_HIRED], true)) {
            throw ValidationException::withMessages([
                'request' => 'هذا الطلب مغلق بالفعل.',
            ]);
        }

        DB::transaction(function () use ($request, $talent) {
            $request->update(['status' => TalentHiringRequest::STATUS_HIRED]);
            $talent->update(['is_open_to_work' => false]);
            $this->pauseOtherActivePublicRequests($talent, $request->id);
            $this->hireRecordService->recordFromHiringRequest($request->fresh());
        });
    }

    private function pauseOtherActivePublicRequests(Talent $talent, int $exceptId): void
    {
        TalentHiringRequest::query()
            ->forTalent($talent->id)
            ->public()
            ->where('id', '!=', $exceptId)
            ->where('status', TalentHiringRequest::STATUS_ACTIVE)
            ->update(['status' => TalentHiringRequest::STATUS_PAUSED]);
    }

    public function syncOpenToWorkFlag(Talent $talent): void
    {
        $hasActivePublic = TalentHiringRequest::query()
            ->forTalent($talent->id)
            ->publicActive()
            ->exists();

        $talent->update(['is_open_to_work' => $hasActivePublic]);
    }

    private function buildPayload(User $user, Talent $talent, array $data, ?int $companyId): array
    {
        return [
            'user_id' => $user->id,
            'talent_id' => $talent->id,
            'company_id' => $companyId,
            'headline' => $data['headline'],
            'cover_message' => $data['cover_message'] ?? null,
            'employment_type' => $data['employment_type'] ?? TalentHiringRequest::TYPE_FULL_TIME,
            'is_remote' => (bool) ($data['is_remote'] ?? true),
            'rate_min' => $data['rate_min'] ?? null,
            'rate_max' => $data['rate_max'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'status' => TalentHiringRequest::STATUS_DRAFT,
        ];
    }
}
