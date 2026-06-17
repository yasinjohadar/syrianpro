<?php

use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Talent;
use App\Models\TalentHiringRequest;
use App\Models\TalentHiringRequestResponse;
use App\Models\User;
use App\Notifications\HiringRequestResponseNotification;
use App\Notifications\JobApplicationStatusChangedNotification;
use App\Notifications\NewJobApplicationNotification;
use App\Notifications\TalentHiredNotification;
use App\Services\TalentHiringRequestService;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

function createCompanyUser(): array
{
    $role = Role::firstOrCreate(['name' => 'company']);
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole($role);

    $company = Company::create([
        'user_id' => $user->id,
        'name' => 'Test Company '.$user->id,
        'slug' => 'test-company-'.$user->id,
        'sector' => 'Tech',
        'location' => 'Remote',
        'is_active' => true,
    ]);

    return compact('user', 'company');
}

function createTalentUser(): array
{
    $role = Role::firstOrCreate(['name' => 'talent']);
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole($role);

    $talent = Talent::create([
        'user_id' => $user->id,
        'name' => 'Test Talent '.$user->id,
        'slug' => 'test-talent-'.$user->id,
        'title' => 'Developer',
        'city' => 'Damascus',
        'is_open_to_work' => true,
        'is_active' => true,
    ]);

    return compact('user', 'talent');
}

function createJobForCompany(Company $company): Job
{
    return Job::create([
        'title' => 'Backend Developer',
        'slug' => 'backend-dev-'.$company->id.'-'.uniqid(),
        'company_id' => $company->id,
        'company_name' => $company->name,
        'location' => 'Remote',
        'employment_type' => 'دوام كامل',
        'remote_type' => 'full-remote',
        'is_active' => true,
        'published_at' => now(),
    ]);
}

test('job is linked to company via company_id', function () {
    ['company' => $company] = createCompanyUser();
    $job = createJobForCompany($company);

    expect($job->company_id)->toBe($company->id);
    expect($company->jobs()->pluck('id'))->toContain($job->id);
});

test('company can mark pitch as hired and talent receives notification', function () {
    Notification::fake();

    ['user' => $companyUser, 'company' => $company] = createCompanyUser();
    ['user' => $talentUser, 'talent' => $talent] = createTalentUser();

    $pitch = TalentHiringRequest::create([
        'user_id' => $talentUser->id,
        'talent_id' => $talent->id,
        'company_id' => $company->id,
        'headline' => 'Senior Dev',
        'employment_type' => TalentHiringRequest::TYPE_FULL_TIME,
        'is_remote' => true,
        'status' => TalentHiringRequest::STATUS_ACTIVE,
        'published_at' => now(),
    ]);

    TalentHiringRequestResponse::create([
        'hiring_request_id' => $pitch->id,
        'company_id' => $company->id,
        'user_id' => $companyUser->id,
        'status' => TalentHiringRequestResponse::STATUS_INTERESTED,
    ]);

    app(TalentHiringRequestService::class)->markAsHiredByCompany($pitch, $company, $companyUser);

    $pitch->refresh();
    $talent->refresh();

    expect($pitch->status)->toBe(TalentHiringRequest::STATUS_HIRED);
    expect($talent->is_open_to_work)->toBeFalse();

    Notification::assertSentTo($talentUser, TalentHiredNotification::class);
});

test('talent can mark public request as hired', function () {
    ['talent' => $talent] = createTalentUser();

    $publicRequest = TalentHiringRequest::create([
        'user_id' => $talent->user_id,
        'talent_id' => $talent->id,
        'company_id' => null,
        'headline' => 'Looking for work',
        'employment_type' => TalentHiringRequest::TYPE_FULL_TIME,
        'is_remote' => true,
        'status' => TalentHiringRequest::STATUS_ACTIVE,
        'published_at' => now(),
    ]);

    app(TalentHiringRequestService::class)->markAsHiredByTalent($publicRequest, $talent);

    $publicRequest->refresh();
    $talent->refresh();

    expect($publicRequest->status)->toBe(TalentHiringRequest::STATUS_HIRED);
    expect($talent->is_open_to_work)->toBeFalse();
});

test('job application status change notifies talent', function () {
    Notification::fake();

    ['user' => $companyUser, 'company' => $company] = createCompanyUser();
    ['user' => $talentUser] = createTalentUser();
    $job = createJobForCompany($company);

    $application = JobApplication::create([
        'user_id' => $talentUser->id,
        'job_listing_id' => $job->id,
        'status' => JobApplication::STATUS_PENDING,
    ]);

    $this->actingAs($companyUser)
        ->put(route('company.applications.update', $application), [
            'status' => JobApplication::STATUS_ACCEPTED,
        ])
        ->assertRedirect();

    Notification::assertSentTo($talentUser, JobApplicationStatusChangedNotification::class);
});

test('new job application notifies company user', function () {
    Notification::fake();

    ['user' => $companyUser, 'company' => $company] = createCompanyUser();
    ['user' => $talentUser] = createTalentUser();
    $job = createJobForCompany($company);

    $this->actingAs($talentUser)
        ->postJson(route('jobs.apply', $job))
        ->assertOk();

    Notification::assertSentTo($companyUser, NewJobApplicationNotification::class);
});

test('hiring request response notifies talent', function () {
    Notification::fake();

    ['user' => $companyUser, 'company' => $company] = createCompanyUser();
    ['user' => $talentUser, 'talent' => $talent] = createTalentUser();

    $request = TalentHiringRequest::create([
        'user_id' => $talentUser->id,
        'talent_id' => $talent->id,
        'company_id' => null,
        'headline' => 'Open to work',
        'employment_type' => TalentHiringRequest::TYPE_FULL_TIME,
        'is_remote' => true,
        'status' => TalentHiringRequest::STATUS_ACTIVE,
        'published_at' => now(),
    ]);

    app(TalentHiringRequestService::class)->respond(
        $request,
        $company,
        $companyUser,
        TalentHiringRequestResponse::STATUS_INTERESTED,
        'نود التواصل معك'
    );

    Notification::assertSentTo($talentUser, HiringRequestResponseNotification::class);
});

test('authenticated user can fetch notifications', function () {
    ['user' => $companyUser, 'company' => $company] = createCompanyUser();
    ['user' => $talentUser] = createTalentUser();
    $job = createJobForCompany($company);

    $application = JobApplication::create([
        'user_id' => $talentUser->id,
        'job_listing_id' => $job->id,
        'status' => JobApplication::STATUS_PENDING,
    ]);

    $companyUser->notify(new NewJobApplicationNotification($application, $job));

    $this->actingAs($companyUser)
        ->getJson(route('company.notifications.index'))
        ->assertOk()
        ->assertJsonStructure(['unread_count', 'notifications'])
        ->assertJsonPath('unread_count', 1);
});
