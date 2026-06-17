<?php

namespace App\Notifications;

use App\Models\Job;

class JobMatchNotification extends PanelNotification
{
    public function __construct(
        private Job $job,
        private int $score
    ) {}

    public function toArray(object $notifiable): array
    {
        return $this->payload(
            'job_match',
            'وظيفة تناسبك',
            '«'.$this->job->title.'» — تطابق '.$this->score.'%',
            route('jobs.show', $this->job)
        );
    }
}
