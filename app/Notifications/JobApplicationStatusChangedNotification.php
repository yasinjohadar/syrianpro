<?php

namespace App\Notifications;

use App\Models\JobApplication;

class JobApplicationStatusChangedNotification extends PanelNotification
{
    public function __construct(
        private JobApplication $application
    ) {}

    public function toArray(object $notifiable): array
    {
        $jobTitle = $this->application->job?->title ?? 'وظيفة';

        return $this->payload(
            'application_status',
            'تحديث حالة طلبك',
            'حالة طلبك على «'.$jobTitle.'»: '.$this->application->statusLabel(),
            route('talent.applications.index')
        );
    }
}
