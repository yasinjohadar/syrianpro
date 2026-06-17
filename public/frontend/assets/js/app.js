/**
 * تك سوريا — منصة المواهب التقنية السورية
 * Main JavaScript File
 */

const BRAND = 'تك سوريا';

// ========================
// DATA
// ========================
const JOBS = [
  {
    id: 1, title: 'مطور React أول', company: 'SyriaDev Studio', logo: '💻', location: 'عن بُعد', type: 'دوام كامل',
    salary: '1,200 – 1,800', salaryUSD: '1200-1800', currency: '$',
    remoteType: 'full-remote', timezone: 'UTC+2', syriaFriendly: true,
    paymentMethods: ['Wise', 'PayPal'],
    skills: ['React', 'TypeScript', 'Node.js'],
    tags: ['full-time', 'remote', 'frontend'],
    tagLabels: [{ t: 'عن بُعد 🌐', c: 'teal' }, { t: 'Frontend', c: 'blue' }, { t: 'Senior', c: 'gold' }],
    date: 'منذ يومين', isNew: true,
    description: 'نبحث عن مطور React موهوب للانضمام لفريقنا السوري عن بُعد. ستعمل على منتجات SaaS تخدم السوق العربي والعالمي.',
    responsibilities: ['تطوير واجهات React/TypeScript عالية الجودة', 'التعاون مع فريق Backend على REST APIs', 'كتابة اختبارات ومراجعة كود', 'المشاركة في قرارات المنتج'],
    requirements: ['3+ سنوات React/TypeScript', 'خبرة Node.js', 'Git و CI/CD', 'عربي وإنجليزي'],
    benefits: ['راتب بالدولار', 'ساعات مرنة', 'تدريب مستمر', 'معدات عمل']
  },
  {
    id: 2, title: 'مهندس DevOps', company: 'CloudBridge EU', logo: '☁️', location: 'عن بُعد · أوروبا', type: 'دوام كامل',
    salary: '2,000 – 2,800', salaryUSD: '2000-2800', currency: '$',
    remoteType: 'full-remote', timezone: 'UTC+1', syriaFriendly: true,
    paymentMethods: ['Wise', 'Bank Transfer'],
    skills: ['Docker', 'Kubernetes', 'AWS', 'Terraform'],
    tags: ['full-time', 'remote', 'devops'],
    tagLabels: [{ t: 'عن بُعد 🌐', c: 'teal' }, { t: 'DevOps', c: 'blue' }, { t: 'Senior', c: 'gold' }],
    date: 'منذ يوم', isNew: true,
    description: 'شركة أوروبية تبحث عن مهندس DevOps للعمل عن بُعد مع فريق موزع. خبرة سورية مرحب بها.',
    responsibilities: ['إدارة البنية السحابية AWS', 'أتمتة CI/CD pipelines', 'مراقبة الأنظمة', 'تحسين الأمان'],
    requirements: ['4+ سنوات DevOps', 'AWS certified preferred', 'Linux و scripting', 'إنجليزي متقدم'],
    benefits: ['عقد B2B', 'إجازة 28 يوم', 'ميزانية تعلم', 'تأمين صحي']
  },
  {
    id: 3, title: 'مصمم UI/UX', company: 'Pixel Damascus', logo: '🎨', location: 'دمشق · هجين', type: 'هجين',
    salary: '800 – 1,200', salaryUSD: '800-1200', currency: '$',
    remoteType: 'hybrid', timezone: 'UTC+2', syriaFriendly: true,
    paymentMethods: ['PayPal', 'Wise'],
    skills: ['Figma', 'UI Design', 'Prototyping'],
    tags: ['hybrid', 'design'],
    tagLabels: [{ t: 'هجين', c: 'blue' }, { t: 'UI/UX', c: 'gold' }],
    date: 'منذ 3 أيام', isNew: false,
    description: 'ستوديو تصميم دمشقي يبحث عن مصمم UI/UX لمنتجات رقمية ومواقع الشركات.',
    responsibilities: ['تصميم واجهات Figma', 'اختبار المستخدم', 'Design system', 'التعاون مع المطورين'],
    requirements: ['2+ سنوات UI/UX', 'Portfolio قوي', 'Figma متقدم'],
    benefits: ['بيئة إبداعية', 'مشاريع متنوعة', 'مرونة']
  },
  {
    id: 4, title: 'محلل بيانات', company: 'DataSyria', logo: '📊', location: 'عن بُعد', type: 'عن بُعد',
    salary: '1,000 – 1,500', salaryUSD: '1000-1500', currency: '$',
    remoteType: 'full-remote', timezone: 'UTC+2', syriaFriendly: true,
    paymentMethods: ['Wise'],
    skills: ['Python', 'SQL', 'Power BI'],
    tags: ['remote', 'data'],
    tagLabels: [{ t: 'عن بُعد 🌐', c: 'teal' }, { t: 'Data', c: 'blue' }],
    date: 'منذ 5 أيام', isNew: false,
    description: 'تحليل بيانات لقطاع التجارة الإلكترونية السورية والإقليمية.',
    responsibilities: ['بناء dashboards', 'تحليل البيانات', 'تقارير للإدارة'],
    requirements: ['Python و SQL', 'Power BI أو Tableau', 'إحصاء'],
    benefits: ['Remote كامل', 'USD', 'نمو مهني']
  },
  {
    id: 5, title: 'مطور Flutter', company: 'Mobile Aleppo', logo: '📱', location: 'حلب · عن بُعد', type: 'دوام كامل',
    salary: '900 – 1,400', salaryUSD: '900-1400', currency: '$',
    remoteType: 'full-remote', timezone: 'UTC+2', syriaFriendly: true,
    paymentMethods: ['PayPal', 'Wise'],
    skills: ['Flutter', 'Dart', 'Firebase'],
    tags: ['full-time', 'mobile'],
    tagLabels: [{ t: 'عن بُعد 🌐', c: 'teal' }, { t: 'Flutter', c: 'gold' }],
    date: 'اليوم', isNew: true,
    description: 'تطبيقات جوال للسوق العربي — فريق حلب يعمل عن بُعد.',
    responsibilities: ['تطوير تطبيقات Flutter', 'تكامل APIs', 'نشر App Store/Play'],
    requirements: ['2+ سنوات Flutter', 'Firebase', 'State management'],
    benefits: ['فريق شاب', 'مشاريع حقيقية', 'USD']
  },
  {
    id: 6, title: 'QA Engineer', company: 'QualityFirst Remote', logo: '🔍', location: 'عن بُعد', type: 'دوام كامل',
    salary: '700 – 1,100', salaryUSD: '700-1100', currency: '$',
    remoteType: 'full-remote', timezone: 'UTC+3', syriaFriendly: true,
    paymentMethods: ['Wise', 'PayPal'],
    skills: ['Selenium', 'Cypress', 'Manual Testing'],
    tags: ['full-time', 'qa'],
    tagLabels: [{ t: 'عن بُعد 🌐', c: 'teal' }, { t: 'QA', c: 'accent' }],
    date: 'منذ أسبوع', isNew: false,
    description: 'اختبار منتجات SaaS — manual و automated testing.',
    responsibilities: ['كتابة test cases', 'automation', 'bug reporting'],
    requirements: ['1+ سنة QA', 'Cypress أو Selenium', 'attention to detail'],
    benefits: ['Remote', 'تدريب automation', 'USD']
  },
  {
    id: 7, title: 'Backend Node.js', company: 'TechLatakia', logo: '⚙️', location: 'اللاذقية · عن بُعد', type: 'دوام كامل',
    salary: '1,100 – 1,700', salaryUSD: '1100-1700', currency: '$',
    remoteType: 'full-remote', timezone: 'UTC+2', syriaFriendly: true,
    paymentMethods: ['Wise'],
    skills: ['Node.js', 'PostgreSQL', 'Redis'],
    tags: ['full-time', 'backend'],
    tagLabels: [{ t: 'عن بُعد 🌐', c: 'teal' }, { t: 'Backend', c: 'blue' }],
    date: 'منذ يومين', isNew: true,
    description: 'APIs و microservices لمنصة توصيل محلية.',
    responsibilities: ['REST/GraphQL APIs', 'Database design', 'Performance optimization'],
    requirements: ['3+ سنوات Node.js', 'PostgreSQL', 'Redis'],
    benefits: ['Startup environment', 'Equity optional', 'USD']
  },
  {
    id: 8, title: 'Product Manager', company: 'RemoteMENA', logo: '📋', location: 'عن بُعد · MENA', type: 'دوام كامل',
    salary: '1,500 – 2,200', salaryUSD: '1500-2200', currency: '$',
    remoteType: 'full-remote', timezone: 'UTC+2', syriaFriendly: true,
    paymentMethods: ['Wise', 'PayPal'],
    skills: ['Product', 'Agile', 'Analytics'],
    tags: ['full-time', 'pm'],
    tagLabels: [{ t: 'عن بُعد 🌐', c: 'teal' }, { t: 'PM', c: 'accent' }],
    date: 'منذ 3 أيام', isNew: false,
    description: 'إدارة منتج تقني يخدم السوق العربي — remote-first.',
    responsibilities: ['Roadmap', 'User stories', 'Stakeholder management'],
    requirements: ['3+ سنوات PM', 'Agile/Scrum', 'Technical background'],
    benefits: ['Leadership role', 'USD', 'Global team']
  },
  {
    id: 9, title: 'مطور Full Stack', company: 'GlobalTech Hiring', logo: '🌍', location: 'عن بُعد · عالمي', type: 'دوام كامل',
    salary: '1,800 – 2,500', salaryUSD: '1800-2500', currency: '$',
    remoteType: 'full-remote', timezone: 'Flexible', syriaFriendly: true,
    paymentMethods: ['Wise', 'PayPal', 'Crypto'],
    skills: ['React', 'Node.js', 'AWS'],
    tags: ['full-time', 'remote', 'fullstack'],
    tagLabels: [{ t: 'عن بُعد 🌐', c: 'teal' }, { t: 'Full Stack', c: 'gold' }],
    date: 'اليوم', isNew: true,
    description: 'شركة أمريكية تستقطب مواهب سورية للعمل عن بُعد — full stack role.',
    responsibilities: ['End-to-end features', 'Code review', 'Architecture decisions'],
    requirements: ['5+ سنوات', 'React + Node', 'AWS basics', 'English fluent'],
    benefits: ['Top USD rates', 'US holidays', 'Equipment stipend']
  },
];

const COMPANIES = [
  {
    id: 1, name: 'SyriaDev Studio', sector: 'تطوير البرمجيات', logo: '💻', jobs: 8, verified: true, rating: '4.8', remoteFriendly: true, location: 'دمشق',
    founded: '2018', teamSize: '30–45', website: 'syriadev.studio', timezone: 'UTC+2',
    paymentMethods: ['Wise', 'PayPal', 'Bank Transfer'],
    about: 'SyriaDev Studio ستوديو برمجي سوري يبني منتجات SaaS للسوق العربي والعالمي. نعمل بنموذج remote-first منذ التأسيس، ونجمع بين خبرة محلية عميقة وفهم احتياجات المستخدم العربي مع معايير هندسة عالمية. فرقنا موزعة بين دمشق وحلب وأوروبا، ونركز على React و Node.js ومنتجات B2B قابلة للتوسع.',
    mission: 'تمكين المواهب السورية من بناء منتجات تقنية عالمية المستوى — دون الحاجة للهجرة أو التنازل عن جودة الحياة.',
    values: ['الجودة قبل السرعة', 'شفافية كاملة', 'نمو الفريق', 'تأثير حقيقي', 'احترام التوازن'],
    perks: ['راتب USD ثابت', 'ساعات مرنة', 'ميزانية تعلم $500/سنة', 'معدات عمل', 'إجازة 24 يوم', 'تأمين صحي'],
    culture: ['اجتماعات async أولاً', 'توثيق مفتوح', 'مراجعات كود أسبوعية', 'يوم جمعة بدون اجتماعات', 'قنوات Slack عربية/إنجليزية'],
    techStack: ['React', 'TypeScript', 'Node.js', 'PostgreSQL', 'Docker', 'AWS'],
  },
  {
    id: 2, name: 'CloudBridge EU', sector: 'DevOps & Cloud', logo: '☁️', jobs: 5, verified: true, rating: '4.6', remoteFriendly: true, location: 'أوروبا',
    founded: '2020', teamSize: '20–35', website: 'cloudbridge.eu', timezone: 'UTC+1',
    paymentMethods: ['Wise', 'Bank Transfer', 'SEPA'],
    about: 'CloudBridge EU شركة بنية تحتية سحابية مقرها أمستردام، تستقطب مهندسين سوريين للعمل عن بُعد على مشاريع أوروبية. ندير بنية AWS و Kubernetes لشركات ناشئة ومتوسطة، ونؤمن بأن المواهب السورية من أفضل المهندسين في المنطقة عندما تُعطى البيئة المناسبة.',
    mission: 'جسر المواهب السورية بالبنية السحابية الأوروبية — بعقود شفافة ودفع عادل.',
    values: ['أمان أولاً', 'أتمتة دائمة', 'تعلم مستمر', 'ثقة متبادلة', 'استدامة'],
    perks: ['عقد B2B أوروبي', 'إجازة 28 يوم', 'ميزانية شهادات AWS', 'ساعات مرنة', 'تأمين صحي', 'مكافآت أداء'],
    culture: ['on-call مدروس', 'postmortems بدون لوم', 'مؤتمرات سحابية سنوية', 'mentorship برنامج', 'عمل async'],
    techStack: ['AWS', 'Kubernetes', 'Terraform', 'Docker', 'GitHub Actions', 'Prometheus'],
  },
  {
    id: 3, name: 'Pixel Damascus', sector: 'UI/UX Design', logo: '🎨', jobs: 3, verified: true, rating: '4.5', remoteFriendly: true, location: 'دمشق',
    founded: '2019', teamSize: '15–25', website: 'pixeldamascus.com', timezone: 'UTC+2',
    paymentMethods: ['Wise', 'PayPal'],
    about: 'Pixel Damascus ستوديو تصميم واجهات رقمية من دمشق، متخصص في UI/UX للمنتجات العربية والعالمية. نصمم تجارب مستخدم تبدأ من بحث المستخدم وتنتهي بـ design systems قابلة للتوسع. عملاؤنا شركات ناشئة أوروبية ومنتجات SaaS عربية — ونفخر بأن 60% من فريقنا سوري يعمل عن بُعد بالكامل.',
    mission: 'نصنع واجهات جميلة وعملية — تُحبها المستخدمون وتُقدّرها الشركات.',
    values: ['المستخدم أولاً', 'تفاصيل دقيقة', 'تعاون مفتوح', 'إبداع منضبط', 'تسليم في الوقت'],
    perks: ['راتب USD', 'Figma Pro مدفوع', 'ساعات مرنة', 'مشاريع متنوعة', 'مراجعات تصميم أسبوعية', 'portfolio support'],
    culture: ['تصميم collaborative', 'critique sessions', 'مكتبة تصميم مشتركة', 'زيارات فريق سنوية', 'عمل هجين اختياري'],
    techStack: ['Figma', 'Framer', 'Protopie', 'Design Systems', 'Maze', 'Notion'],
  },
  {
    id: 4, name: 'DataSyria', sector: 'تحليل البيانات', logo: '📊', jobs: 4, verified: true, rating: '4.4', remoteFriendly: true, location: 'عن بُعد',
    founded: '2021', teamSize: '10–18', website: 'datasyria.io', timezone: 'UTC+2',
    paymentMethods: ['Wise', 'PayPal'],
    about: 'DataSyria فريق تحليلات بيانات سوري يخدم القطاع التجاري والتقني. نبني لوحات تحكم، نماذج تنبؤية، وتقارير تساعد الشركات على قرارات مبنية على البيانات. نعمل بالكامل عن بُعد مع عملاء في الخليج وأوروبا.',
    mission: 'تحويل البيانات إلى قرارات — للسوق السوري والعربي.',
    values: ['دقة البيانات', 'وضوح التقارير', 'خصوصية المستخدم', 'تأثير ملموس'],
    perks: ['USD', 'أدوات BI مدفوعة', 'تدريب SQL/Python', 'مشاريع متنوعة', 'ساعات مرنة'],
    culture: ['data-driven decisions', 'توثيق كل تحليل', 'pair analysis', 'اجتماعات قصيرة'],
    techStack: ['Python', 'SQL', 'dbt', 'Metabase', 'BigQuery', 'Pandas'],
  },
  {
    id: 5, name: 'Mobile Aleppo', sector: 'تطبيقات الجوال', logo: '📱', jobs: 6, verified: true, rating: '4.7', remoteFriendly: true, location: 'حلب',
    founded: '2017', teamSize: '20–30', website: 'mobilealeppo.com', timezone: 'UTC+2',
    paymentMethods: ['Wise', 'PayPal'],
    about: 'Mobile Aleppo متخصصون في تطبيقات Flutter للسوق العربي — من التجارة الإلكترونية إلى التوصيل والخدمات. فريقنا من حلب ودمشق يعمل مع شركات خليجية وأوروبية على منتجات mobile-first.',
    mission: 'تطبيقات جوال عربية بجودة عالمية.',
    values: ['أداء عالي', 'UX محلي', 'كود نظيف', 'تسليم سريع'],
    perks: ['USD', 'أجهزة اختبار', 'مؤتمرات Flutter', 'مكافآت إطلاق', 'ساعات مرنة'],
    culture: ['sprint أسبوعي', 'demo يوم الجمعة', 'كود ريفيو إلزامي', 'عمل هجين'],
    techStack: ['Flutter', 'Dart', 'Firebase', 'REST APIs', 'CI/CD', 'Figma'],
  },
  {
    id: 6, name: 'TechLatakia', sector: 'Backend & APIs', logo: '⚙️', jobs: 4, verified: true, rating: '4.3', remoteFriendly: true, location: 'اللاذقية',
    founded: '2019', teamSize: '12–20', website: 'techlatakia.dev', timezone: 'UTC+2',
    paymentMethods: ['Wise', 'PayPal'],
    about: 'TechLatakia فريق backend سوري يبني APIs و microservices للمنتجات الرقمية. نتخصص في Node.js و Go وقواعد بيانات عالية الأداء. مقرنا اللاذقية لكن الفريق موزع بالكامل.',
    mission: 'بنية تحتية backend موثوقة — سريعة وآمنة وقابلة للتوسع.',
    values: ['موثوقية', 'أمان', 'بساطة المعمارية', 'توثيق API'],
    perks: ['USD', 'ساعات مرنة', 'تدريب تقني', 'معدات عمل', 'إجازة مدفوعة'],
    culture: ['API-first', 'اختبارات تلقائية', 'on-call rotation عادل', 'async communication'],
    techStack: ['Node.js', 'Go', 'PostgreSQL', 'Redis', 'gRPC', 'Docker'],
  },
  {
    id: 7, name: 'RemoteMENA', sector: 'Product & Strategy', logo: '📋', jobs: 2, verified: true, rating: '4.5', remoteFriendly: true, location: 'MENA',
    founded: '2022', teamSize: '8–15', website: 'remotemena.co', timezone: 'UTC+2',
    paymentMethods: ['Wise', 'PayPal'],
    about: 'RemoteMENA استشارات منتج واستراتيجية للشركات التقنية في المنطقة. نساعد الفرق على بناء roadmaps، تحليل السوق، وإطلاق منتجات ناجحة. فريقنا موزع بين سوريا والأردن ومصر.',
    mission: 'منتجات تقنية تفهم السوق العربي — وتنمو باستدامة.',
    values: ['فهم السوق', 'قرارات مبنية على بيانات', 'شراكة طويلة', 'شفافية'],
    perks: ['USD', 'مشاريع متنوعة', 'شبكة واسعة', 'ساعات مرنة', 'تعلم مستمر'],
    culture: ['workshops شهرية', 'تقارير مفتوحة', 'تعاون مع founders', 'مرونة عالية'],
    techStack: ['Notion', 'Mixpanel', 'Figma', 'Jira', 'Miro', 'SQL'],
  },
  {
    id: 8, name: 'GlobalTech Hiring', sector: 'توظيف عالمي', logo: '🌍', jobs: 12, verified: true, rating: '4.9', remoteFriendly: true, location: 'عالمي',
    founded: '2016', teamSize: '40–60', website: 'globaltech.hiring', timezone: 'UTC±0',
    paymentMethods: ['Wise', 'PayPal', 'Bank Transfer', 'Deel'],
    about: 'GlobalTech Hiring جسر بين المواهب السورية والشركات الأمريكية والأوروبية. نحن لسنا مجرد منصة توظيف — نرافق المرشح من المقابلة إلى العقد ونتابع الرضا الوظيفي. أكثر من 200 مطور سوري انضموا لشركات عالمية عبرنا.',
    mission: 'كل موهبة سورية تستحق فرصة عالمية — بكرامة وعدالة.',
    values: ['عدالة', 'شفافية', 'متابعة مستمرة', 'احترام المرشح', 'نجاح طويل الأمد'],
    perks: ['عقود عالمية', 'دعم قانوني', 'تدريب مقابلات', 'مراجعة عقود', 'مجتمع alumni'],
    culture: ['دعم شخصي لكل مرشح', 'مجموعات Telegram', 'events افتراضية', 'feedback مستمر'],
    techStack: ['ATS', 'LinkedIn', 'Slack', 'Notion', 'Calendly', 'Greenhouse'],
  },
  {
    id: 9, name: 'QualityFirst Remote', sector: 'QA & Testing', logo: '🔍', jobs: 3, verified: false, rating: '4.2', remoteFriendly: true, location: 'عن بُعد',
    founded: '2023', teamSize: '8–12', website: 'qualityfirst.dev', timezone: 'UTC+2',
    paymentMethods: ['Wise', 'PayPal'],
    about: 'QualityFirst Remote فريق QA سوري يختبر منتجات SaaS قبل الإطلاق. نغطي اختبار يدوي، أتمتة Playwright، واختبار أداء. نعمل مع شركات ناشئة تحتاج جودة بدون تكلفة فريق داخلي كامل.',
    mission: 'لا إطلاق بدون جودة — حتى للمنتجات السريعة.',
    values: ['دقة', 'تغطية شاملة', 'تواصل واضح', 'تسليم سريع'],
    perks: ['USD', 'أدوات اختبار', 'تدريب Playwright', 'ساعات مرنة', 'مشاريع متنوعة'],
    culture: ['bug reports مفصلة', 'تعاون مع devs', 'اختبار exploratory', 'async'],
    techStack: ['Playwright', 'Cypress', 'Jest', 'Postman', 'Jira', 'TestRail'],
  },
  {
    id: 10, name: 'CodeDamascus', sector: 'تعليم تقني', logo: '📚', jobs: 2, verified: true, rating: '4.6', remoteFriendly: true, location: 'دمشق',
    founded: '2018', teamSize: '15–22', website: 'codedamascus.org', timezone: 'UTC+2',
    paymentMethods: ['PayPal', 'Wise'],
    about: 'CodeDamascus أكاديمية برمجة وتدريب تقني من دمشق. نُعدّ المطورين السوريين لسوق العمل العالمي عبر bootcamps ودورات متقدمة. جزء من خريجينا يعملون الآن عن بُعد مع شركات أوروبية — ونفتخر بذلك.',
    mission: 'تعليم تقني عملي يفتح أبواب العمل العالمي.',
    values: ['تعليم عملي', 'مجتمع داعم', 'فرص حقيقية', 'جودة المحتوى'],
    perks: ['USD', 'تدريس مرن', 'منصة تعليم', 'شبكة خريجين', 'مشاريع حقيقية'],
    culture: ['تعلم بالممارسة', 'mentorship', 'مجتمع Discord', 'demo days'],
    techStack: ['JavaScript', 'React', 'Node.js', 'Python', 'Git', 'VS Code'],
  },
];

const TALENTS = [
  {
    id: 1, name: 'أحمد الخطيب', title: 'مطور Full Stack', city: 'دمشق', avatar: 'أ',
    bio: 'مطور full stack بخبرة 5 سنوات في React و Node.js. أعمل عن بُعد مع شركات أوروبية وأبني منتجات SaaS.',
    skills: ['React', 'TypeScript', 'Node.js', 'PostgreSQL', 'Docker'],
    remote: true, availability: 'متاح فوراً', rateUSD: '20–30/ساعة',
    experience: [{ role: 'Senior Developer', company: 'Remote EU', years: '2021–الآن' }, { role: 'Full Stack Dev', company: 'SyriaDev', years: '2019–2021' }],
    projects: [
      { id: 1, title: 'منصة إدارة المشاريع', desc: 'SaaS لإدارة فرق العمل عن بُعد', image: '📊', demoUrl: '#', githubUrl: '#', tags: ['React', 'Node.js'] },
      { id: 2, title: 'متجر إلكتروني', desc: 'E-commerce كامل مع لوحة تحكم', image: '🛒', demoUrl: '#', githubUrl: '#', tags: ['Next.js', 'Stripe'] },
    ],
    links: { github: '#', linkedin: '#', portfolio: '#' },
    verified: true, featured: true
  },
  {
    id: 2, name: 'سارة النجار', title: 'مصممة UI/UX', city: 'حلب', avatar: 'س',
    bio: 'مصممة واجهات بخبرة 4 سنوات. متخصصة في Figma وتجربة المستخدم للمنتجات العربية.',
    skills: ['Figma', 'UI Design', 'UX Research', 'Prototyping', 'Design Systems'],
    remote: true, availability: 'متاح خلال أسبوع', rateUSD: '15–25/ساعة',
    experience: [{ role: 'Lead Designer', company: 'Pixel Damascus', years: '2022–الآن' }],
    projects: [
      { id: 1, title: 'تطبيق توصيل', desc: 'UI/UX لتطبيق توصيل محلي', image: '🚗', demoUrl: '#', githubUrl: '', tags: ['Figma', 'Mobile'] },
      { id: 2, title: 'Dashboard تحليلات', desc: 'لوحة تحكم SaaS', image: '📈', demoUrl: '#', githubUrl: '', tags: ['Dashboard', 'B2B'] },
    ],
    links: { github: '', linkedin: '#', portfolio: '#' },
    verified: true, featured: true
  },
  {
    id: 3, name: 'محمد العيسى', title: 'مهندس DevOps', city: 'اللاذقية', avatar: 'م',
    bio: 'DevOps engineer — AWS, Kubernetes, CI/CD. أؤتمت البنية لفرق موزعة.',
    skills: ['AWS', 'Docker', 'Kubernetes', 'Terraform', 'GitHub Actions'],
    remote: true, availability: 'متاح فوراً', rateUSD: '25–40/ساعة',
    experience: [{ role: 'DevOps Engineer', company: 'CloudBridge EU', years: '2020–الآن' }],
    projects: [
      { id: 1, title: 'CI/CD Pipeline', desc: 'أتمتة نشر microservices', image: '⚙️', demoUrl: '#', githubUrl: '#', tags: ['K8s', 'AWS'] },
    ],
    links: { github: '#', linkedin: '#', portfolio: '' },
    verified: true, featured: false
  },
  {
    id: 4, name: 'ليلى حمود', title: 'مطورة Flutter', city: 'دمشق', avatar: 'ل',
    bio: 'مطورة تطبيقات جوال Flutter/Dart. نشرت 8 تطبيقات على المتاجر.',
    skills: ['Flutter', 'Dart', 'Firebase', 'Bloc', 'REST APIs'],
    remote: true, availability: 'مشغولة — متاحة بعد شهر', rateUSD: '18–28/ساعة',
    experience: [{ role: 'Mobile Developer', company: 'Mobile Aleppo', years: '2021–الآن' }],
    projects: [
      { id: 1, title: 'تطبيق تعليمي', desc: 'تطبيق تعليم أطفال بالعربية', image: '📱', demoUrl: '#', githubUrl: '#', tags: ['Flutter'] },
      { id: 2, title: 'تطبيق صحة', desc: 'حجز مواعيد طبية', image: '🏥', demoUrl: '#', githubUrl: '#', tags: ['Flutter', 'Firebase'] },
    ],
    links: { github: '#', linkedin: '#', portfolio: '#' },
    verified: false, featured: true
  },
  {
    id: 5, name: 'كريم الشامي', title: 'Backend Developer', city: 'حلب', avatar: 'ك',
    bio: 'Node.js و PostgreSQL — APIs و microservices للمنتجات الرقمية.',
    skills: ['Node.js', 'PostgreSQL', 'Redis', 'GraphQL', 'Microservices'],
    remote: true, availability: 'متاح فوراً', rateUSD: '22–32/ساعة',
    experience: [{ role: 'Backend Dev', company: 'TechLatakia', years: '2019–الآن' }],
    projects: [
      { id: 1, title: 'API Gateway', desc: 'Gateway لـ 12 microservice', image: '🔗', demoUrl: '#', githubUrl: '#', tags: ['Node.js'] },
    ],
    links: { github: '#', linkedin: '#', portfolio: '' },
    verified: true, featured: false
  },
  {
    id: 6, name: 'نور الدين', title: 'محللة بيانات', city: 'دمشق', avatar: 'ن',
    bio: 'Python, SQL, Power BI — تحليلات للتجارة الإلكترونية والقطاع المالي.',
    skills: ['Python', 'SQL', 'Power BI', 'Pandas', 'Statistics'],
    remote: true, availability: 'متاح فوراً', rateUSD: '18–28/ساعة',
    experience: [{ role: 'Data Analyst', company: 'DataSyria', years: '2020–الآن' }],
    projects: [
      { id: 1, title: 'Dashboard مبيعات', desc: 'تحليلات real-time', image: '📊', demoUrl: '#', githubUrl: '#', tags: ['Power BI'] },
    ],
    links: { github: '#', linkedin: '#', portfolio: '' },
    verified: true, featured: false
  },
  {
    id: 7, name: 'ياسين جخضر', title: 'QA Engineer', city: 'عن بُعد', avatar: 'ي',
    bio: 'Manual و automated testing — Cypress, Selenium, API testing.',
    skills: ['Cypress', 'Selenium', 'Jest', 'Postman', 'Test Planning'],
    remote: true, availability: 'متاح خلال 3 أيام', rateUSD: '12–20/ساعة',
    experience: [{ role: 'QA Engineer', company: 'QualityFirst', years: '2021–الآن' }],
    projects: [
      { id: 1, title: 'Test Automation Suite', desc: 'E2E tests لـ SaaS', image: '🔍', demoUrl: '#', githubUrl: '#', tags: ['Cypress'] },
    ],
    links: { github: '#', linkedin: '#', portfolio: '' },
    verified: false, featured: false
  },
  {
    id: 8, name: 'رامي قاسم', title: 'Product Manager', city: 'دمشق', avatar: 'ر',
    bio: 'PM بخبرة تقنية — Agile, roadmap, analytics للمنتجات العربية.',
    skills: ['Product Strategy', 'Agile', 'Jira', 'Analytics', 'User Research'],
    remote: true, availability: 'متاح فوراً', rateUSD: '25–35/ساعة',
    experience: [{ role: 'Product Manager', company: 'RemoteMENA', years: '2018–الآن' }],
    projects: [
      { id: 1, title: 'إطلاق منتج SaaS', desc: 'من الفكرة إلى 10k مستخدم', image: '🚀', demoUrl: '#', githubUrl: '', tags: ['Product'] },
    ],
    links: { github: '', linkedin: '#', portfolio: '#' },
    verified: true, featured: true
  },
];

// ========================
// UTILS
// ========================
function getQueryParam(key) {
  const fromQuery = new URLSearchParams(window.location.search).get(key);
  if (fromQuery) return fromQuery;
  if (key === 'id') {
    if (document.body.dataset.resourceId) return document.body.dataset.resourceId;
    const match = window.location.pathname.match(/\/(?:jobs|talents|companies)\/(\d+)/);
    if (match) return match[1];
  }
  return null;
}

function resolveUrl(url) {
  if (!url || url === '#') return null;
  if (url.startsWith('http') || (url.startsWith('/') && !url.includes('.html'))) return url;

  const routes = window.FRONTEND_ROUTES || {};
  const [path, query] = url.split('?');
  const id = query ? new URLSearchParams(query).get('id') : null;

  const map = {
    'index.html': routes.home,
    'jobs.html': routes.jobs ? routes.jobs + (query ? `?${query}` : '') : null,
    'job-detail.html': id && routes.jobs ? `${routes.jobs}/${id}` : routes.jobs,
    'talents.html': routes.talents ? routes.talents + (query ? `?${query}` : '') : null,
    'talent-profile.html': id && routes.talents ? `${routes.talents}/${id}` : routes.talents,
    'companies.html': routes.companies,
    'company-profile.html': id && routes.companies ? `${routes.companies}/${id}` : routes.companies,
    'post-job.html': routes.postJob,
    'edit-profile.html': routes.editProfile,
    'dashboard-seeker.html': routes.dashboardSeeker,
    'dashboard-company.html': routes.dashboardCompany,
    'profile.html': routes.profile,
  };

  return map[path] || url;
}

function normalizeSearch(text) {
  return (text || '').trim().toLowerCase();
}

const CITY_ALIASES = {
  'دمشق': ['دمشق', 'damascus'],
  'حلب': ['حلب', 'aleppo'],
  'اللاذقية': ['اللاذقية', 'latakia'],
  'عن بُعد': ['عن بُعد', 'remote', 'بُعد', 'عن بعد'],
  'أوروبا': ['أوروبا', 'europe', 'eu'],
  'عالمي': ['عالمي', 'global', 'عالم'],
};

function matchesCity(location, cityQuery) {
  if (!cityQuery) return true;
  const q = normalizeSearch(cityQuery);
  const loc = normalizeSearch(location);
  if (loc.includes(q)) return true;
  for (const [city, aliases] of Object.entries(CITY_ALIASES)) {
    const queryMatches = aliases.some(a => q.includes(a) || a.includes(q)) || q.includes(city);
    if (queryMatches && (loc.includes(city) || aliases.some(a => loc.includes(a)))) return true;
  }
  return false;
}

function matchesSpecialty(item, query, fields) {
  if (!query) return true;
  const q = normalizeSearch(query);
  const haystack = fields.map(f => (typeof f === 'function' ? f(item) : item[f]) || '').join(' ').toLowerCase();
  return haystack.includes(q);
}

function heroSearch() {
  const specialty = document.getElementById('hero-search-q')?.value.trim() || '';
  const city = document.getElementById('hero-search-city')?.value.trim() || '';
  const params = new URLSearchParams();
  if (specialty) params.set('q', specialty);
  if (city) params.set('city', city);
  const qs = params.toString();
  goTo(`jobs.html${qs ? `?${qs}` : ''}`);
}

function initHeroSearch() {
  document.getElementById('hero-search-q')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); heroSearch(); }
  });
  document.getElementById('hero-search-city')?.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); heroSearch(); }
  });
}

function initJobsPageSearch() {
  const q = getQueryParam('q');
  const city = getQueryParam('city');
  const searchEl = document.getElementById('jobs-search');
  const cityEl = document.getElementById('jobs-city');
  if (q && searchEl) searchEl.value = decodeURIComponent(q);
  if (city && cityEl) cityEl.value = decodeURIComponent(city);
  ['jobs-search', 'jobs-city'].forEach(id => {
    document.getElementById(id)?.addEventListener('keydown', e => {
      if (e.key === 'Enter') { e.preventDefault(); filterJobs(); }
    });
  });
  filterJobs(false);
}

function initTalentsPageSearch() {
  ['talents-search', 'talents-city'].forEach(id => {
    document.getElementById(id)?.addEventListener('keydown', e => {
      if (e.key === 'Enter') { e.preventDefault(); filterTalents(); }
    });
  });
}

function getSelectedFilterCities(selector) {
  return [...document.querySelectorAll(selector)]
    .filter(cb => cb.checked)
    .map(cb => cb.dataset.city);
}

// ========================
// AUTOCOMPLETE
// ========================
const AC_DATA = {
  specialty: [
    { v: 'React', icon: '⚛️', desc: 'مكتبة واجهات تفاعلية' },
    { v: 'Frontend', icon: '🖥️', desc: 'تطوير الواجهات الأمامية' },
    { v: 'Backend', icon: '⚙️', desc: 'خوادم و APIs' },
    { v: 'DevOps', icon: '☁️', desc: 'بنية تحتية ونشر' },
    { v: 'Flutter', icon: '📱', desc: 'تطبيقات جوال' },
    { v: 'UI/UX', icon: '🎨', desc: 'تصميم تجربة المستخدم' },
    { v: 'Data', icon: '📊', desc: 'تحليل البيانات' },
    { v: 'QA', icon: '🔍', desc: 'اختبار الجودة' },
    { v: 'Node.js', icon: '🟢', desc: 'JavaScript للخادم' },
    { v: 'Product', icon: '📋', desc: 'إدارة المنتجات' },
  ],
  city: [
    { v: 'عن بُعد', icon: '🌐', desc: 'عمل من أي مكان' },
    { v: 'دمشق', icon: '🏛️', desc: 'العاصمة' },
    { v: 'حلب', icon: '🕌', desc: 'المدينة الاقتصادية' },
    { v: 'اللاذقية', icon: '🌊', desc: 'الساحل السوري' },
    { v: 'أوروبا', icon: '🇪🇺', desc: 'شركات أوروبية' },
    { v: 'عالمي', icon: '🌍', desc: 'شركات دولية' },
  ],
};

let acClickBound = false;
let acActiveIndex = -1;

function initSearchAutocompletes() {
  document.querySelectorAll('.ac-wrap').forEach(wrap => {
    const input = wrap.querySelector('input[data-ac]');
    const menu = wrap.querySelector('.ac-menu');
    const chevron = wrap.querySelector('.ac-chevron');
    if (!input || !menu || input.dataset.acReady) return;
    input.dataset.acReady = '1';

    const type = input.dataset.ac;
    const title = type === 'specialty' ? '🎯 التخصصات التقنية' : '📍 المدن والمواقع';

    const getItems = (filter = '') => {
      const q = normalizeSearch(filter);
      return (AC_DATA[type] || []).filter(item =>
        !q || item.v.toLowerCase().includes(q) || (item.desc && item.desc.includes(q))
      );
    };

    const render = (filter = '', activeIdx = 0) => {
      const items = getItems(filter);
      acActiveIndex = items.length ? Math.min(activeIdx, items.length - 1) : -1;
      menu.innerHTML = `
        <div class="ac-menu-header">${title}</div>
        <div class="ac-menu-list">
          ${items.length ? items.map((item, i) => `
            <button type="button" class="ac-item${i === acActiveIndex ? ' active' : ''}" data-value="${item.v}">
              <span class="ac-item-icon">${item.icon}</span>
              <span class="ac-item-body">
                <span class="ac-item-label">${item.v}</span>
                <span class="ac-item-desc">${item.desc}</span>
              </span>
            </button>`).join('') : '<div class="ac-empty">لا توجد نتائج مطابقة</div>'}
        </div>`;
    };

    const open = () => {
      document.querySelectorAll('.ac-menu.open').forEach(m => {
        if (m !== menu) m.classList.remove('open');
      });
      render(input.value);
      menu.classList.add('open');
    };

    const close = () => {
      menu.classList.remove('open');
      acActiveIndex = -1;
    };

    const select = (value) => {
      input.value = value;
      close();
    };

    input.addEventListener('focus', open);
    input.addEventListener('input', () => { render(input.value); menu.classList.add('open'); });

    input.addEventListener('keydown', e => {
      const items = menu.querySelectorAll('.ac-item');
      if (!menu.classList.contains('open')) {
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') { e.preventDefault(); open(); }
        return;
      }
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        acActiveIndex = Math.min(acActiveIndex + 1, items.length - 1);
        render(input.value, acActiveIndex);
        items[acActiveIndex]?.scrollIntoView({ block: 'nearest' });
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        acActiveIndex = Math.max(acActiveIndex - 1, 0);
        render(input.value, acActiveIndex);
        items[acActiveIndex]?.scrollIntoView({ block: 'nearest' });
      } else if (e.key === 'Enter' && acActiveIndex >= 0 && items[acActiveIndex]) {
        e.preventDefault();
        select(items[acActiveIndex].dataset.value);
      } else if (e.key === 'Escape') {
        close();
      }
    });

    chevron?.addEventListener('click', e => {
      e.preventDefault();
      e.stopPropagation();
      menu.classList.contains('open') ? close() : open();
    });

    menu.addEventListener('click', e => {
      const btn = e.target.closest('.ac-item');
      if (btn) select(btn.dataset.value);
    });
  });

  if (!acClickBound) {
    document.addEventListener('click', e => {
      if (!e.target.closest('.ac-wrap')) {
        document.querySelectorAll('.ac-menu.open').forEach(m => m.classList.remove('open'));
      }
    });
    acClickBound = true;
  }
}

function goTo(url) {
  const resolved = resolveUrl(url);
  if (resolved) window.location.href = resolved;
}

function getJobsData() {
  if (Array.isArray(window.__JOBS__) && window.__JOBS__.length) {
    return window.__JOBS__;
  }
  return JOBS;
}

function getTalentsData() {
  if (Array.isArray(window.__TALENTS__) && window.__TALENTS__.length) {
    return window.__TALENTS__;
  }
  return TALENTS;
}

function getCompaniesData() {
  if (Array.isArray(window.__COMPANIES__) && window.__COMPANIES__.length) {
    return window.__COMPANIES__;
  }
  return COMPANIES;
}

function getJobById(id) {
  return getJobsData().find(j => j.id === Number(id));
}

function getTalentById(id) {
  const numId = Number(id);
  const stored = getStoredProfile();
  if (stored && (stored.id === numId || numId === 99)) return stored;
  return getTalentByIdFromData(numId);
}

function getTalentByIdFromData(id) {
  return getTalentsData().find(t => t.id === Number(id));
}

function getCompanyById(id) {
  return getCompaniesData().find(c => c.id === Number(id));
}

function getCompanyByName(name) {
  return COMPANIES.find(c => c.name === name);
}

// ========================
// LAYOUT
// ========================
const NAV_ITEMS = [
  { id: 'home', label: 'الرئيسية', href: 'index.html' },
  { id: 'jobs', label: 'الوظائف', href: 'jobs.html' },
  { id: 'talents', label: 'المواهب', href: 'talents.html' },
  { id: 'companies', label: 'الشركات', href: 'companies.html' },
  { id: 'post-job', label: 'أضف وظيفة', href: 'post-job.html' },
];

function getNavbarAuthActionsHTML() {
  const auth = window.FRONTEND_AUTH || {};
  const routes = window.FRONTEND_ROUTES || {};
  if (auth.loggedIn && auth.dashboardUrl) {
    return `<a class="btn btn-outline btn-sm" href="${auth.dashboardUrl}">لوحتي</a>`;
  }
  const loginHref = routes.login || resolveUrl('login');
  return `
      <a class="btn btn-outline btn-sm" href="${loginHref}">تسجيل الدخول</a>
      <button class="btn btn-primary btn-sm" type="button" onclick="openModal('register')">ابدأ مجاناً ✨</button>`;
}

function getNavbarHTML(activePage) {
  const isDark = localStorage.getItem(LS_THEME) === 'dark';
  const links = NAV_ITEMS.map(item =>
    `<div class="nav-link${activePage === item.id ? ' active' : ''}" onclick="goTo('${item.href}')">${item.label}</div>`
  ).join('');
  return `
<nav class="navbar">
  <div class="nav-inner">
    <div class="nav-logo" onclick="goTo('index.html')" style="cursor:pointer">${BRAND}<span>.</span></div>
    <div class="nav-links">${links}</div>
    <div class="nav-actions">
      <button class="theme-toggle" onclick="toggleTheme()" title="تبديل الوضع">${isDark ? '☀️' : '🌙'}</button>
      ${getNavbarAuthActionsHTML()}
    </div>
  </div>
</nav>`;
}

function getFooterHTML() {
  return `
<footer class="footer">
  <div class="footer-bg">
    <div class="footer-orb footer-orb-1"></div>
    <div class="footer-orb footer-orb-2"></div>
    <div class="footer-orb footer-orb-3"></div>
  </div>
  <div class="footer-wrap">
    <div class="footer-cta">
      <div class="footer-cta-content">
        <span class="footer-cta-badge">🌐 Remote-first</span>
        <h3>جاهز تعمل عن بُعد مع العالم؟</h3>
        <p>انضم لـ <strong>500+</strong> تقني سوري — اعرض مهاراتك أو وظّف أفضل المواهب.</p>
      </div>
      <div class="footer-cta-actions">
        <button class="btn btn-primary" onclick="goTo('edit-profile.html')">👤 أنشئ ملفك</button>
        <button class="btn footer-cta-outline" onclick="goTo('post-job.html')">🏢 انشر وظيفة</button>
      </div>
    </div>

    <div class="footer-inner">
      <div class="footer-brand">
        <div class="footer-logo">${BRAND}<span>.</span></div>
        <p class="footer-tagline">منصة المواهب التقنية السورية — وظائف remote، معرض أعمال، وتواصل مباشر مع الشركات.</p>
        <div class="footer-mini-stats">
          <div class="footer-stat"><span class="footer-stat-num">500+</span><span class="footer-stat-label">تقني</span></div>
          <div class="footer-stat"><span class="footer-stat-num">120+</span><span class="footer-stat-label">وظيفة</span></div>
          <div class="footer-stat"><span class="footer-stat-num">80+</span><span class="footer-stat-label">شركة</span></div>
        </div>
        <div class="footer-social">
          <a class="social-icon" href="#" title="X" aria-label="X">𝕏</a>
          <a class="social-icon" href="#" title="LinkedIn" aria-label="LinkedIn">in</a>
          <a class="social-icon" href="#" title="GitHub" aria-label="GitHub">🐙</a>
          <a class="social-icon" href="#" title="YouTube" aria-label="YouTube">▶</a>
        </div>
      </div>

      <div class="footer-col">
        <h4><span class="footer-col-dot"></span> للتقنيين</h4>
        <ul>
          <li onclick="goTo('jobs.html')"><span>وظائف عن بُعد</span><span class="footer-link-arrow">←</span></li>
          <li onclick="goTo('talents.html')"><span>دليل المواهب</span><span class="footer-link-arrow">←</span></li>
          <li onclick="goTo('edit-profile.html')"><span>إنشاء ملف</span><span class="footer-link-arrow">←</span></li>
          <li onclick="goTo('dashboard-seeker.html')"><span>لوحة التحكم</span><span class="footer-link-arrow">←</span></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4><span class="footer-col-dot"></span> للشركات</h4>
        <ul>
          <li onclick="goTo('post-job.html')"><span>أضف وظيفة</span><span class="footer-link-arrow">←</span></li>
          <li onclick="goTo('dashboard-company.html')"><span>لوحة الشركة</span><span class="footer-link-arrow">←</span></li>
          <li onclick="goTo('talents.html')"><span>قاعدة المواهب</span><span class="footer-link-arrow">←</span></li>
          <li onclick="goTo('companies.html')"><span>الشركات</span><span class="footer-link-arrow">←</span></li>
        </ul>
      </div>

      <div class="footer-col footer-newsletter">
        <h4><span class="footer-col-dot"></span> ابقَ على اطلاع</h4>
        <p class="footer-newsletter-text">وظائف remote جديدة ونصائح تقنية — أسبوعياً.</p>
        <div class="footer-newsletter-form">
          <input type="email" class="footer-email-input" placeholder="بريدك الإلكتروني">
          <button class="footer-subscribe-btn" onclick="showToast('✅ تم الاشتراك بنجاح!', 'success')">اشترك</button>
        </div>
        <div class="footer-badges">
          <span class="footer-badge">💵 USD</span>
          <span class="footer-badge">🌐 Remote</span>
          <span class="footer-badge">🇸🇾 Syria</span>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2026 ${BRAND}. جميع الحقوق محفوظة.</p>
      <div class="footer-bottom-links">
        <span>سياسة الخصوصية</span>
        <span class="footer-bottom-sep">·</span>
        <span>الشروط والأحكام</span>
        <span class="footer-bottom-sep">·</span>
        <span>اتصل بنا</span>
      </div>
      <p class="footer-made">صُنع بـ <span class="footer-heart">❤️</span> للمواهب السورية</p>
    </div>
  </div>
</footer>`;
}

function getAuthModalHTML() {
  return `
<div class="modal-overlay" id="auth-modal" onclick="closeModalOutside(event)">
  <div class="modal">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <div class="modal-tabs">
      <button class="tab-btn active" onclick="switchTab('login')">تسجيل الدخول</button>
      <button class="tab-btn" onclick="switchTab('register')">إنشاء حساب</button>
    </div>
    <div id="login-form">
      <h2>مرحباً بعودتك</h2>
      <p>سجّل دخولك للوصول لملفك ووظائفك</p>
      <div class="form-group">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" class="form-input" placeholder="your@email.com">
      </div>
      <div class="form-group">
        <label class="form-label">كلمة المرور</label>
        <input type="password" class="form-input" placeholder="••••••••">
      </div>
      <button class="btn btn-primary btn-full btn-lg" onclick="loginSuccess()">تسجيل الدخول</button>
      <div class="divider">أو</div>
      <div class="social-btns">
        <button class="social-btn">🇬 Google</button>
        <button class="social-btn">🐙 GitHub</button>
      </div>
      <div class="auth-switch">ليس لديك حساب؟ <a onclick="switchTab('register')">إنشاء حساب جديد</a></div>
    </div>
    <div id="register-form" style="display:none;">
      <h2>انضم إلى ${BRAND}</h2>
      <p>أنشئ ملفك واعرض أعمالك للعالم</p>
      <div class="role-select">
        <button class="role-btn active" id="role-seeker" onclick="selectRole('seeker')">
          <span class="role-icon">👤</span>
          <span class="role-name">تقني / مطور</span>
        </button>
        <button class="role-btn" id="role-company" onclick="selectRole('company')">
          <span class="role-icon">🏢</span>
          <span class="role-name">شركة</span>
        </button>
      </div>
      <div class="form-grid" style="gap:14px;">
        <div class="form-group">
          <label class="form-label">الاسم</label>
          <input type="text" class="form-input" id="reg-name" placeholder="أحمد الخطيب">
        </div>
        <div class="form-group">
          <label class="form-label">التخصص</label>
          <input type="text" class="form-input" id="reg-title" placeholder="مطور React">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">البريد الإلكتروني</label>
        <input type="email" class="form-input" placeholder="your@email.com">
      </div>
      <div class="form-group">
        <label class="form-label">كلمة المرور</label>
        <input type="password" class="form-input" placeholder="8 أحرف على الأقل">
      </div>
      <button class="btn btn-primary btn-full btn-lg" onclick="registerSuccess()">إنشاء الحساب مجاناً 🎉</button>
      <div class="auth-switch">لديك حساب؟ <a onclick="switchTab('login')">تسجيل الدخول</a></div>
    </div>
  </div>
</div>`;
}

function injectLayout(activePage) {
  const nav = document.getElementById('site-navbar');
  const footer = document.getElementById('site-footer');
  const modal = document.getElementById('site-modal');

  if (document.querySelector('.navbar')) {
    initNavbarScroll();
    return;
  }

  if (nav) nav.innerHTML = getNavbarHTML(activePage);
  if (footer) footer.innerHTML = getFooterHTML();
  if (modal) modal.innerHTML = getAuthModalHTML();
  initNavbarScroll();
}

// ========================
// NAVBAR SCROLL & MOBILE
// ========================
let navbarScrollBound = false;
let navbarMobileBound = false;

function openNavDrawer() {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;
  navbar.classList.add('nav-open');
  document.body.classList.add('nav-open');
  const burger = navbar.querySelector('.nav-burger');
  const drawer = navbar.querySelector('.nav-drawer');
  burger?.setAttribute('aria-expanded', 'true');
  drawer?.setAttribute('aria-hidden', 'false');
}

function closeNavDrawer() {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;
  navbar.classList.remove('nav-open');
  document.body.classList.remove('nav-open');
  const burger = navbar.querySelector('.nav-burger');
  const drawer = navbar.querySelector('.nav-drawer');
  burger?.setAttribute('aria-expanded', 'false');
  drawer?.setAttribute('aria-hidden', 'true');
}

function toggleNavDrawer() {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;
  if (navbar.classList.contains('nav-open')) closeNavDrawer();
  else openNavDrawer();
}

function initNavbarMobile() {
  const navbar = document.querySelector('.navbar');
  if (!navbar || navbarMobileBound) return;
  navbarMobileBound = true;

  const burger = navbar.querySelector('.nav-burger');
  const overlay = navbar.querySelector('.nav-overlay');
  const closeBtn = navbar.querySelector('.nav-drawer-close');

  burger?.addEventListener('click', toggleNavDrawer);
  overlay?.addEventListener('click', closeNavDrawer);
  closeBtn?.addEventListener('click', closeNavDrawer);

  navbar.querySelectorAll('.nav-drawer-link, .nav-brand--drawer').forEach(link => {
    link.addEventListener('click', closeNavDrawer);
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeNavDrawer();
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 992) closeNavDrawer();
  });
}

function initNavbarScroll() {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;

  const updateNavbar = () => {
    const scrollY = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = docHeight > 0 ? (scrollY / docHeight) * 100 : 0;

    navbar.classList.toggle('navbar-scrolled', scrollY > 24);
    navbar.style.setProperty('--scroll-progress', `${progress}%`);
    document.documentElement.style.setProperty('--nav-height', scrollY > 24 ? '62px' : '72px');
  };

  if (!navbarScrollBound) {
    window.addEventListener('scroll', updateNavbar, { passive: true });
    navbarScrollBound = true;
  }
  updateNavbar();
  initNavbarMobile();
}

// Legacy navigate — redirect to pages
function navigate(pageId) {
  const map = {
    home: 'index.html', jobs: 'jobs.html', companies: 'companies.html',
    talents: 'talents.html', 'post-job': 'post-job.html',
    'dashboard-company': 'dashboard-company.html', profile: 'profile.html',
    'job-detail': 'job-detail.html', 'company-profile': 'company-profile.html',
    'talent-profile': 'talent-profile.html', 'edit-profile': 'edit-profile.html',
    'dashboard-seeker': 'dashboard-seeker.html',
  };
  if (map[pageId]) goTo(map[pageId]);
}

// ========================
// RENDER JOBS
// ========================
function jobShowUrl(job) {
  const base = (window.FRONTEND_ROUTES && window.FRONTEND_ROUTES.jobs) || '/jobs';
  return `${base.replace(/\/$/, '')}/${job.slug || job.id}`;
}

function companyShowUrl(company) {
  const base = (window.FRONTEND_ROUTES && window.FRONTEND_ROUTES.companies) || '/companies';
  return `${base.replace(/\/$/, '')}/${company.slug || company.id}`;
}

function createJobCard(job, onclick) {
  const click = onclick || `goTo('${jobShowUrl(job)}')`;
  const tagHTML = job.tagLabels.map(t => `<span class="tag tag-${t.c}">${t.t}</span>`).join('');
  const saved = isJobSaved(job.id);
  return `
    <div class="job-card" onclick="${click}">
      <div class="job-card-top">
        <div class="company-logo">${job.logo}</div>
        <div style="flex:1; margin: 0 12px;">
          <div class="job-title">${job.title} ${job.isNew ? '<span class="badge-new">جديد</span>' : ''}</div>
          <div class="job-company">${job.company} · ${job.location}</div>
        </div>
        <button class="job-save${saved ? ' saved' : ''}" onclick="event.stopPropagation(); toggleSaveJob(${job.id}, this)">🔖</button>
      </div>
      <div class="job-tags">${tagHTML}${job.syriaFriendly ? '<span class="remote-badge">🇸🇾 Syria-friendly</span>' : ''}</div>
      <div class="job-meta">
        <div class="job-salary">${job.salary} ${job.currency || '$'}/شهر</div>
        <div class="job-date">${job.date}</div>
      </div>
    </div>`;
}

function renderJobs(containerId, jobs, count) {
  const el = document.getElementById(containerId);
  if (!el) return;
  const list = jobs || getJobsData();
  el.innerHTML = list.slice(0, count || list.length).map(j => createJobCard(j)).join('');
}

function createCompanyCard(company) {
  const tagHTML = [
    company.remoteFriendly ? '<span class="tag tag-teal">Remote-friendly 🌐</span>' : '',
    `<span class="tag tag-blue">${company.sector}</span>`,
    company.verified ? '<span class="tag tag-gold">موثّق ✓</span>' : '',
  ].join('');
  return `
    <div class="job-card" onclick="goTo('${companyShowUrl(company)}')">
      <div class="job-card-top">
        <div class="company-logo">${company.logo}</div>
        <div style="flex:1; margin: 0 12px;">
          <div class="job-title">${company.name}</div>
          <div class="job-company">${company.sector} · ${company.location}</div>
        </div>
        <div class="job-rating" title="التقييم">⭐ ${company.rating}</div>
      </div>
      <div class="job-tags">${tagHTML}</div>
      <div class="job-meta">
        <div class="job-salary">${company.jobs} وظيفة</div>
        <div class="job-date">${company.remoteFriendly ? '🇸🇾 Syria-friendly' : ''}</div>
      </div>
    </div>`;
}

function renderCompanies(containerId, companies, count) {
  const el = document.getElementById(containerId);
  if (!el) return;
  const list = companies || getCompaniesData();
  el.innerHTML = list.slice(0, count || list.length).map(c => createCompanyCard(c)).join('');
}

// ========================
// RENDER TALENTS
// ========================
function talentShowUrl(talent) {
  const base = (window.FRONTEND_ROUTES && window.FRONTEND_ROUTES.talents) || '/talents';
  return `${base.replace(/\/$/, '')}/${talent.slug || talent.id}`;
}

function createTalentCard(talent) {
  const allSkills = talent.skills || [];
  const skills = allSkills.slice(0, 3);
  const extraSkills = Math.max(0, allSkills.length - skills.length);
  const skillsHTML = skills.map(s => `<span class="talent-card__skill">${s}</span>`).join('')
    + (extraSkills ? `<span class="talent-card__skill talent-card__skill--more">+${extraSkills}</span>` : '');

  const chips = [
    talent.city ? `<span class="talent-card__chip">${talent.city}</span>` : '',
    talent.remote ? `<span class="talent-card__chip talent-card__chip--teal">عن بُعد</span>` : '',
    (talent.openToWork || talent.hiringHeadline) ? `<span class="talent-card__chip talent-card__chip--green">يبحث عن عمل</span>` : '',
    talent.verified ? `<span class="talent-card__chip talent-card__chip--gold">موثّق</span>` : '',
  ].filter(Boolean).join('');

  const avatarInner = talent.avatarImage
    ? `<img src="${talent.avatarImage}" alt="${talent.name}" class="talent-card__avatar-img">`
    : talent.avatar;

  let rateHTML = '<span class="talent-card__rate-na">—</span>';
  if (talent.rateMin && talent.rateMax) {
    const fmt = n => Number(n).toLocaleString('en-US');
    rateHTML = `<span dir="ltr" class="tp-ltr-val">$${fmt(talent.rateMin)} – $${fmt(talent.rateMax)}</span><span class="tp-rate-unit">/ساعة</span>`;
  } else if (talent.rateUSD) {
    rateHTML = formatRateDisplay(talent.rateUSD);
  }

  const hiringHTML = talent.hiringHeadline
    ? `<p class="talent-card__hiring">يبحث عن: ${talent.hiringHeadline.length > 48 ? `${talent.hiringHeadline.slice(0, 48)}…` : talent.hiringHeadline}</p>`
    : '';

  const specialtyHTML = talent.specialtyName
    ? `<p class="talent-card__specialty">${talent.specialtyName}</p>`
    : '';

  const recommendHTML = talent.recommendationReason
    ? `<div class="talent-card__recommend">${talent.recommendationReason}</div>`
    : '';

  return `
<a href="${talentShowUrl(talent)}" class="talent-card">
  ${recommendHTML}
  <div class="talent-card__header">
    <div class="talent-card__avatar-wrap">
      <div class="talent-card__avatar-ring"></div>
      <div class="talent-card__avatar">${avatarInner}</div>
    </div>
    <div class="talent-card__identity">
      <div class="talent-card__name-row">
        <h3 class="talent-card__name">${talent.name}</h3>
        ${talent.featured ? '<span class="talent-card__featured">مميز</span>' : ''}
      </div>
      <p class="talent-card__title">${talent.title || ''}</p>
      ${specialtyHTML}
    </div>
  </div>
  <div class="talent-card__badges">${chips}</div>
  ${skills.length ? `<div class="talent-card__skills">${skillsHTML}</div>` : ''}
  ${hiringHTML}
  <div class="talent-card__footer">
    <div class="talent-card__rate">${rateHTML}</div>
    ${talent.availability ? `<div class="talent-card__availability">${talent.availability}</div>` : ''}
  </div>
  <span class="talent-card__cta">عرض الملف ←</span>
</a>`;
}

function renderTalents(containerId, talents, count) {
  const el = document.getElementById(containerId);
  if (!el) return;
  const list = talents || getTalentsData();
  el.innerHTML = list.slice(0, count || list.length).map(t => createTalentCard(t)).join('');
}

function renderProjectCards(projects, containerId, lux = false) {
  const el = document.getElementById(containerId);
  if (!el || !projects) return;
  el.innerHTML = projects.map(p => `
    <article class="project-card${lux ? ' project-card--lux' : ''}">
      <div class="project-image${lux ? ' project-image--lux' : ''}">${p.image || '💼'}</div>
      <div class="project-body">
        <h4 class="project-title">${p.title}</h4>
        <p class="project-desc">${p.desc}</p>
        <div class="skill-tags project-tags">${(p.tags || []).map(t => `<span class="skill-tag">${t}</span>`).join('')}</div>
        <div class="project-links">
          ${p.demoUrl ? `<a href="${p.demoUrl}" class="btn btn-primary btn-sm" onclick="event.stopPropagation()">🔗 معاينة</a>` : ''}
          ${p.githubUrl ? `<a href="${p.githubUrl}" class="btn btn-outline btn-sm" onclick="event.stopPropagation()">🐙 GitHub</a>` : ''}
        </div>
      </div>
    </article>`).join('');
}

// ========================
// JOB DETAIL
// ========================
function formatSalaryDisplay(salary, currency = '$') {
  if (!salary) return '—';
  const m = String(salary).replace(/,/g, '').match(/(\d+)\s*[–-]\s*(\d+)/);
  if (m) {
    const fmt = n => Number(n).toLocaleString('en-US');
    return `<span dir="ltr" class="tp-ltr-val">${currency}${fmt(m[1])} – ${currency}${fmt(m[2])}</span><span class="tp-rate-unit">/شهر</span>`;
  }
  return `<span dir="ltr" class="tp-ltr-val">${currency}${salary}</span><span class="tp-rate-unit">/شهر</span>`;
}

function renderJobDetail() {
  const id = getQueryParam('id') || '1';
  const job = getJobById(id);
  if (!job) return;

  const setText = (sel, text) => { const el = document.querySelector(sel); if (el) el.textContent = text; };
  const setHTML = (sel, html) => { const el = document.querySelector(sel); if (el) el.innerHTML = html; };

  setText('#jd-title', job.title);
  setText('#jd-logo', job.logo);
  setText('#jd-company', job.company);
  setText('#jd-breadcrumb-title', job.title);
  setText('#jd-location', job.location);
  setText('#jd-type', job.type);
  setText('#sidebar-type', job.type);
  setText('#sidebar-location', job.location);
  setText('#sidebar-timezone', job.timezone || '—');

  const salaryHTML = formatSalaryDisplay(job.salary, job.currency || '$');
  setHTML('#jd-salary', salaryHTML);
  setHTML('#sidebar-salary', salaryHTML);

  const tagsEl = document.getElementById('jd-tags');
  if (tagsEl) {
    tagsEl.innerHTML = job.tagLabels.map(t => `<span class="tag tag-${t.c}">${t.t}</span>`).join('') +
      (job.isNew ? '<span class="badge-new">جديد</span>' : '') +
      (job.syriaFriendly ? '<span class="remote-badge">Syria-friendly</span>' : '');
  }

  const desc = document.getElementById('detail-description');
  if (desc) desc.textContent = job.description;

  const resp = document.getElementById('detail-responsibilities');
  if (resp) resp.innerHTML = job.responsibilities.map(r => `<li>${r}</li>`).join('');

  const req = document.getElementById('detail-requirements');
  if (req) req.innerHTML = job.requirements.map(r => `<li>${r}</li>`).join('');

  const ben = document.getElementById('detail-benefits');
  if (ben) ben.innerHTML = job.benefits.map(b => `<li>${b}</li>`).join('');

  const skillsEl = document.getElementById('detail-skills');
  if (skillsEl) skillsEl.innerHTML = job.skills.map(s => `<span class="skill-tag">${s}</span>`).join('');

  const paymentEl = document.getElementById('detail-payment');
  if (paymentEl) paymentEl.textContent = (job.paymentMethods || []).join(' · ');

  document.title = `${job.title} - ${BRAND}`;
}

// ========================
// TALENT PROFILE
// ========================
function formatRateDisplay(rate) {
  if (!rate) return '—';
  const m = String(rate).match(/(\d+)\s*[–-]\s*(\d+)/);
  if (m) {
    return `<span dir="ltr" class="tp-ltr-val">$${m[1]} – $${m[2]}</span><span class="tp-rate-unit">/ساعة</span>`;
  }
  return `<span dir="ltr" class="tp-ltr-val">${rate}</span>`;
}

function renderTalentProfile() {
  const id = getQueryParam('id') || '1';
  const talent = getTalentById(id);
  if (!talent) return;

  const setText = (sel, text) => { const el = document.querySelector(sel); if (el) el.textContent = text; };

  setText('#tp-name', talent.name);
  setText('#tp-title', talent.title);
  setText('#tp-city', talent.city);
  setText('#tp-bio', talent.bio);
  setText('#tp-availability', talent.availability);
  setText('#tp-avatar', talent.avatar);
  setText('#tp-breadcrumb-name', talent.name);

  const rateEl = document.getElementById('tp-rate');
  if (rateEl) rateEl.innerHTML = formatRateDisplay(talent.rateUSD);

  const verified = document.getElementById('tp-verified');
  if (verified) verified.hidden = !talent.verified;

  const skillsEl = document.getElementById('tp-skills');
  if (skillsEl) {
    skillsEl.innerHTML = talent.skills.map(s => `<span class="tp-skill">${s}</span>`).join('');
  }

  const expEl = document.getElementById('tp-experience');
  if (expEl) {
    expEl.innerHTML = talent.experience.map((e, i) => `
      <div class="tp-timeline-item">
        <div class="tp-timeline-dot${i === 0 ? ' active' : ''}"></div>
        <div class="tp-timeline-content">
          <div class="tp-timeline-role">${e.role}</div>
          <div class="tp-timeline-company">${e.company}</div>
          <div class="tp-timeline-years">${e.years}</div>
        </div>
      </div>`).join('');
  }

  const countEl = document.getElementById('tp-project-count');
  if (countEl) countEl.textContent = `${talent.projects?.length || 0} مشروع`;

  renderProjectCards(talent.projects, 'tp-projects', true);

  const links = talent.links || {};
  const setLink = (id, url) => {
    const el = document.getElementById(id);
    if (el && url) el.href = url;
  };
  setLink('tp-github', links.github);
  setLink('tp-linkedin', links.linkedin);
  setLink('tp-portfolio', links.portfolio);

  document.title = `${talent.name} - ${BRAND}`;
}

// ========================
// COMPANY PROFILE
// ========================
function renderCompanyProfile() {
  const id = getQueryParam('id') || '1';
  const company = getCompanyById(id);
  if (!company) return;

  const setText = (sel, text) => { const el = document.querySelector(sel); if (el) el.textContent = text; };

  setText('#cp-name', company.name);
  setText('#cp-breadcrumb-name', company.name);
  setText('#cp-logo', company.logo);
  setText('#cp-about', company.about);
  setText('#cp-mission', company.mission || company.about);
  setText('#cp-sector-line', `${company.sector} · ${company.location}`);
  setText('#cp-rating', company.rating);
  setText('#cp-jobs-count', company.jobs);
  setText('#cp-jobs-btn', company.jobs);
  setText('#cp-jobs-stat', company.jobs);
  setText('#cp-rating-stat', company.rating);
  setText('#cp-team', company.teamSize || '—');
  setText('#cp-team-side', company.teamSize || '—');
  setText('#cp-founded', company.founded || '—');
  setText('#cp-founded-side', company.founded || '—');
  setText('#cp-location', company.location);
  setText('#cp-timezone', company.timezone || 'UTC+2');
  setText('#cp-payment', (company.paymentMethods || []).join(' · '));

  const verified = document.getElementById('cp-verified');
  if (verified) verified.hidden = !company.verified;

  const websiteLink = document.getElementById('cp-website-link');
  if (websiteLink && company.website) {
    websiteLink.href = `https://${company.website}`;
    websiteLink.textContent = `🌐 ${company.website}`;
  }

  const tagsEl = document.getElementById('cp-tags');
  if (tagsEl) {
    tagsEl.innerHTML = [
      company.remoteFriendly ? '<span class="tag tag-teal">Remote-friendly 🌐</span>' : '',
      `<span class="tag tag-blue">${company.sector}</span>`,
      company.verified ? '<span class="tag tag-gold">موثّقة ✓</span>' : '',
      '<span class="remote-badge">🇸🇾 Syria-friendly</span>',
    ].join('');
  }

  const valuesEl = document.getElementById('cp-values');
  if (valuesEl && company.values) {
    valuesEl.innerHTML = company.values.map(v => `
      <div class="cp-value-item">
        <span class="cp-value-icon">◆</span>
        <span>${v}</span>
      </div>`).join('');
  }

  const techEl = document.getElementById('cp-tech');
  if (techEl && company.techStack) {
    techEl.innerHTML = company.techStack.map(t => `<span class="skill-tag">${t}</span>`).join('');
  }

  const cultureEl = document.getElementById('cp-culture');
  if (cultureEl && company.culture) {
    cultureEl.innerHTML = company.culture.map(c => `<li>${c}</li>`).join('');
  }

  const perksEl = document.getElementById('cp-perks');
  if (perksEl && company.perks) {
    perksEl.innerHTML = company.perks.map((p, i) => `
      <div class="cp-perk">
        <span class="cp-perk-icon">${['💵', '🌐', '📚', '🎁', '⏰', '🏥'][i % 6]}</span>
        <span>${p}</span>
      </div>`).join('');
  }

  const whyEl = document.getElementById('cp-why');
  if (whyEl && company.perks) {
    whyEl.innerHTML = company.perks.slice(0, 5).map(p => `<li><span>✓</span> ${p}</li>`).join('');
  }

  const companyJobs = getJobsData().filter(j => j.company === company.name);
  renderJobs('company-jobs-grid', companyJobs.length ? companyJobs : getJobsData().slice(0, 3), 4);

  document.title = `${company.name} - ${BRAND}`;
}

function filterCompanies(showEmptyToast = true) {
  const search = normalizeSearch(document.getElementById('companies-search')?.value);
  const chip = document.querySelector('.chip.selected');
  const category = chip?.dataset?.category ?? '';

  let filtered = getCompaniesData().filter(c => {
    if (search && !matchesSpecialty(c, search, ['name', 'sector', 'location'])) return false;
    if (category && c.category !== category) return false;
    return true;
  });

  renderCompanies('companies-grid', filtered, filtered.length);
  const countEl = document.getElementById('companies-count');
  if (countEl) countEl.textContent = filtered.length;
  if (filtered.length === 0 && showEmptyToast) showToast('لم نجد شركات — جرّب بحثاً آخر', 'error');
}

// ========================
// FILTERS
// ========================
function filterJobs(showEmptyToast = true) {
  const specialty = normalizeSearch(document.getElementById('jobs-search')?.value);
  const cityQuery = normalizeSearch(document.getElementById('jobs-city')?.value);
  const remoteOnly = document.getElementById('filter-remote')?.checked;
  const syriaOnly = document.getElementById('filter-syria')?.checked;
  const sidebarCities = getSelectedFilterCities('.filter-city-cb');

  let filtered = getJobsData().filter(j => {
    if (!matchesSpecialty(j, specialty, [
      'title', 'company',
      i => i.skills.join(' '),
      i => i.tags.join(' '),
      i => i.tagLabels.map(t => t.t).join(' '),
    ])) return false;
    if (cityQuery && !matchesCity(j.location, cityQuery)) return false;
    if (sidebarCities.length && !sidebarCities.some(c => matchesCity(j.location, c))) return false;
    if (remoteOnly && j.remoteType !== 'full-remote') return false;
    if (syriaOnly && !j.syriaFriendly) return false;
    return true;
  });

  const chip = document.querySelector('.chip.selected');
  if (chip) {
    const label = chip.textContent.trim();
    if (label.includes('عن بُعد')) filtered = filtered.filter(j => j.remoteType === 'full-remote');
    else if (label === 'Frontend') filtered = filtered.filter(j => j.tags.includes('frontend'));
    else if (label === 'Backend') filtered = filtered.filter(j => j.tags.includes('backend'));
    else if (label === 'DevOps') filtered = filtered.filter(j => j.tags.includes('devops'));
    else if (label === 'تصميم') filtered = filtered.filter(j => j.tags.includes('design'));
  }

  renderJobs('jobs-grid', filtered, filtered.length);
  const countEl = document.getElementById('jobs-count');
  const barEl = document.getElementById('jobs-count-bar');
  if (countEl) countEl.textContent = filtered.length;
  if (barEl) barEl.textContent = filtered.length;
  if (filtered.length === 0 && showEmptyToast) showToast('لم نجد نتائج — جرّب تخصصاً أو مدينة أخرى', 'error');
}

function resetJobFilters() {
  const remote = document.getElementById('filter-remote');
  const syria = document.getElementById('filter-syria');
  const salary = document.getElementById('filter-job-salary');
  const salaryVal = document.getElementById('filter-job-salary-val');

  if (remote) remote.checked = false;
  if (syria) syria.checked = false;
  if (salary) {
    salary.value = '2000';
    if (salaryVal) salaryVal.textContent = '2,000';
  }

  document.querySelectorAll('.filter-city-cb').forEach(cb => { cb.checked = false; });
  document.querySelectorAll('.filters-panel--pro input[name^="payment_"]').forEach(cb => { cb.checked = false; });

  const search = document.getElementById('jobs-search');
  const city = document.getElementById('jobs-city');
  if (search) search.value = '';
  if (city) city.value = '';

  document.querySelectorAll('.chip').forEach((chip, i) => {
    chip.classList.toggle('selected', i === 0);
  });

  filterJobs(false);
  showToast('تم مسح الفلاتر', 'success');
}

function filterTalents(showEmptyToast = true) {
  const specialty = normalizeSearch(document.getElementById('talents-search')?.value);
  const cityQuery = normalizeSearch(document.getElementById('talents-city')?.value);
  const remoteOnly = document.getElementById('filter-talent-remote')?.checked;
  const availableOnly = document.getElementById('filter-available')?.checked;
  const openToWorkOnly = document.getElementById('filter-open-to-work')?.checked;
  const sidebarCities = getSelectedFilterCities('.filter-talent-city-cb');

  let filtered = getTalentsData().filter(t => {
    if (!matchesSpecialty(t, specialty, [
      'name', 'title', 'bio',
      i => i.skills.join(' '),
    ])) return false;
    if (cityQuery && !matchesCity(t.city, cityQuery)) return false;
    if (sidebarCities.length && !sidebarCities.some(c => matchesCity(t.city, c))) return false;
    if (remoteOnly && !t.remote) return false;
    if (openToWorkOnly && !t.openToWork) return false;
    if (availableOnly && t.availability.includes('مشغولة')) return false;
    return true;
  });

  const chip = document.querySelector('.chip.selected');
  if (chip && document.getElementById('talents-grid')) {
    const label = chip.textContent.trim();
    const skillMap = {
      Frontend: ['react', 'typescript', 'frontend', 'next'],
      Backend: ['node', 'backend', 'postgresql', 'graphql'],
      Mobile: ['flutter', 'mobile', 'dart'],
      DevOps: ['devops', 'docker', 'kubernetes', 'aws'],
      'UI/UX': ['figma', 'ui', 'ux', 'design'],
    };
    if (skillMap[label]) {
      filtered = filtered.filter(t =>
        t.skills.some(s => skillMap[label].some(k => s.toLowerCase().includes(k)))
      );
    }
  }

  renderTalents('talents-grid', filtered, filtered.length);
  const countEl = document.getElementById('talents-count');
  const barEl = document.getElementById('talents-count-bar');
  if (countEl) countEl.textContent = filtered.length;
  if (barEl) barEl.textContent = filtered.length;
  if (filtered.length === 0 && showEmptyToast) showToast('لم نجد مواهب — جرّب تخصصاً أو مدينة أخرى', 'error');
}

function resetTalentFilters() {
  const available = document.getElementById('filter-available');
  const remote = document.getElementById('filter-talent-remote');
  const openToWork = document.getElementById('filter-open-to-work');
  const rate = document.getElementById('filter-talent-rate');
  const rateVal = document.getElementById('filter-talent-rate-val');

  if (available) available.checked = false;
  if (remote) remote.checked = true;
  if (openToWork) openToWork.checked = false;
  if (rate) {
    rate.value = '30';
    if (rateVal) rateVal.textContent = '30';
  }

  document.querySelectorAll('.filter-talent-city-cb').forEach(cb => { cb.checked = false; });

  const search = document.getElementById('talents-search');
  const city = document.getElementById('talents-city');
  if (search) search.value = '';
  if (city) city.value = '';

  document.querySelectorAll('.chip').forEach((chip, i) => {
    chip.classList.toggle('selected', i === 0);
  });

  filterTalents(false);
  showToast('تم مسح الفلاتر', 'success');
}

// ========================
// LOCAL STORAGE
// ========================
const LS_THEME = 'teksyria_theme';
const LS_SAVED_JOBS = 'teksyria_saved_jobs';
const LS_PROFILE = 'teksyria_profile';
const LS_APPLICATIONS = 'teksyria_applications';

function isJobSaved(id) {
  return JSON.parse(localStorage.getItem(LS_SAVED_JOBS) || '[]').includes(Number(id));
}

function toggleSaveJob(id, btn) {
  let saved = JSON.parse(localStorage.getItem(LS_SAVED_JOBS) || '[]');
  const numId = Number(id);
  if (saved.includes(numId)) {
    saved = saved.filter(x => x !== numId);
    btn?.classList.remove('saved');
    showToast('✕ تم إلغاء الحفظ', 'success');
  } else {
    saved.push(numId);
    btn?.classList.add('saved');
    showToast('🔖 تم حفظ الوظيفة', 'success');
  }
  localStorage.setItem(LS_SAVED_JOBS, JSON.stringify(saved));
}

function toggleSave(btn) {
  btn.classList.toggle('saved');
  showToast(btn.classList.contains('saved') ? '🔖 تم الحفظ' : '✕ تم إلغاء الحفظ', 'success');
}

function getStoredProfile() {
  try { return JSON.parse(localStorage.getItem(LS_PROFILE)); } catch { return null; }
}

function saveProfile(profile) {
  localStorage.setItem(LS_PROFILE, JSON.stringify(profile));
}

function getApplications() {
  return JSON.parse(localStorage.getItem(LS_APPLICATIONS) || '[]');
}

function applyToJob(jobId) {
  const auth = window.FRONTEND_AUTH || {};
  const routes = window.FRONTEND_ROUTES || {};

  if (!auth.loggedIn) {
    const loginUrl = routes.login || '/login';
    const returnUrl = window.location.href;
    window.location.href = `${loginUrl}?redirect=${encodeURIComponent(returnUrl)}`;
    return;
  }

  const applyUrl = window.JOB_APPLY_URL || (routes.jobs ? `${routes.jobs}/${jobId}/apply` : null);
  if (!applyUrl) {
    showToast('تعذر إرسال الطلب', 'error');
    return;
  }

  const btn = document.getElementById('job-apply-btn');
  if (btn?.disabled) return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  if (btn) {
    btn.disabled = true;
    btn.textContent = 'جاري الإرسال...';
  }

  fetch(applyUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrf,
      'X-Requested-With': 'XMLHttpRequest',
    },
    credentials: 'same-origin',
  })
    .then(async (res) => {
      const data = await res.json().catch(() => ({}));
      if (res.ok && data.success) {
        showToast('✅ ' + (data.message || 'تم إرسال طلبك بنجاح!'), 'success');
        if (btn) {
          btn.classList.add('btn-apply--done');
          btn.textContent = data.status_label || 'قيد المراجعة';
          btn.disabled = true;
        }
        const note = document.querySelector('.apply-box-note');
        if (!note && btn?.parentElement) {
          const p = document.createElement('p');
          p.className = 'apply-box-note';
          p.textContent = 'تم إرسال طلبك لهذه الوظيفة';
          btn.parentElement.appendChild(p);
        }
        return;
      }
      if (res.status === 409) {
        showToast(data.message || 'لقد تقدمت لهذه الوظيفة مسبقاً', 'error');
        if (btn) {
          btn.classList.add('btn-apply--done');
          btn.textContent = data.status_label || 'قيد المراجعة';
          btn.disabled = true;
        }
        return;
      }
      throw new Error(data.message || 'فشل إرسال الطلب');
    })
    .catch((err) => {
      showToast(err.message || 'حدث خطأ، حاول مجدداً', 'error');
      if (btn && !btn.classList.contains('btn-apply--done')) {
        btn.disabled = false;
        btn.textContent = 'تقدم الآن';
      }
    });
}

function loadEditProfileForm() {
  const stored = getStoredProfile();
  const talent = stored || TALENTS[0];
  const fields = { 'ep-name': talent.name, 'ep-title': talent.title, 'ep-city': talent.city, 'ep-bio': talent.bio, 'ep-rate': talent.rateUSD, 'ep-availability': talent.availability };
  Object.entries(fields).forEach(([id, val]) => { const el = document.getElementById(id); if (el) el.value = val || ''; });
  const skillsEl = document.getElementById('ep-skills');
  if (skillsEl) skillsEl.value = (talent.skills || []).join(', ');
  renderEditProjects(talent.projects || []);
}

function renderEditProjects(projects) {
  const el = document.getElementById('ep-projects-list');
  if (!el) return;
  el.innerHTML = projects.map((p, i) => `
    <div class="edit-project-item" data-index="${i}">
      <input type="text" class="form-input" value="${p.title}" data-field="title" placeholder="عنوان المشروع">
      <input type="text" class="form-input" value="${p.desc}" data-field="desc" placeholder="الوصف">
      <input type="text" class="form-input" value="${(p.tags || []).join(', ')}" data-field="tags" placeholder="React, Node.js">
      <button type="button" class="btn btn-outline btn-sm" onclick="removeEditProject(${i})">حذف</button>
    </div>`).join('');
}

function addEditProject() {
  const stored = getStoredProfile() || { ...TALENTS[0], id: 99, projects: [] };
  stored.projects = stored.projects || [];
  stored.projects.push({ id: Date.now(), title: '', desc: '', image: '💼', demoUrl: '#', githubUrl: '#', tags: [] });
  saveProfile(stored);
  renderEditProjects(stored.projects);
}

function removeEditProject(index) {
  const stored = getStoredProfile() || { projects: [] };
  stored.projects.splice(index, 1);
  saveProfile(stored);
  renderEditProjects(stored.projects);
}

function saveEditProfile(e) {
  e.preventDefault();
  const projects = [];
  document.querySelectorAll('.edit-project-item').forEach(item => {
    const title = item.querySelector('[data-field="title"]')?.value || '';
    const desc = item.querySelector('[data-field="desc"]')?.value || '';
    const tags = (item.querySelector('[data-field="tags"]')?.value || '').split(',').map(t => t.trim()).filter(Boolean);
    if (title) projects.push({ id: Date.now() + Math.random(), title, desc, image: '💼', demoUrl: '#', githubUrl: '#', tags });
  });

  const profile = {
    id: getStoredProfile()?.id || 99,
    name: document.getElementById('ep-name')?.value || '',
    title: document.getElementById('ep-title')?.value || '',
    city: document.getElementById('ep-city')?.value || 'دمشق',
    avatar: (document.getElementById('ep-name')?.value || 'أ')[0],
    bio: document.getElementById('ep-bio')?.value || '',
    rateUSD: document.getElementById('ep-rate')?.value || '',
    availability: document.getElementById('ep-availability')?.value || 'متاح',
    skills: (document.getElementById('ep-skills')?.value || '').split(',').map(s => s.trim()).filter(Boolean),
    projects,
    remote: true,
    verified: false,
    featured: false,
    experience: getStoredProfile()?.experience || [],
    links: getStoredProfile()?.links || { github: '#', linkedin: '#', portfolio: '#' },
  };
  saveProfile(profile);
  showToast('✅ تم حفظ ملفك بنجاح!', 'success');
  setTimeout(() => goTo(`talent-profile.html?id=${profile.id}`), 800);
}

function renderSeekerDashboard() {
  const profile = getStoredProfile() || TALENTS[0];
  const apps = getApplications();
  const saved = JSON.parse(localStorage.getItem(LS_SAVED_JOBS) || '[]');

  const setText = (sel, text) => { const el = document.querySelector(sel); if (el) el.textContent = text; };
  setText('#sd-name', profile.name);
  setText('#sd-title', profile.title);
  setText('#sd-avatar', profile.avatar);

  const completion = Math.min(100, 40 + (profile.skills?.length || 0) * 8 + (profile.projects?.length || 0) * 15);
  const fill = document.querySelector('#sd-completion-fill');
  if (fill) fill.style.width = completion + '%';
  setText('#sd-completion-text', `اكتمال الملف: ${completion}%`);

  const appsEl = document.getElementById('sd-applications');
  if (appsEl && !appsEl.dataset.serverRendered) {
    appsEl.innerHTML = apps.length ? apps.map(a => `
      <div class="app-item">
        <div><strong>${a.title}</strong> — ${a.company}</div>
        <span class="tag tag-teal">${a.status}</span>
        <span style="font-size:0.82rem;color:var(--text3)">${a.date}</span>
      </div>`).join('') : `<p style="color:var(--text3)">لم تتقدم لأي وظيفة بعد — <a href="${resolveUrl('jobs.html') || '#'}" style="color:var(--accent)">تصفح الوظائف</a></p>`;
  }

  const savedEl = document.getElementById('sd-saved-jobs');
  if (savedEl) {
    const savedJobs = JOBS.filter(j => saved.includes(j.id));
    savedEl.innerHTML = savedJobs.length ? savedJobs.map(j => createJobCard(j)).join('') : '<p style="color:var(--text3)">لا وظائف محفوظة</p>';
  }
}

// ========================
// THEME
// ========================
function initTheme() {
  const saved = localStorage.getItem(LS_THEME);
  const dark = saved === 'dark';
  document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
  document.querySelectorAll('.theme-toggle').forEach(btn => { btn.textContent = dark ? '☀️' : '🌙'; });
}

function toggleTheme() {
  const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
  const next = isDark ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  localStorage.setItem(LS_THEME, next);
  document.querySelectorAll('.theme-toggle').forEach(btn => { btn.textContent = next === 'dark' ? '☀️' : '🌙'; });
}

// ========================
// MODAL
// ========================
function openModal(type) {
  document.getElementById('auth-modal')?.classList.add('open');
  if (type === 'register') switchTab('register');
  else switchTab('login');
}
function closeModal() {
  document.getElementById('auth-modal')?.classList.remove('open');
}
function closeModalOutside(e) {
  if (e.target?.id === 'auth-modal') closeModal();
}
function switchTab(tab) {
  document.querySelectorAll('.tab-btn').forEach((btn, i) => btn.classList.toggle('active', (tab === 'login' ? i === 0 : i === 1)));
  const login = document.getElementById('login-form');
  const reg = document.getElementById('register-form');
  if (login) login.style.display = tab === 'login' ? 'block' : 'none';
  if (reg) reg.style.display = tab === 'register' ? 'block' : 'none';
}
function selectRole(role) {
  document.getElementById('role-seeker')?.classList.toggle('active', role === 'seeker');
  document.getElementById('role-company')?.classList.toggle('active', role === 'company');
}
function loginSuccess() {
  closeModal();
  showToast(`✅ مرحباً بك في ${BRAND}!`, 'success');
  goTo('dashboard-seeker.html');
}
function registerSuccess() {
  const roleCompany = document.getElementById('role-company')?.classList.contains('active');
  closeModal();
  showToast('🎉 تم إنشاء حسابك بنجاح!', 'success');
  if (roleCompany) goTo('dashboard-company.html');
  else {
    const name = document.getElementById('reg-name')?.value || 'مستخدم جديد';
    const title = document.getElementById('reg-title')?.value || 'مطور';
    saveProfile({ id: 99, name, title, city: 'دمشق', avatar: name[0], bio: '', skills: [], projects: [], remote: true, availability: 'متاح', rateUSD: '', experience: [], links: {}, verified: false, featured: false });
    goTo('edit-profile.html');
  }
}

// ========================
// TAG INPUT
// ========================
function addTag(e, wrapId) {
  if (e.key !== 'Enter') return;
  const input = e.target;
  const val = input.value.trim();
  if (!val) return;
  const wrap = document.getElementById(wrapId);
  const tag = document.createElement('span');
  tag.className = 'tag-item';
  tag.innerHTML = `${val} <span class="tag-remove" onclick="removeTag(this)">✕</span>`;
  wrap.insertBefore(tag, input);
  input.value = '';
  e.preventDefault();
}
function removeTag(el) {
  el.parentElement.remove();
}

// ========================
// CHIPS
// ========================
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('chip')) {
    const siblings = e.target.parentElement.querySelectorAll('.chip');
    siblings.forEach(c => c.classList.remove('selected'));
    e.target.classList.add('selected');
    if (document.getElementById('jobs-grid')) filterJobs(false);
    if (document.getElementById('talents-grid')) filterTalents(false);
    if (document.getElementById('companies-grid')) filterCompanies(false);
  }
});

// ========================
// TOAST
// ========================
function showToast(msg, type = 'success') {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container';
    document.body.appendChild(container);
  }
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span class="toast-icon">${type === 'success' ? '✅' : '❌'}</span> ${msg}`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(10px)';
    toast.style.transition = '0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

function contactTalent() {
  showToast('📧 سيتم تفعيل التواصل عند ربط المنصة — demo', 'success');
}

// ========================
// PAGE INIT
// ========================
function runPageInit(page) {
  switch (page) {
    case 'home':
      if (!document.getElementById('home-jobs-grid')?.children.length) {
        renderJobs('home-jobs-grid', getJobsData().filter(j => j.remoteType === 'full-remote'), 6);
      }
      if (!document.getElementById('home-talents-grid')?.children.length) {
        renderTalents('home-talents-grid', getTalentsData().filter(t => t.featured), 4);
      }
      if (!document.getElementById('home-companies-grid')?.children.length) {
        renderCompanies('home-companies-grid', getCompaniesData().filter(c => c.remoteFriendly), 8);
      }
      initHeroSearch();
      initSearchAutocompletes();
      break;
    case 'jobs':
      if (!document.getElementById('jobs-grid')?.children.length) {
        renderJobs('jobs-grid', getJobsData(), getJobsData().length);
      }
      initJobsPageSearch();
      initSearchAutocompletes();
      break;
    case 'talents':
      if (!document.getElementById('talents-grid')?.children.length) {
        renderTalents('talents-grid', getTalentsData(), getTalentsData().length);
      }
      initTalentsPageSearch();
      initSearchAutocompletes();
      filterTalents(false);
      break;
    case 'companies':
      if (!document.getElementById('companies-grid')?.children.length) {
        renderCompanies('companies-grid', getCompaniesData(), getCompaniesData().length);
      }
      break;
    case 'job-detail':
      if (document.getElementById('jd-title')?.textContent.trim() === '—') {
        renderJobDetail();
      }
      break;
    case 'talent-profile':
      if (document.getElementById('tp-name')?.textContent.trim() === '—') {
        renderTalentProfile();
      }
      break;
    case 'company-profile':
      if (document.getElementById('cp-name')?.textContent.trim() === '—') {
        renderCompanyProfile();
      }
      break;
    case 'edit-profile':
      loadEditProfileForm();
      break;
    case 'dashboard-seeker':
      renderSeekerDashboard();
      break;
    case 'profile':
      renderSeekerDashboard();
      break;
  }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  const page = document.body.dataset.page;
  if (page) {
    injectLayout(page);
    runPageInit(page);
  } else {
    initNavbarScroll();
  }
});
