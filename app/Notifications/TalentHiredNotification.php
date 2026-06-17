<?php

namespace App\Notifications;

use App\Models\Company;
use App\Models\TalentHiringRequest;

class TalentHiredNotification extends PanelNotification
{
    public function __construct(
        private TalentHiringRequest $hiringRequest,
        private ?Company $company = null
    ) {}

    public function toArray(object $notifiable): array
    {
        $by = $this->company?->name ?? 'شركة';

        return $this->payload(
            'hired',
            'تم تأكيد التوظيف',
            'تم تأكيد توظيفك'.($this->company ? ' لدى '.$by : '').' — «'.$this->hiringRequest->headline.'»',
            route('talent.hiring-request.index')
        );
    }
}
