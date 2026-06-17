<?php

namespace App\Notifications;

use App\Models\CompanyTalentAction;

class JobInviteNotification extends PanelNotification
{
    public function __construct(
        private CompanyTalentAction $action
    ) {}

    public function toArray(object $notifiable): array
    {
        $this->action->loadMissing(['company', 'job']);

        return $this->payload(
            'job_invite',
            'دعوة للتقديم على وظيفة',
            ($this->action->company?->name ?? 'شركة').' تدعوك للتقديم على «'.($this->action->job?->title ?? 'وظيفة').'»',
            route('jobs.show', $this->action->job).'#invite='.$this->action->id
        );
    }
}
