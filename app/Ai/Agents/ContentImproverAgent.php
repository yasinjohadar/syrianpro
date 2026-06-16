<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Promptable;
use Stringable;

class ContentImproverAgent implements Agent
{
    use Promptable;

    public function __construct(
        public string $content,
        public string $type = 'general',
    ) {}

    public function instructions(): Stringable|string
    {
        $typeInstructions = match ($this->type) {
            'grammar' => 'ركز على تصحيح القواعد والإملاء.',
            'clarity' => 'ركز على وضوح الأسلوب وتبسيط الجمل.',
            'all' => 'حسّن القواعد والوضوح والأسلوب معاً.',
            default => 'حسّن المحتوى بشكل عام مع الحفاظ على المعنى.',
        };

        return "أنت محرر محتوى محترف باللغة العربية. {$typeInstructions} أرجِع النص المحسّن فقط دون تعليقات إضافية.";
    }

    public function buildPrompt(): string
    {
        return "حسّن المحتوى التالي:\n\n{$this->content}";
    }
}
