<?php

use App\Models\Hire;
use App\Models\JobApplication;
use App\Models\TalentHiringRequest;
use App\Models\TalentRecommendation;
use App\Services\HireRecordService;
use App\Services\TalentJobMatchingService;
use App\Services\TalentRecommendationService;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

test('accepted application creates hire record', function () {
    ['user' => $companyUser, 'company' => $company] = createCompanyUser();
    ['user' => $talentUser, 'talent' => $talent] = createTalentUser();
    $job = createJobForCompany($company);

    $application = JobApplication::create([
        'user_id' => $talentUser->id,
        'job_listing_id' => $job->id,
        'status' => JobApplication::STATUS_ACCEPTED,
    ]);

    $hire = app(HireRecordService::class)->recordFromApplication($application);

    expect($hire)->not->toBeNull();
    expect($hire->source)->toBe(Hire::SOURCE_APPLICATION);
    expect($hire->company_id)->toBe($company->id);
});

test('admin can create talent recommendation', function () {
    Notification::fake();

    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $admin = \App\Models\User::factory()->create(['is_active' => true]);
    $admin->assignRole($adminRole);

    ['talent' => $talent] = createTalentUser();

    $rec = app(TalentRecommendationService::class)->create($admin, $talent, [
        'reason' => 'خبير Laravel',
        'scope' => TalentRecommendation::SCOPE_HOMEPAGE,
        'priority' => 10,
    ]);

    expect($rec->is_active)->toBeTrue();
    expect(TalentRecommendation::active()->forScope(TalentRecommendation::SCOPE_HOMEPAGE)->count())->toBe(1);
});

test('matching service scores talent and job', function () {
    ['company' => $company] = createCompanyUser();
    ['talent' => $talent] = createTalentUser();

    $talent->update(['skills' => ['Laravel', 'PHP'], 'is_open_to_work' => true, 'is_remote' => true]);

    $job = createJobForCompany($company);
    $job->update(['skills' => ['Laravel', 'Vue'], 'remote_type' => 'full-remote', 'is_syria_friendly' => true]);

    $score = app(TalentJobMatchingService::class)->score($talent->fresh(), $job->fresh());

    expect($score)->toBeGreaterThanOrEqual(40);
});
