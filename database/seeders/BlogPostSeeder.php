<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorId = User::first()?->id;

        $categories = BlogCategory::all()->keyBy('slug');
        $tags = BlogTag::all()->keyBy('slug');

        $posts = [
            [
                'title' => 'مقدمة إلى Laravel 11: ما الجديد وما الذي تغير؟',
                'slug' => 'laravel-11-introduction',
                'excerpt' => 'استكشف أبرز المميزات والتغييرات في إطار عمل Laravel 11 وأفضل الطرق للترحيل من الإصدارات السابقة.',
                'content' => $this->contentLaravel11(),
                'blog_category_id' => $categories->get('web-development')?->id,
                'tag_slugs' => ['laravel', 'best-practices', 'tutorial'],
                'reading_time' => 8,
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'React Hooks: دليل شامل من الصفر',
                'slug' => 'react-hooks-complete-guide',
                'excerpt' => 'فهم useState، useEffect، useCallback وجميع الـ Hooks الأساسية في React مع أمثلة عملية.',
                'content' => $this->contentReactHooks(),
                'blog_category_id' => $categories->get('web-development')?->id,
                'tag_slugs' => ['react', 'javascript', 'tutorial'],
                'reading_time' => 12,
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'title' => 'Docker للمطورين: تشغيل بيئة تطوير متسقة',
                'slug' => 'docker-for-developers',
                'excerpt' => 'كيف تستخدم Docker لإنشاء بيئة تطوير متطابقة بين الفريق وتجنب مشكلة "يعمل عندي ولا يعمل عندك".',
                'content' => $this->contentDocker(),
                'blog_category_id' => $categories->get('devops-cloud')?->id,
                'tag_slugs' => ['docker', 'best-practices', 'tutorial'],
                'reading_time' => 10,
                'published_at' => Carbon::now()->subDays(15),
            ],
            [
                'title' => 'بايثون وعلوم البيانات: البداية مع Pandas',
                'slug' => 'python-pandas-data-science',
                'excerpt' => 'تعلم أساسيات تحليل البيانات باستخدام مكتبة Pandas في بايثون مع أمثلة على بيانات حقيقية.',
                'content' => $this->contentPandas(),
                'blog_category_id' => $categories->get('python-programming')?->id,
                'tag_slugs' => ['python', 'databases', 'tutorial'],
                'reading_time' => 9,
                'published_at' => Carbon::now()->subDays(20),
            ],
            [
                'title' => 'تصميم REST API احترافي: معايير وأفضل الممارسات',
                'slug' => 'rest-api-design-best-practices',
                'excerpt' => 'قواعد ذهبية لتصميم واجهات API قابلة للتوسع وسهلة الاستخدام للمطورين.',
                'content' => $this->contentRestApi(),
                'blog_category_id' => $categories->get('web-development')?->id,
                'tag_slugs' => ['api', 'best-practices', 'laravel'],
                'reading_time' => 11,
                'published_at' => Carbon::now()->subDays(25),
            ],
            [
                'title' => 'الذكاء الاصطناعي التوليدي: كيف تدمج ChatGPT في تطبيقاتك؟',
                'slug' => 'integrate-chatgpt-in-apps',
                'excerpt' => 'استخدام OpenAI API لإضافة قدرات فهم النصوص والرد التلقائي في مشاريعك البرمجية.',
                'content' => $this->contentChatGPT(),
                'blog_category_id' => $categories->get('artificial-intelligence')?->id,
                'tag_slugs' => ['ai', 'api', 'tips'],
                'reading_time' => 7,
                'published_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'TypeScript للمشاريع الكبيرة: نصائح من الميدان',
                'slug' => 'typescript-large-projects-tips',
                'excerpt' => 'تنظيم المشروع، الـ Types القوية، وتجنب الأخطاء الشائعة عند استخدام TypeScript في مشاريع ضخمة.',
                'content' => $this->contentTypeScript(),
                'blog_category_id' => $categories->get('web-development')?->id,
                'tag_slugs' => ['typescript', 'javascript', 'best-practices'],
                'reading_time' => 8,
                'published_at' => Carbon::now()->subDays(7),
            ],
            [
                'title' => 'Kubernetes للمبتدئين: المفاهيم الأساسية',
                'slug' => 'kubernetes-basics-beginners',
                'excerpt' => 'Pods، Deployments، Services: فهم المكونات الأساسية لأوركسترة الحاويات خطوة بخطوة.',
                'content' => $this->contentKubernetes(),
                'blog_category_id' => $categories->get('devops-cloud')?->id,
                'tag_slugs' => ['kubernetes', 'docker', 'tutorial'],
                'reading_time' => 14,
                'published_at' => Carbon::now()->subDays(12),
            ],
            [
                'title' => 'Flutter مقابل React Native: أيهما تختار في 2025؟',
                'slug' => 'flutter-vs-react-native-2025',
                'excerpt' => 'مقارنة موضوعية بين Flutter و React Native من حيث الأداء، المجتمع، وسوق العمل.',
                'content' => $this->contentFlutterVsReact(),
                'blog_category_id' => $categories->get('mobile-development')?->id,
                'tag_slugs' => ['react', 'tips', 'best-practices'],
                'reading_time' => 6,
                'published_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'تحسين أداء قواعد البيانات: فهارس واستعلامات أسرع',
                'slug' => 'database-performance-indexing',
                'excerpt' => 'كيف تقلل زمن الاستجابة وتحمّل الخادم بفهم الفهارس وتحليل الاستعلامات البطيئة.',
                'content' => $this->contentDatabase(),
                'blog_category_id' => $categories->get('databases')?->id,
                'tag_slugs' => ['databases', 'laravel', 'best-practices', 'tips'],
                'reading_time' => 10,
                'published_at' => Carbon::now()->subDays(18),
            ],
        ];

        foreach ($posts as $postData) {
            $tagSlugs = $postData['tag_slugs'] ?? [];
            unset($postData['tag_slugs']);

            $post = BlogPost::updateOrCreate(
                ['slug' => $postData['slug']],
                array_merge($postData, [
                    'author_id' => $authorId,
                    'status' => 'published',
                    'meta_title' => $postData['title'] . ' | مدونة ياسين جوخدار',
                    'meta_description' => $postData['excerpt'],
                    'focus_keyword' => null,
                    'is_indexable' => true,
                    'is_followable' => true,
                    'robots_meta' => 'index,follow',
                    'is_featured' => in_array($postData['slug'], ['laravel-11-introduction', 'react-hooks-complete-guide']),
                    'language' => 'ar',
                    'schema_type' => 'TechArticle',
                ])
            );

            $tagIds = $tags->whereIn('slug', $tagSlugs)->pluck('id')->toArray();
            $post->tags()->sync($tagIds);
        }

        // تحديث عداد التدوينات في التصنيفات والوسوم
        BlogCategory::all()->each(fn ($c) => $c->updatePostsCount());
        BlogTag::all()->each(fn ($t) => $t->updatePostsCount());
    }

    private function contentLaravel11(): string
    {
        return '<p>أطلق Laravel 11 مع تبسيط هيكل المشروع وتحسينات في الأداء. في هذا المقال نستعرض أبرز التغييرات.</p>
<h2>هيكل المشروع الجديد</h2>
<p>تم دمج عدد من الملفات وتقليل التعقيد الافتراضي. مجلد <code>app/Http</code> أصبح أنحف، وملف الـ routes أصبح أوضح.</p>
<h2>تحسينات الأداء</h2>
<p>تحميل أسرع للـ routes والـ config مع الاعتماد على الـ cache في بيئة الإنتاج.</p>
<h2>نصائح الترحيل</h2>
<p>استخدم دليل الترحيل الرسمي واختبر بيئة التطوير قبل التحديث على الإنتاج.</p>';
    }

    private function contentReactHooks(): string
    {
        return '<p>React Hooks غيّرت طريقة كتابة المكونات من Classes إلى دوال بسيطة وقابلة لإعادة الاستخدام.</p>
<h2>useState و useEffect</h2>
<p><code>useState</code> لإدارة الحالة المحلية و<code>useEffect</code> للتعامل مع الآثار الجانبية مثل طلبات الـ API.</p>
<h2>useCallback و useMemo</h2>
<p>لتحسين الأداء وتجنب إعادة الـ render غير الضرورية عند تمرير الدوال أو القيم للمكونات الفرعية.</p>
<h2>قواعد Hooks</h2>
<p>لا تستدعِ الـ Hooks داخل حلقات أو شروط؛ استدعها دائماً في المستوى الأعلى من الدالة.</p>';
    }

    private function contentDocker(): string
    {
        return '<p>Docker يوفّر حاويات متسقة تعمل بنفس الطريقة على أي جهاز أو سيرفر.</p>
<h2>Dockerfile أساسي</h2>
<p>ابدأ بصورة أساسية مثل <code>php:8.2-fpm</code> أو <code>node:20</code> وأضف تبعياتك خطوة بخطوة.</p>
<h2>Docker Compose للتطوير</h2>
<p>شغّل التطبيق، قاعدة البيانات، و Redis من ملف <code>docker-compose.yml</code> واحد.</p>
<h2>أفضل الممارسات</h2>
<p>استخدم صور صغيرة، طبقات مرتبة، وعدم تشغيل العمليات كـ root قدر الإمكان.</p>';
    }

    private function contentPandas(): string
    {
        return '<p>Pandas هي المكتبة الأكثر استخداماً لتحليل البيانات في بايثون.</p>
<h2>DataFrame و Series</h2>
<p>الجدول (DataFrame) والسلسلة (Series) هما البنية الأساسية. تعلم القراءة من CSV و Excel.</p>
<h2>التصفية والتحويل</h2>
<p>استخدم <code>loc</code> و <code>iloc</code> للوصول للصفوف والأعمدة، و groupby للتجميع.</p>
<h2>دمج الجداول</h2>
<p>merge و concat لربط عدة مصادر بيانات بشكل احترافي.</p>';
    }

    private function contentRestApi(): string
    {
        return '<p>واجهة API جيدة التصميم تسهّل على المطورين الاستخدام وتقلل الأخطاء.</p>
<h2>الأسماء والمسارات</h2>
<p>استخدم أسماء جمع (users وليس user)، وأفعال HTTP الصحيحة: GET للقراءة، POST للإنشاء، PUT/PATCH للتحديث، DELETE للحذف.</p>
<h2>الترقيم والتصفية</h2>
<p>قدم pagination وفلترة واضحة مثل <code>?page=2&amp;per_page=20&amp;status=active</code>.</p>
<h2>رموز الحالة والرسائل</h2>
<p>استخدم 200، 201، 400، 404، 422 بشكل متسق مع رسائل خطأ مفهومة.</p>';
    }

    private function contentChatGPT(): string
    {
        return '<p>دمج نماذج اللغة الكبيرة مثل GPT في تطبيقاتك يفتح أبواباً جديدة للتجربة.</p>
<h2>OpenAI API</h2>
<p>استخدم الـ API الرسمي مع مفتاحك. يمكنك اختيار النموذج (مثل gpt-4) وضبط درجة الإبداع (temperature).</p>
<h2>توفير التكلفة</h2>
<p>حدد طول الـ context والرد، واستخدم الـ streaming للردود الطويلة لتحسين تجربة المستخدم.</p>
<h2>الأمان والخصوصية</h2>
<p>لا ترسل بيانات حساسة دون تشفير، واطلع على سياسات الاستخدام والاحتفاظ بالبيانات.</p>';
    }

    private function contentTypeScript(): string
    {
        return '<p>TypeScript يقلل الأخطاء ويحسّن تجربة التطوير في المشاريع الكبيرة.</p>
<h2>واجهات وأنواع قوية</h2>
<p>عرّف واجهات للـ API والكائنات المعقدة بدلاً من الاعتماد على any.</p>
<h2>تقسيم المشروع</h2>
<p>استخدم paths في tsconfig لتقصير الـ imports، ووحدات منفصلة للأنواع المشتركة.</p>
<h2>التحقق الصارم</h2>
<p>فعّل strict mode وتجنب type assertion إلا عند الضرورة.</p>';
    }

    private function contentKubernetes(): string
    {
        return '<p>Kubernetes منصة أوركسترة للحاويات؛ فهم المفاهيم الأساسية يسهّل البداية.</p>
<h2>Pods</h2>
<p>أصغر وحدة قابلة للنشر؛ وعاء واحد أو أكثر من الحاويات يتشاركان الشبكة والتخزين.</p>
<h2>Deployments</h2>
<p>إدارة تكرارات الـ Pods، التحديثات المتدحرجة، والرجوع للإصدار السابق.</p>
<h2>Services</h2>
<p>تعريض الـ Pods للشبكة الداخلية أو الخارجية عبر موازن الحمل والعقد.</p>';
    }

    private function contentFlutterVsReact(): string
    {
        return '<p>كلا الإطارين يسمحان ببناء تطبيقات متعددة المنصات؛ الفرق في الأداء والأدوات والمجتمع.</p>
<h2>Flutter</h2>
<p>أداء قريب من الأصلي، واجهة غنية، لغة Dart. مناسب لتطبيقات تحتاج واجهات مخصصة كثيرة.</p>
<h2>React Native</h2>
<p>يعتمد على JavaScript/TypeScript ومكتبة React. توافق مع الويب وتوفر مطورين أكبر.</p>
<h2>الخلاصة</h2>
<p>اختر بناءً على فريقك، نوع التطبيق، وحاجتك للتحديثات السريعة من المجتمع.</p>';
    }

    private function contentDatabase(): string
    {
        return '<p>تحسين استعلامات وقواعد البيانات يقلل زمن الاستجابة ويحسّن تجربة المستخدم.</p>
<h2>الفهارس (Indexes)</h2>
<p>أضف فهارس على الأعمدة المستخدمة في WHERE و JOIN و ORDER BY. تجنب الفهارس الكثيرة على جدول واحد.</p>
<h2>تحليل الاستعلامات البطيئة</h2>
<p>استخدم EXPLAIN في MySQL/PostgreSQL أو أدوات Laravel مثل Telescope لمعرفة الاستعلامات الأبطأ.</p>
<h2>تجنب N+1</h2>
<p>استخدم Eager Loading (with) في Eloquent بدلاً من تحميل العلاقات داخل الحلقات.</p>';
    }
}
