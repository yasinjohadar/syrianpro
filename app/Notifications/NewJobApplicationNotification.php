<?php

namespace App\Notifications;

use App\Models\Job;
use App\Models\JobApplication;

class NewJobApplicationNotification extends PanelNotification
{
    public function __construct(
        private JobApplication $application,
        private Job $job
    ) {}

    public function toArray(object $notifiable): array
    {
        $applicant = $this->application->user;

        return $this->payload(
            'application_new',
            'تقديم جديد على وظيفة',
            ($applicant?->name ?? 'متقدم').' تقدّم على «'.$this->job->title.'»',
            route('company.applications.show', $this->application)
        );
    }
}
