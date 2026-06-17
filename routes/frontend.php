<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\Frontend\CompanyController;
use App\Http\Controllers\Frontend\JobApplicationController;
use App\Http\Controllers\Frontend\JobController;
use App\Http\Controllers\Frontend\TalentController;
use App\Http\Controllers\NewsletterController;
use App\Models\BlogPost;
use App\Models\BlogCategory;

Route::get('/blog', function () {
    $categories = BlogCategory::active()->orderBy('order')->get();
    $featuredPost = BlogPost::published()
        ->with('category', 'author')
        ->where('is_featured', true)
        ->latest('published_at')
        ->first();
    if (! $featuredPost) {
        $featuredPost = BlogPost::published()->with('category', 'author')->latest('published_at')->first();
    }
    $posts = BlogPost::published()
        ->with('category', 'author')
        ->latest('published_at')
        ->paginate(9);

    return view('frontend.pages.blog', compact('posts', 'categories', 'featuredPost'));
})->name('blog');

Route::get('/blog/{slug}', function ($slug) {
    $post = BlogPost::where('slug', $slug)->published()->with('category', 'author', 'tags')->firstOrFail();
    $recentPosts = BlogPost::published()
        ->where('id', '!=', $post->id)
        ->with('category')
        ->latest('published_at')
        ->take(4)
        ->get();
    $categories = BlogCategory::active()->withCount('publishedPosts')->orderBy('order')->get();
    $prevPost = BlogPost::published()->where('published_at', '>', $post->published_at)->latest('published_at')->first();
    $nextPost = BlogPost::published()->where('published_at', '<', $post->published_at)->oldest('published_at')->first();

    return view('frontend.pages.blog-detail', compact('post', 'recentPosts', 'categories', 'prevPost', 'nextPost'));
})->name('blog.show');

// الخدمات (المهارات / التخصصات)
$serviceViews = [
    'web' => 'frontend.pages.service-detail',
    'mobile' => 'frontend.pages.service-detail-mobile',
    'security' => 'frontend.pages.service-detail-security',
    'servers' => 'frontend.pages.service-detail-servers',
    'devops' => 'frontend.pages.service-detail-devops',
];
Route::get('/services/{slug}', function ($slug) use ($serviceViews) {
    if (! array_key_exists($slug, $serviceViews)) {
        abort(404);
    }

    return view($serviceViews[$slug]);
})->name('service.show')->where('slug', 'web|mobile|security|servers|devops');

Route::get('/contact', function () {
    return view('frontend.pages.contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('/about', function () {
    return view('frontend.pages.about');
})->name('about');

Route::get('/consultation', function () {
    return view('frontend.pages.consultation');
})->name('consultation');

Route::post('/consultation', [ConsultationController::class, 'store'])->name('consultation.store');

// تك سوريا — منصة المواهب التقنية
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

Route::middleware(['auth', 'check.user.active'])->group(function () {
    Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply');
});

Route::redirect('/dashboard/seeker', '/talent')->middleware(['auth', 'check.user.active'])->name('dashboard.seeker');
Route::redirect('/dashboard/company', '/company')->middleware(['auth', 'check.user.active'])->name('dashboard.company');

Route::get('/talents', [TalentController::class, 'index'])->name('talents.index');
Route::get('/talents/{talent}', [TalentController::class, 'show'])->name('talents.show');

Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');

Route::get('/post-job', function () {
    if (auth()->check() && auth()->user()->hasRole('company')) {
        return redirect()->route('company.jobs.create');
    }

    return view('frontend.pages.post-job', ['activePage' => 'post-job']);
})->name('post-job');
Route::get('/edit-profile', fn () => view('frontend.pages.edit-profile', ['activePage' => 'edit-profile']))->name('edit-profile');
