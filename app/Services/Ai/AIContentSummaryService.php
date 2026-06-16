<?php

namespace App\Services\Ai;

use App\Ai\Agents\ContentSummarizerAgent;
use App\Ai\Support\AiConfiguration;
use Illuminate\Support\Facades\Log;

class AIContentSummaryService
{
    /**
     * @return array{summary_text: string, summary_type: string, tokens_used: int, cost: float}
     */
    public function summarize(string $content, string $type = 'short'): array
    {
        set_time_limit(180);

        try {
            $agent = ContentSummarizerAgent::make(content: $content, type: $type);

            $response = $agent->prompt(
                $agent->buildPrompt(),
                provider: AiConfiguration::textProvider(),
                model: AiConfiguration::textModel(),
                timeout: AiConfiguration::timeout(),
            );

            $summaryText = (string) $response;
            $usage = $response->usage;
            $tokensUsed = ($usage->promptTokens ?? 0) + ($usage->completionTokens ?? 0);

            return [
                'summary_text' => $summaryText,
                'summary_type' => $type,
                'tokens_used' => $tokensUsed,
                'cost' => 0,
            ];
        } catch (\Exception $e) {
            Log::error('Error summarizing content: '.$e->getMessage());
            throw $e;
        }
    }
}
