<?php

namespace App\Services\Ai;

use App\Ai\Agents\BlogPostGeneratorAgent;
use App\Ai\Support\AiConfiguration;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIBlogPostService
{
    public function generateBlogPost(string $topic, array $options = []): array
    {
        $contentLength = $options['content_length'] ?? 'medium';
        $tone = $options['tone'] ?? 'professional';
        $language = $options['language'] ?? 'ar';
        $category = $options['category'] ?? null;
        $generateSeo = $options['generate_seo'] ?? true;

        set_time_limit(500);

        try {
            $agent = BlogPostGeneratorAgent::make(
                topic: $topic,
                language: $language,
                contentLength: $contentLength,
                tone: $tone,
                category: $category instanceof BlogCategory ? $category : null,
                includeSeo: $generateSeo,
            );

            $response = $agent->prompt(
                "اكتب مقال مدونة كاملاً حول: {$topic}",
                provider: AiConfiguration::textProvider(),
                model: AiConfiguration::textModel('structured'),
                timeout: AiConfiguration::timeout(),
            );

            $data = $response->toArray();

            $title = $data['title'] ?? $topic;
            $content = $data['content'] ?? '';
            $excerpt = $data['excerpt'] ?? $this->generateExcerpt($content, $language);
            $slug = $data['slug'] ?? $this->generateSlug($title);

            $result = [
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $excerpt,
                'content' => $content,
            ];

            if ($generateSeo) {
                $result['meta_title'] = $data['meta_title'] ?? Str::limit($title, 60);
                $result['meta_description'] = $data['meta_description'] ?? Str::limit(strip_tags($content), 160);
                $result['focus_keyword'] = $data['focus_keyword'] ?? $topic;
                $result['og_title'] = $data['og_title'] ?? $title;
                $result['og_description'] = $data['og_description'] ?? $excerpt;
                $result['twitter_title'] = $data['og_title'] ?? $title;
                $result['twitter_description'] = $data['og_description'] ?? $excerpt;
            }

            $result['canonical_url'] = url('/blog/'.$slug);
            $wordCount = str_word_count(strip_tags($content));
            $result['reading_time'] = max(1, ceil($wordCount / 200));

            return $result;
        } catch (\Exception $e) {
            Log::error('Error generating blog post: '.$e->getMessage(), [
                'topic' => $topic,
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    private function generateExcerpt(string $content, string $language): string
    {
        $plain = strip_tags($content);

        return Str::limit($plain, 150);
    }

    private function generateSlug(string $title): string
    {
        $slug = Str::slug($title);

        return $slug ?: 'post-'.substr(uniqid(), -6);
    }
}
