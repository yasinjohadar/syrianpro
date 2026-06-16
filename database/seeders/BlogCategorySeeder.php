<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'تطوير الويب',
                'slug' => 'web-development',
                'description' => 'مقالات ودروس حول تطوير الويب، Frontend و Backend، وأطر العمل الحديثة.',
                'icon' => 'fas fa-code',
                'color' => '#0d6efd',
                'order' => 1,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'تطوير الويب | مدونة ياسين جوخدار',
                'meta_description' => 'تعلم تطوير الويب من الصفر، Laravel، React، Vue وأفضل الممارسات.',
            ],
            [
                'name' => 'بايثون والبرمجة',
                'slug' => 'python-programming',
                'description' => 'كل ما يخص لغة بايثون، البرمجة الكائنية، وأتمتة المهام.',
                'icon' => 'fab fa-python',
                'color' => '#3776ab',
                'order' => 2,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'بايثون والبرمجة | مدونة ياسين جوخدار',
                'meta_description' => 'دروس بايثون، علوم البيانات، السكربتات والأتمتة.',
            ],
            [
                'name' => 'DevOps والسحابة',
                'slug' => 'devops-cloud',
                'description' => 'Docker، Kubernetes، CI/CD، ونشر التطبيقات على السحابة.',
                'icon' => 'fas fa-cloud',
                'color' => '#2496ed',
                'order' => 3,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'DevOps والسحابة | مدونة ياسين جوخدار',
                'meta_description' => 'أفضل ممارسات DevOps، الحاويات، والإنتاجية.',
            ],
            [
                'name' => 'الذكاء الاصطناعي',
                'slug' => 'artificial-intelligence',
                'description' => 'تعلم الآلة، معالجة اللغة الطبيعية، والنماذج الحديثة.',
                'icon' => 'fas fa-robot',
                'color' => '#6f42c1',
                'order' => 4,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'الذكاء الاصطناعي | مدونة ياسين جوخدار',
                'meta_description' => 'AI، Machine Learning، وتطبيقات عملية.',
            ],
            [
                'name' => 'تطوير الموبايل',
                'slug' => 'mobile-development',
                'description' => 'Flutter، React Native، وتطوير تطبيقات الهواتف.',
                'icon' => 'fas fa-mobile-alt',
                'color' => '#20c997',
                'order' => 5,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'تطوير الموبايل | مدونة ياسين جوخدار',
                'meta_description' => 'بناء تطبيقات Android و iOS بتقنيات حديثة.',
            ],
            [
                'name' => 'قواعد البيانات',
                'slug' => 'databases',
                'description' => 'SQL، NoSQL، تحسين الاستعلامات، وتصميم قواعد البيانات.',
                'icon' => 'fas fa-database',
                'color' => '#fd7e14',
                'order' => 6,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'قواعد البيانات | مدونة ياسين جوخدار',
                'meta_description' => 'MySQL، PostgreSQL، MongoDB وأفضل الممارسات.',
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
