<?php

use App\Ai\Agents\ContentImproverAgent;
use App\Ai\Agents\ContentSummarizerAgent;
use App\Ai\Agents\GrammarCheckerAgent;
use App\Services\Ai\AIContentImprovementService;
use App\Services\Ai\AIContentSummaryService;
use Tests\TestCase;

uses(TestCase::class);

it('summarizes content via the sdk agent', function () {
    ContentSummarizerAgent::fake(['ملخص قصير للمحتوى.']);

    $summary = app(AIContentSummaryService::class)->summarize('نص طويل للتجربة', 'short');

    expect($summary['summary_text'])->toBe('ملخص قصير للمحتوى.');
});

it('improves content via the sdk agent', function () {
    ContentImproverAgent::fake(['نص محسّن.']);

    $result = app(AIContentImprovementService::class)->improveContent('نص أصلي', ['type' => 'general']);

    expect($result['content'])->toBe('نص محسّن.');
});

it('checks grammar via structured sdk agent', function () {
    GrammarCheckerAgent::fake([[
        'corrected' => 'نص صحيح.',
        'errors' => [],
    ]]);

    $result = app(AIContentImprovementService::class)->checkGrammar('نص خاطئ');

    expect($result['corrected'])->toBe('نص صحيح.')
        ->and($result['errors'])->toBe([]);
});
