<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class GrammarCheckerAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $text,
    ) {}

    public function instructions(): Stringable|string
    {
        return 'أنت مدقق لغوي عربي. صحّح النص وأرجِع النص المصحح مع قائمة الأخطاء التي وجدتها.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'corrected' => $schema->string()->required(),
            'errors' => $schema->array()->items(
                $schema->object(fn (JsonSchema $schema) => [
                    'original' => $schema->string()->required(),
                    'correction' => $schema->string()->required(),
                    'explanation' => $schema->string()->required(),
                ])
            )->required(),
        ];
    }

    public function buildPrompt(): string
    {
        return "افحص النص التالي نحوياً وإملائياً:\n\n{$this->text}";
    }
}
