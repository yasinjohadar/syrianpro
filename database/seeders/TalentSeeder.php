<?php

namespace Database\Seeders;

use App\Models\Talent;
use App\Models\TechSpecialty;
use Illuminate\Database\Seeder;

class TalentSeeder extends Seeder
{
    public function run(): void
    {
        $specialtyIds = TechSpecialty::query()->pluck('id', 'name');

        $talents = [
            [
                'name' => 'أحمد الخطيب',
                'title' => 'مطور Full Stack',
                'city' => 'دمشق',
                'avatar' => 'أ',
                'bio' => 'مطور full stack بخبرة 5 سنوات في React و Node.js. أعمل عن بُعد مع شركات أوروبية وأبني منتجات SaaS.',
                'skills' => ['React', 'TypeScript', 'Node.js', 'PostgreSQL', 'Docker'],
                'is_remote' => true,
                'availability' => 'متاح فوراً',
                'rate_min' => 20,
                'rate_max' => 30,
                'tech_specialty' => 'Frontend',
                'experience' => [
                    ['role' => 'Senior Developer', 'company' => 'Remote EU', 'years' => '2021–الآن'],
                    ['role' => 'Full Stack Dev', 'company' => 'SyriaDev', 'years' => '2019–2021'],
                ],
                'projects' => [
                    ['id' => 1, 'title' => 'منصة إدارة المشاريع', 'desc' => 'SaaS لإدارة فرق العمل عن بُعد', 'image' => '📊', 'demoUrl' => '#', 'githubUrl' => '#', 'tags' => ['React', 'Node.js']],
                    ['id' => 2, 'title' => 'متجر إلكتروني', 'desc' => 'E-commerce كامل مع لوحة تحكم', 'image' => '🛒', 'demoUrl' => '#', 'githubUrl' => '#', 'tags' => ['Next.js', 'Stripe']],
                ],
                'links' => ['github' => '#', 'linkedin' => '#', 'portfolio' => '#'],
                'is_verified' => true,
                'is_featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'سارة النجار',
                'title' => 'مصممة UI/UX',
                'city' => 'حلب',
                'avatar' => 'س',
                'bio' => 'مصممة واجهات بخبرة 4 سنوات. متخصصة في Figma وتجربة المستخدم للمنتجات العربية.',
                'skills' => ['Figma', 'UI Design', 'UX Research', 'Prototyping', 'Design Systems'],
                'is_remote' => true,
                'availability' => 'متاح خلال أسبوع',
                'rate_min' => 15,
                'rate_max' => 25,
                'tech_specialty' => 'UI/UX',
                'experience' => [
                    ['role' => 'Lead Designer', 'company' => 'Pixel Damascus', 'years' => '2022–الآن'],
                ],
                'projects' => [
                    ['id' => 1, 'title' => 'تطبيق توصيل', 'desc' => 'UI/UX لتطبيق توصيل محلي', 'image' => '🚗', 'demoUrl' => '#', 'githubUrl' => '', 'tags' => ['Figma', 'Mobile']],
                    ['id' => 2, 'title' => 'Dashboard تحليلات', 'desc' => 'لوحة تحكم SaaS', 'image' => '📈', 'demoUrl' => '#', 'githubUrl' => '', 'tags' => ['Dashboard', 'B2B']],
                ],
                'links' => ['github' => '', 'linkedin' => '#', 'portfolio' => '#'],
                'is_verified' => true,
                'is_featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'محمد العيسى',
                'title' => 'مهندس DevOps',
                'city' => 'اللاذقية',
                'avatar' => 'م',
                'bio' => 'DevOps engineer — AWS, Kubernetes, CI/CD. أؤتمت البنية لفرق موزعة.',
                'skills' => ['AWS', 'Docker', 'Kubernetes', 'Terraform', 'GitHub Actions'],
                'is_remote' => true,
                'availability' => 'متاح فوراً',
                'rate_min' => 25,
                'rate_max' => 40,
                'tech_specialty' => 'DevOps',
                'experience' => [
                    ['role' => 'DevOps Engineer', 'company' => 'CloudBridge EU', 'years' => '2020–الآن'],
                ],
                'projects' => [
                    ['id' => 1, 'title' => 'CI/CD Pipeline', 'desc' => 'أتمتة نشر microservices', 'image' => '⚙️', 'demoUrl' => '#', 'githubUrl' => '#', 'tags' => ['K8s', 'AWS']],
                ],
                'links' => ['github' => '#', 'linkedin' => '#', 'portfolio' => ''],
                'is_verified' => true,
                'is_featured' => false,
                'order' => 3,
            ],
            [
                'name' => 'ليلى حمود',
                'title' => 'مطورة Flutter',
                'city' => 'دمشق',
                'avatar' => 'ل',
                'bio' => 'مطورة تطبيقات جوال Flutter/Dart. نشرت 8 تطبيقات على المتاجر.',
                'skills' => ['Flutter', 'Dart', 'Firebase', 'Bloc', 'REST APIs'],
                'is_remote' => true,
                'availability' => 'مشغولة — متاحة بعد شهر',
                'rate_min' => 18,
                'rate_max' => 28,
                'tech_specialty' => 'Mobile',
                'experience' => [
                    ['role' => 'Mobile Developer', 'company' => 'Mobile Aleppo', 'years' => '2021–الآن'],
                ],
                'projects' => [
                    ['id' => 1, 'title' => 'تطبيق تعليمي', 'desc' => 'تطبيق تعليم أطفال بالعربية', 'image' => '📱', 'demoUrl' => '#', 'githubUrl' => '#', 'tags' => ['Flutter']],
                    ['id' => 2, 'title' => 'تطبيق صحة', 'desc' => 'حجز مواعيد طبية', 'image' => '🏥', 'demoUrl' => '#', 'githubUrl' => '#', 'tags' => ['Flutter', 'Firebase']],
                ],
                'links' => ['github' => '#', 'linkedin' => '#', 'portfolio' => '#'],
                'is_verified' => false,
                'is_featured' => true,
                'order' => 4,
            ],
            [
                'name' => 'كريم الشامي',
                'title' => 'Backend Developer',
                'city' => 'حلب',
                'avatar' => 'ك',
                'bio' => 'Node.js و PostgreSQL — APIs و microservices للمنتجات الرقمية.',
                'skills' => ['Node.js', 'PostgreSQL', 'Redis', 'GraphQL', 'Microservices'],
                'is_remote' => true,
                'availability' => 'متاح فوراً',
                'rate_min' => 22,
                'rate_max' => 32,
                'tech_specialty' => 'Backend',
                'experience' => [
                    ['role' => 'Backend Dev', 'company' => 'TechLatakia', 'years' => '2019–الآن'],
                ],
                'projects' => [
                    ['id' => 1, 'title' => 'API Gateway', 'desc' => 'Gateway لـ 12 microservice', 'image' => '🔗', 'demoUrl' => '#', 'githubUrl' => '#', 'tags' => ['Node.js']],
                ],
                'links' => ['github' => '#', 'linkedin' => '#', 'portfolio' => ''],
                'is_verified' => true,
                'is_featured' => false,
                'order' => 5,
            ],
            [
                'name' => 'نور الدين',
                'title' => 'محللة بيانات',
                'city' => 'دمشق',
                'avatar' => 'ن',
                'bio' => 'Python, SQL, Power BI — تحليلات للتجارة الإلكترونية والقطاع المالي.',
                'skills' => ['Python', 'SQL', 'Power BI', 'Pandas', 'Statistics'],
                'is_remote' => true,
                'availability' => 'متاح فوراً',
                'rate_min' => 18,
                'rate_max' => 28,
                'tech_specialty' => 'Data',
                'experience' => [
                    ['role' => 'Data Analyst', 'company' => 'DataSyria', 'years' => '2020–الآن'],
                ],
                'projects' => [
                    ['id' => 1, 'title' => 'Dashboard مبيعات', 'desc' => 'تحليلات real-time', 'image' => '📊', 'demoUrl' => '#', 'githubUrl' => '#', 'tags' => ['Power BI']],
                ],
                'links' => ['github' => '#', 'linkedin' => '#', 'portfolio' => ''],
                'is_verified' => true,
                'is_featured' => false,
                'order' => 6,
            ],
            [
                'name' => 'ياسين جخضر',
                'title' => 'QA Engineer',
                'city' => 'عن بُعد',
                'avatar' => 'ي',
                'bio' => 'Manual و automated testing — Cypress, Selenium, API testing.',
                'skills' => ['Cypress', 'Selenium', 'Jest', 'Postman', 'Test Planning'],
                'is_remote' => true,
                'availability' => 'متاح خلال 3 أيام',
                'rate_min' => 12,
                'rate_max' => 20,
                'tech_specialty' => 'QA',
                'experience' => [
                    ['role' => 'QA Engineer', 'company' => 'QualityFirst', 'years' => '2021–الآن'],
                ],
                'projects' => [
                    ['id' => 1, 'title' => 'Test Automation Suite', 'desc' => 'E2E tests لـ SaaS', 'image' => '🔍', 'demoUrl' => '#', 'githubUrl' => '#', 'tags' => ['Cypress']],
                ],
                'links' => ['github' => '#', 'linkedin' => '#', 'portfolio' => ''],
                'is_verified' => false,
                'is_featured' => false,
                'order' => 7,
            ],
            [
                'name' => 'رامي قاسم',
                'title' => 'Product Manager',
                'city' => 'دمشق',
                'avatar' => 'ر',
                'bio' => 'PM بخبرة تقنية — Agile, roadmap, analytics للمنتجات العربية.',
                'skills' => ['Product Strategy', 'Agile', 'Jira', 'Analytics', 'User Research'],
                'is_remote' => true,
                'availability' => 'متاح فوراً',
                'rate_min' => 25,
                'rate_max' => 35,
                'tech_specialty' => 'Product',
                'experience' => [
                    ['role' => 'Product Manager', 'company' => 'RemoteMENA', 'years' => '2018–الآن'],
                ],
                'projects' => [
                    ['id' => 1, 'title' => 'إطلاق منتج SaaS', 'desc' => 'من الفكرة إلى 10k مستخدم', 'image' => '🚀', 'demoUrl' => '#', 'githubUrl' => '', 'tags' => ['Product']],
                ],
                'links' => ['github' => '', 'linkedin' => '#', 'portfolio' => '#'],
                'is_verified' => true,
                'is_featured' => true,
                'order' => 8,
            ],
        ];

        foreach ($talents as $item) {
            $specialtyName = $item['tech_specialty'];
            unset($item['tech_specialty']);

            $item['tech_specialty_id'] = $specialtyIds[$specialtyName] ?? null;
            $item['slug'] = Talent::generateUniqueSlug($item['name']);
            $item['is_active'] = true;

            Talent::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
