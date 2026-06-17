<?php

namespace App\Notifications;

use App\Models\Company;
use App\Models\TalentHiringRequest;
use App\Models\TalentHiringRequestResponse;

class HiringRequestResponseNotification extends PanelNotification
{
    public function __construct(
        private TalentHiringRequest $hiringRequest,
        private TalentHiringRequestResponse $response,
        private Company $company
    ) {}

    public function toArray(object $notifiable): array
    {
        return $this->payload(
            'hiring_response',
            'رد شركة على طلبك',
            $this->company->name.': '.$this->response->statusLabel(),
            route('talent.hiring-request.index')
        );
    }
}
