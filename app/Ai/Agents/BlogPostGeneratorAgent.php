<?php

namespace App\Ai\Agents;

use App\Models\BlogCategory;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class BlogPostGeneratorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function __construct(
        public string $topic,
        public string $language = 'ar',
        public string $contentLength = 'medium',
        public string $tone = 'professional',
        public ?BlogCategory $category = null,
        public bool $includeSeo = true,
    ) {}

    public function instructions(): Stringable|string
    {
        $langLabel = $this->language === 'ar' ? 'العربية' : 'English';
        $categoryName = $this->category?->name ?? 'عام';

        return <<<INSTRUCTIONS
أنت كاتب محتوى محترف. اكتب مقال مدونة كاملاً باللغة {$langLabel}.
الموضوع: {$this->topic}
التصنيف: {$categoryName}
الطول: {$this->contentLength}
النبرة: {$this->tone}
المحتوى بصيغة HTML مناسبة للمدونة (h2, h3, p, ul).
INSTRUCTIONS;
    }

    public function schema(JsonSchema $schema): array
    {
        $fields = [
            'title' => $schema->string()->required(),
            'slug' => $schema->string()->required(),
            'excerpt' => $schema->string()->required(),
            'content' => $schema->string()->required(),
        ];

        if ($this->includeSeo) {
            $fields['meta_title'] = $schema->string()->required();
            $fields['meta_description'] = $schema->string()->required();
            $fields['focus_keyword'] = $schema->string()->required();
            $fields['og_title'] = $schema->string()->required();
            $fields['og_description'] = $schema->string()->required();
        }

        return $fields;
    }
}
