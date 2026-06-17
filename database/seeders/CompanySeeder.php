<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'SyriaDev Studio', 'sector' => 'تطوير البرمجيات', 'category' => 'tech',
                'logo' => '💻', 'jobs_count' => 8, 'rating' => 4.8, 'location' => 'دمشق',
                'founded' => '2018', 'team_size' => '30–45', 'website' => 'syriadev.studio', 'timezone' => 'UTC+2',
                'contact_emails' => [
                    ['label' => 'التوظيف', 'email' => 'careers@syriadev.studio'],
                    ['label' => 'عام', 'email' => 'hello@syriadev.studio'],
                ],
                'contact_websites' => [
                    ['label' => 'الموقع الرئيسي', 'url' => 'syriadev.studio'],
                    ['label' => 'المدونة', 'url' => 'blog.syriadev.studio'],
                ],
                'social_links' => [
                    ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/syriadev'],
                    ['platform' => 'github', 'url' => 'https://github.com/syriadev'],
                    ['platform' => 'twitter', 'url' => 'https://x.com/syriadev'],
                ],
                'payment_methods' => ['Wise', 'PayPal', 'Bank Transfer'],
                'about' => 'SyriaDev Studio ستوديو برمجي سوري يبني منتجات SaaS للسوق العربي والعالمي. نعمل بنموذج remote-first منذ التأسيس، ونجمع بين خبرة محلية عميقة وفهم احتياجات المستخدم العربي مع معايير هندسة عالمية. فرقنا موزعة بين دمشق وحلب وأوروبا، ونركز على React و Node.js ومنتجات B2B قابلة للتوسع.',
                'mission' => 'تمكين المواهب السورية من بناء منتجات تقنية عالمية المستوى — دون الحاجة للهجرة أو التنازل عن جودة الحياة.',
                'values' => ['الجودة قبل السرعة', 'شفافية كاملة', 'نمو الفريق', 'تأثير حقيقي', 'احترام التوازن'],
                'perks' => ['راتب USD ثابت', 'ساعات مرنة', 'ميزانية تعلم $500/سنة', 'معدات عمل', 'إجازة 24 يوم', 'تأمين صحي'],
                'culture' => ['اجتماعات async أولاً', 'توثيق مفتوح', 'مراجعات كود أسبوعية', 'يوم جمعة بدون اجتماعات', 'قنوات Slack عربية/إنجليزية'],
                'tech_stack' => ['React', 'TypeScript', 'Node.js', 'PostgreSQL', 'Docker', 'AWS'],
                'is_verified' => true, 'is_featured' => true, 'order' => 1,
            ],
            [
                'name' => 'CloudBridge EU', 'sector' => 'DevOps & Cloud', 'category' => 'tech',
                'logo' => '☁️', 'jobs_count' => 5, 'rating' => 4.6, 'location' => 'أوروبا',
                'founded' => '2020', 'team_size' => '20–35', 'website' => 'cloudbridge.eu', 'timezone' => 'UTC+1',
                'payment_methods' => ['Wise', 'Bank Transfer', 'SEPA'],
                'about' => 'CloudBridge EU شركة بنية تحتية سحابية مقرها أمستردام، تستقطب مهندسين سوريين للعمل عن بُعد على مشاريع أوروبية.',
                'mission' => 'جسر المواهب السورية بالبنية السحابية الأوروبية — بعقود شفافة ودفع عادل.',
                'values' => ['أمان أولاً', 'أتمتة دائمة', 'تعلم مستمر', 'ثقة متبادلة', 'استدامة'],
                'perks' => ['عقد B2B أوروبي', 'إجازة 28 يوم', 'ميزانية شهادات AWS', 'ساعات مرنة', 'تأمين صحي', 'مكافآت أداء'],
                'culture' => ['on-call مدروس', 'postmortems بدون لوم', 'مؤتمرات سحابية سنوية', 'mentorship برنامج', 'عمل async'],
                'tech_stack' => ['AWS', 'Kubernetes', 'Terraform', 'Docker', 'GitHub Actions', 'Prometheus'],
                'is_verified' => true, 'is_featured' => true, 'order' => 2,
            ],
            [
                'name' => 'Pixel Damascus', 'sector' => 'UI/UX Design', 'category' => 'design',
                'logo' => '🎨', 'jobs_count' => 3, 'rating' => 4.5, 'location' => 'دمشق',
                'founded' => '2019', 'team_size' => '15–25', 'website' => 'pixeldamascus.com', 'timezone' => 'UTC+2',
                'payment_methods' => ['Wise', 'PayPal'],
                'about' => 'Pixel Damascus ستوديو تصميم واجهات رقمية من دمشق، متخصص في UI/UX للمنتجات العربية والعالمية.',
                'mission' => 'نصنع واجهات جميلة وعملية — تُحبها المستخدمون وتُقدّرها الشركات.',
                'values' => ['المستخدم أولاً', 'تفاصيل دقيقة', 'تعاون مفتوح', 'إبداع منضبط', 'تسليم في الوقت'],
                'perks' => ['راتب USD', 'Figma Pro مدفوع', 'ساعات مرنة', 'مشاريع متنوعة', 'مراجعات تصميم أسبوعية', 'portfolio support'],
                'culture' => ['تصميم collaborative', 'critique sessions', 'مكتبة تصميم مشتركة', 'زيارات فريق سنوية', 'عمل هجين اختياري'],
                'tech_stack' => ['Figma', 'Framer', 'Protopie', 'Design Systems', 'Maze', 'Notion'],
                'is_verified' => true, 'is_featured' => true, 'order' => 3,
            ],
            [
                'name' => 'DataSyria', 'sector' => 'تحليل البيانات', 'category' => 'data',
                'logo' => '📊', 'jobs_count' => 4, 'rating' => 4.4, 'location' => 'عن بُعد',
                'founded' => '2021', 'team_size' => '10–18', 'website' => 'datasyria.io', 'timezone' => 'UTC+2',
                'payment_methods' => ['Wise', 'PayPal'],
                'about' => 'DataSyria فريق تحليلات بيانات سوري يخدم القطاع التجاري والتقني.',
                'mission' => 'تحويل البيانات إلى قرارات — للسوق السوري والعربي.',
                'values' => ['دقة البيانات', 'وضوح التقارير', 'خصوصية المستخدم', 'تأثير ملموس'],
                'perks' => ['USD', 'أدوات BI مدفوعة', 'تدريب SQL/Python', 'مشاريع متنوعة', 'ساعات مرنة'],
                'culture' => ['data-driven decisions', 'توثيق كل تحليل', 'pair analysis', 'اجتماعات قصيرة'],
                'tech_stack' => ['Python', 'SQL', 'dbt', 'Metabase', 'BigQuery', 'Pandas'],
                'is_verified' => true, 'is_featured' => true, 'order' => 4,
            ],
            [
                'name' => 'Mobile Aleppo', 'sector' => 'تطبيقات الجوال', 'category' => 'tech',
                'logo' => '📱', 'jobs_count' => 6, 'rating' => 4.7, 'location' => 'حلب',
                'founded' => '2017', 'team_size' => '20–30', 'website' => 'mobilealeppo.com', 'timezone' => 'UTC+2',
                'payment_methods' => ['Wise', 'PayPal'],
                'about' => 'Mobile Aleppo متخصصون في تطبيقات Flutter للسوق العربي.',
                'mission' => 'تطبيقات جوال عربية بجودة عالمية.',
                'values' => ['أداء عالي', 'UX محلي', 'كود نظيف', 'تسليم سريع'],
                'perks' => ['USD', 'أجهزة اختبار', 'مؤتمرات Flutter', 'مكافآت إطلاق', 'ساعات مرنة'],
                'culture' => ['sprint أسبوعي', 'demo يوم الجمعة', 'كود ريفيو إلزامي', 'عمل هجين'],
                'tech_stack' => ['Flutter', 'Dart', 'Firebase', 'REST APIs', 'CI/CD', 'Figma'],
                'is_verified' => true, 'is_featured' => true, 'order' => 5,
            ],
            [
                'name' => 'TechLatakia', 'sector' => 'Backend & APIs', 'category' => 'tech',
                'logo' => '⚙️', 'jobs_count' => 4, 'rating' => 4.3, 'location' => 'اللاذقية',
                'founded' => '2019', 'team_size' => '12–20', 'website' => 'techlatakia.dev', 'timezone' => 'UTC+2',
                'payment_methods' => ['Wise', 'PayPal'],
                'about' => 'TechLatakia فريق backend سوري يبني APIs و microservices للمنتجات الرقمية.',
                'mission' => 'بنية تحتية backend موثوقة — سريعة وآمنة وقابلة للتوسع.',
                'values' => ['موثوقية', 'أمان', 'بساطة المعمارية', 'توثيق API'],
                'perks' => ['USD', 'ساعات مرنة', 'تدريب تقني', 'معدات عمل', 'إجازة مدفوعة'],
                'culture' => ['API-first', 'اختبارات تلقائية', 'on-call rotation عادل', 'async communication'],
                'tech_stack' => ['Node.js', 'Go', 'PostgreSQL', 'Redis', 'gRPC', 'Docker'],
                'is_verified' => true, 'is_featured' => false, 'order' => 6,
            ],
            [
                'name' => 'RemoteMENA', 'sector' => 'Product & Strategy', 'category' => 'tech',
                'logo' => '📋', 'jobs_count' => 2, 'rating' => 4.5, 'location' => 'MENA',
                'founded' => '2022', 'team_size' => '8–15', 'website' => 'remotemena.co', 'timezone' => 'UTC+2',
                'payment_methods' => ['Wise', 'PayPal'],
                'about' => 'RemoteMENA استشارات منتج واستراتيجية للشركات التقنية في المنطقة.',
                'mission' => 'منتجات تقنية تفهم السوق العربي — وتنمو باستدامة.',
                'values' => ['فهم السوق', 'قرارات مبنية على بيانات', 'شراكة طويلة', 'شفافية'],
                'perks' => ['USD', 'مشاريع متنوعة', 'شبكة واسعة', 'ساعات مرنة', 'تعلم مستمر'],
                'culture' => ['workshops شهرية', 'تقارير مفتوحة', 'تعاون مع founders', 'مرونة عالية'],
                'tech_stack' => ['Notion', 'Mixpanel', 'Figma', 'Jira', 'Miro', 'SQL'],
                'is_verified' => true, 'is_featured' => false, 'order' => 7,
            ],
            [
                'name' => 'GlobalTech Hiring', 'sector' => 'توظيف عالمي', 'category' => 'tech',
                'logo' => '🌍', 'jobs_count' => 12, 'rating' => 4.9, 'location' => 'عالمي',
                'founded' => '2016', 'team_size' => '40–60', 'website' => 'globaltech.hiring', 'timezone' => 'UTC±0',
                'payment_methods' => ['Wise', 'PayPal', 'Bank Transfer', 'Deel'],
                'about' => 'GlobalTech Hiring جسر بين المواهب السورية والشركات الأمريكية والأوروبية.',
                'mission' => 'كل موهبة سورية تستحق فرصة عالمية — بكرامة وعدالة.',
                'values' => ['عدالة', 'شفافية', 'متابعة مستمرة', 'احترام المرشح', 'نجاح طويل الأمد'],
                'perks' => ['عقود عالمية', 'دعم قانوني', 'تدريب مقابلات', 'مراجعة عقود', 'مجتمع alumni'],
                'culture' => ['دعم شخصي لكل مرشح', 'مجموعات Telegram', 'events افتراضية', 'feedback مستمر'],
                'tech_stack' => ['ATS', 'LinkedIn', 'Slack', 'Notion', 'Calendly', 'Greenhouse'],
                'is_verified' => true, 'is_featured' => true, 'order' => 8,
            ],
            [
                'name' => 'QualityFirst Remote', 'sector' => 'QA & Testing', 'category' => 'tech',
                'logo' => '🔍', 'jobs_count' => 3, 'rating' => 4.2, 'location' => 'عن بُعد',
                'founded' => '2023', 'team_size' => '8–12', 'website' => 'qualityfirst.dev', 'timezone' => 'UTC+2',
                'payment_methods' => ['Wise', 'PayPal'],
                'about' => 'QualityFirst Remote فريق QA سوري يختبر منتجات SaaS قبل الإطلاق.',
                'mission' => 'لا إطلاق بدون جودة — حتى للمنتجات السريعة.',
                'values' => ['دقة', 'تغطية شاملة', 'تواصل واضح', 'تسليم سريع'],
                'perks' => ['USD', 'أدوات اختبار', 'تدريب Playwright', 'ساعات مرنة', 'مشاريع متنوعة'],
                'culture' => ['bug reports مفصلة', 'تعاون مع devs', 'اختبار exploratory', 'async'],
                'tech_stack' => ['Playwright', 'Cypress', 'Jest', 'Postman', 'Jira', 'TestRail'],
                'is_verified' => false, 'is_featured' => false, 'order' => 9,
            ],
            [
                'name' => 'CodeDamascus', 'sector' => 'تعليم تقني', 'category' => 'education',
                'logo' => '📚', 'jobs_count' => 2, 'rating' => 4.6, 'location' => 'دمشق',
                'founded' => '2018', 'team_size' => '15–22', 'website' => 'codedamascus.org', 'timezone' => 'UTC+2',
                'payment_methods' => ['PayPal', 'Wise'],
                'about' => 'CodeDamascus أكاديمية برمجة وتدريب تقني من دمشق.',
                'mission' => 'تعليم تقني عملي يفتح أبواب العمل العالمي.',
                'values' => ['تعليم عملي', 'مجتمع داعم', 'فرص حقيقية', 'جودة المحتوى'],
                'perks' => ['USD', 'تدريس مرن', 'منصة تعليم', 'شبكة خريجين', 'مشاريع حقيقية'],
                'culture' => ['تعلم بالممارسة', 'mentorship', 'مجتمع Discord', 'demo days'],
                'tech_stack' => ['JavaScript', 'React', 'Node.js', 'Python', 'Git', 'VS Code'],
                'is_verified' => true, 'is_featured' => false, 'order' => 10,
            ],
        ];

        foreach ($companies as $item) {
            $item['slug'] = Company::generateUniqueSlug($item['name']);
            $item['is_active'] = true;
            $item['is_remote_friendly'] = true;
            $item['is_syria_friendly'] = true;

            Company::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
