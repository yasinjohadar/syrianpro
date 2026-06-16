<?php

namespace App\Services\Ai;

use App\Ai\Agents\ContentImproverAgent;
use App\Ai\Agents\GrammarCheckerAgent;
use App\Ai\Support\AiConfiguration;
use Illuminate\Support\Facades\Log;

class AIContentImprovementService
{
    public function improveContent(string $content, array $options = []): array
    {
        set_time_limit(180);

        $type = $options['type'] ?? 'general';

        try {
            $agent = ContentImproverAgent::make(content: $content, type: $type);

            $response = $agent->prompt(
                $agent->buildPrompt(),
                provider: AiConfiguration::textProvider(),
                model: AiConfiguration::textModel(),
                timeout: AiConfiguration::timeout(),
            );

            $text = (string) $response;

            return [
                'content' => $text,
                'suggestions' => $this->extractSuggestions($text),
            ];
        } catch (\Exception $e) {
            Log::error('Error improving content: '.$e->getMessage());
            throw $e;
        }
    }

    public function checkGrammar(string $text): array
    {
        set_time_limit(180);

        try {
            $agent = GrammarCheckerAgent::make(text: $text);

            $response = $agent->prompt(
                $agent->buildPrompt(),
                provider: AiConfiguration::textProvider(),
                model: AiConfiguration::textModel(),
                timeout: AiConfiguration::timeout(),
            );

            $data = $response->toArray();

            return [
                'corrected' => $data['corrected'] ?? (string) $response,
                'errors' => $data['errors'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('Error checking grammar: '.$e->getMessage());
            throw $e;
        }
    }

    protected function extractSuggestions(string $response): array
    {
        return [];
    }
}
