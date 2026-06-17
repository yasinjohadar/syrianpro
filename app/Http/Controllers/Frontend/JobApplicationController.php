<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CompanyTalentAction;
use App\Models\Job;
use App\Models\JobApplication;
use App\Notifications\NewJobApplicationNotification;
use App\Services\CompanyTalentActionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function __construct(
        private CompanyTalentActionService $actionService
    ) {}

    public function store(Request $request, Job $job): JsonResponse
    {
        abort_unless($job->is_active, 404);

        $user = Auth::user();

        $existing = JobApplication::query()
            ->where('user_id', $user->id)
            ->where('job_listing_id', $job->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'لقد تقدمت لهذه الوظيفة مسبقاً',
                'status' => $existing->status,
                'status_label' => $existing->statusLabel(),
            ], 409);
        }

        $application = JobApplication::create([
            'user_id' => $user->id,
            'job_listing_id' => $job->id,
            'status' => JobApplication::STATUS_PENDING,
        ]);

        $job->load('company.user');
        if ($job->company?->user) {
            $job->company->user->notify(new NewJobApplicationNotification($application, $job));
        }

        if ($request->filled('invite_id')) {
            $invite = CompanyTalentAction::query()
                ->where('id', $request->integer('invite_id'))
                ->where('talent_id', $user->talent?->id)
                ->where('job_listing_id', $job->id)
                ->where('type', CompanyTalentAction::TYPE_INVITE)
                ->first();

            if ($invite) {
                $this->actionService->markInviteApplied($invite);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلبك بنجاح!',
            'status' => $application->status,
            'status_label' => $application->statusLabel(),
        ]);
    }
}
