<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

class ContentSummarizerAgent implements Agent
{
    use Promptable;

    public function __construct(
        public string $content,
        public string $type = 'short',
    ) {}

    public function instructions(): Stringable|string
    {
        $format = match ($this->type) {
            'long' => 'قدّم ملخصاً مفصلاً في عدة فقرات.',
            'bullet_points' => 'قدّم الملخص على شكل نقاط مرقمة.',
            default => 'قدّم ملخصاً قصيراً في فقرة واحدة أو اثنتين.',
        };

        return "أنت خبير تلخيص محتوى. {$format} أرجِع الملخص فقط باللغة العربية.";
    }

    public function buildPrompt(): string
    {
        return "لخّص المحتوى التالي:\n\n{$this->content}";
    }
}
