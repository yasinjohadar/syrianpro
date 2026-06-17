<?php

use App\Http\Controllers\Talent\DashboardController;
use App\Http\Controllers\Talent\HiringRequestController;
use App\Http\Controllers\Talent\JobApplicationController;
use App\Http\Controllers\Talent\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.user.active', 'role:talent'])
    ->prefix('talent')
    ->name('talent.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/applications', [JobApplicationController::class, 'index'])->name('applications.index');
        Route::get('/hiring-request', [HiringRequestController::class, 'index'])->name('hiring-request.index');
        Route::get('/hires', [\App\Http\Controllers\Talent\HireController::class, 'index'])->name('hires.index');
        Route::post('/hiring-request/public', [HiringRequestController::class, 'storePublic'])->name('hiring-request.store-public');
        Route::post('/hiring-request/pitch', [HiringRequestController::class, 'storePitch'])->name('hiring-request.store-pitch');
        Route::post('/hiring-request/toggle-open', [HiringRequestController::class, 'toggleOpenToWork'])->name('hiring-request.toggle-open');
        Route::post('/hiring-request/{hiringRequest}/pause', [HiringRequestController::class, 'pause'])->name('hiring-request.pause');
        Route::post('/hiring-request/{hiringRequest}/resume', [HiringRequestController::class, 'resume'])->name('hiring-request.resume');
        Route::post('/hiring-request/{hiringRequest}/close', [HiringRequestController::class, 'close'])->name('hiring-request.close');
        Route::post('/hiring-request/{hiringRequest}/mark-hired', [HiringRequestController::class, 'markHired'])->name('hiring-request.mark-hired');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    });
