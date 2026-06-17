<?php

namespace App\Console\Commands;

use App\Models\JobApplication;
use App\Models\TalentHiringRequest;
use App\Services\HireRecordService;
use Illuminate\Console\Command;

class BackfillHiresCommand extends Command
{
    protected $signature = 'hires:backfill';

    protected $description = 'Backfill hire records from accepted applications and hired requests';

    public function handle(HireRecordService $service): int
    {
        $count = 0;

        JobApplication::query()
            ->where('status', JobApplication::STATUS_ACCEPTED)
            ->with(['user.talent', 'job'])
            ->chunkById(100, function ($applications) use ($service, &$count) {
                foreach ($applications as $application) {
                    if ($service->recordFromApplication($application)) {
                        $count++;
                    }
                }
            });

        TalentHiringRequest::query()
            ->where('status', TalentHiringRequest::STATUS_HIRED)
            ->with('company')
            ->chunkById(100, function ($requests) use ($service, &$count) {
                foreach ($requests as $request) {
                    if ($service->recordFromHiringRequest($request, $request->company)) {
                        $count++;
                    }
                }
            });

        $this->info("Processed hire records (idempotent). Total rows touched: {$count}");

        return self::SUCCESS;
    }
}
