<?php

use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\HiringRequestController;
use App\Http\Controllers\Company\JobApplicationController;
use App\Http\Controllers\Company\JobController;
use App\Http\Controllers\Company\ProfileController;
use App\Http\Controllers\Company\TalentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.user.active', 'role:company'])
    ->prefix('company')
    ->name('company.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [JobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [JobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');
        Route::post('/jobs/{job}/toggle-active', [JobController::class, 'toggleActive'])->name('jobs.toggle-active');
        Route::get('/applications', [JobApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
        Route::put('/applications/{application}', [JobApplicationController::class, 'update'])->name('applications.update');
        Route::get('/talents', [TalentController::class, 'index'])->name('talents.index');
        Route::get('/talents/{talent}', [TalentController::class, 'show'])->name('talents.show');
        Route::get('/hiring-requests', [HiringRequestController::class, 'index'])->name('hiring-requests.index');
        Route::get('/hires', [\App\Http\Controllers\Company\HireController::class, 'index'])->name('hires.index');
        Route::get('/shortlist', [\App\Http\Controllers\Company\TalentActionController::class, 'shortlist'])->name('shortlist.index');
        Route::post('/talents/{talent}/invite', [\App\Http\Controllers\Company\TalentActionController::class, 'invite'])->name('talents.invite');
        Route::post('/talents/{talent}/shortlist', [\App\Http\Controllers\Company\TalentActionController::class, 'toggleShortlist'])->name('talents.shortlist');
        Route::post('/talents/{talent}/note', [\App\Http\Controllers\Company\TalentActionController::class, 'storeNote'])->name('talents.note');
        Route::get('/hiring-requests/{hiringRequest}', [HiringRequestController::class, 'show'])->name('hiring-requests.show');
        Route::post('/hiring-requests/{hiringRequest}/respond', [HiringRequestController::class, 'respond'])->name('hiring-requests.respond');
        Route::post('/hiring-requests/{hiringRequest}/mark-hired', [HiringRequestController::class, 'markHired'])->name('hiring-requests.mark-hired');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    });
