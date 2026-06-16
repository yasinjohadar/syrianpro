<?php

namespace Database\Seeders;

use App\Models\BlogTag;
use Illuminate\Database\Seeder;

class BlogTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Laravel', 'slug' => 'laravel', 'color' => '#ff2d20'],
            ['name' => 'React', 'slug' => 'react', 'color' => '#61dafb'],
            ['name' => 'Vue.js', 'slug' => 'vuejs', 'color' => '#42b883'],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'color' => '#f7df1e'],
            ['name' => 'TypeScript', 'slug' => 'typescript', 'color' => '#3178c6'],
            ['name' => 'Python', 'slug' => 'python', 'color' => '#3776ab'],
            ['name' => 'Docker', 'slug' => 'docker', 'color' => '#2496ed'],
            ['name' => 'Kubernetes', 'slug' => 'kubernetes', 'color' => '#326ce5'],
            ['name' => 'الذكاء الاصطناعي', 'slug' => 'ai', 'color' => '#6f42c1'],
            ['name' => 'قواعد البيانات', 'slug' => 'databases', 'color' => '#fd7e14'],
            ['name' => 'API', 'slug' => 'api', 'color' => '#0d6efd'],
            ['name' => 'أفضل الممارسات', 'slug' => 'best-practices', 'color' => '#198754'],
            ['name' => 'تعليمي', 'slug' => 'tutorial', 'color' => '#20c997'],
            ['name' => 'نصائح', 'slug' => 'tips', 'color' => '#ffc107'],
        ];

        foreach ($tags as $index => $tag) {
            BlogTag::updateOrCreate(
                ['slug' => $tag['slug']],
                array_merge($tag, [
                    'description' => null,
                    'is_active' => true,
                    'order' => $index + 1,
                    'is_indexable' => true,
                ])
            );
        }
    }
}
