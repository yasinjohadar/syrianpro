<?php

namespace App\Services;

use App\Models\Hire;
use App\Models\JobApplication;
use App\Models\TalentHiringRequest;
use App\Models\Company;

class HireRecordService
{
    public function recordFromApplication(JobApplication $application): ?Hire
    {
        if ($application->status !== JobApplication::STATUS_ACCEPTED) {
            return null;
        }

        $application->loadMissing(['user.talent', 'job']);

        $talent = $application->user?->talent;
        if (! $talent) {
            return null;
        }

        $existing = Hire::query()
            ->where('source', Hire::SOURCE_APPLICATION)
            ->where('source_id', $application->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        return Hire::create([
            'talent_id' => $talent->id,
            'company_id' => $application->job?->company_id,
            'job_listing_id' => $application->job_listing_id,
            'source' => Hire::SOURCE_APPLICATION,
            'source_id' => $application->id,
            'hired_at' => $application->updated_at ?? now(),
        ]);
    }

    public function recordFromHiringRequest(TalentHiringRequest $request, ?Company $company = null): ?Hire
    {
        if ($request->status !== TalentHiringRequest::STATUS_HIRED) {
            return null;
        }

        $source = $request->isPitch()
            ? Hire::SOURCE_PITCH
            : Hire::SOURCE_PUBLIC_REQUEST;

        $existing = Hire::query()
            ->where('source', $source)
            ->where('source_id', $request->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        return Hire::create([
            'talent_id' => $request->talent_id,
            'company_id' => $company?->id ?? $request->company_id,
            'job_listing_id' => null,
            'source' => $source,
            'source_id' => $request->id,
            'hired_at' => $request->updated_at ?? now(),
        ]);
    }
}
