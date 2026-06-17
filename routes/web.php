<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServerTestController;
// عرض صور المدونة (الصورة البارزة) من التخزين
Route::get('/serve/blog-image/{filename}', function (string $filename) {
    $path = 'blog/images/' . $filename;
    if (! \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
        abort(404);
    }
    $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
    $mime = match (strtolower(pathinfo($filename, PATHINFO_EXTENSION))) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        default => 'application/octet-stream',
    };

    return response()->file($fullPath, ['Content-Type' => $mime]);
})->where('filename', '[a-zA-Z0-9_.-]+')->name('blog.image');

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(auth()->user()->dashboardRouteName());
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'check.user.active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/company.php';
require __DIR__.'/talent.php';
require __DIR__.'/frontend.php';

// اختبار السيرفر — الإصدارات، قاعدة البيانات، ENV، الفرونتند (استخدم ?key=قيمة SERVER_TEST_KEY من .env)
Route::get('/server-test', [ServerTestController::class, 'index'])->name('server.test');
