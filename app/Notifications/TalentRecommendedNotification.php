<?php

namespace App\Notifications;

use App\Models\TalentRecommendation;

class TalentRecommendedNotification extends PanelNotification
{
    public function __construct(
        private TalentRecommendation $recommendation
    ) {}

    public function toArray(object $notifiable): array
    {
        $this->recommendation->loadMissing('talent');

        return $this->payload(
            'recommended',
            'تمت إضافتك لقائمة الموصى بهم',
            $this->recommendation->reason,
            $this->recommendation->talent?->slug
                ? route('talents.show', $this->recommendation->talent)
                : route('talent.dashboard')
        );
    }
}
