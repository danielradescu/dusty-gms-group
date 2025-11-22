<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameSession\CreateSessionController;
use App\Http\Controllers\GameSession\InteractionController;
use App\Http\Controllers\GameSession\ManagementController;
use App\Http\Controllers\GameSessionRequestController;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\MagicLoginController;
use App\Http\Controllers\NotificationSubscriptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('role:Admin')->group(function () {
    Route::get('/admin/dashboard', fn () => view('admin.dashboard'));
});

Route::middleware('auth', 'verified')->group(function () {
    //dashboard:
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //boardgame sessions:
    Route::get('/create-game-session', [CreateSessionController::class, 'create'])->name('game-sessions.create');
    Route::post('/create-game-session', [CreateSessionController::class, 'store'])->name('game-sessions.store');
    Route::get('/game-session/{uuid}/created', [CreateSessionController::class, 'show'])->name('game-session.create.show');
    Route::post('/game-session/{uuid}', [InteractionController::class, 'store'])->name('game-session.interaction.store');
    Route::get('/game-session/{uuid}/manage', [ManagementController::class, 'edit'])->name('game-session.manage.edit');
    Route::patch('/game-session/{uuid}/core-info', [ManagementController::class, 'updateCoreInfo'])->name('game-session.manage.update.core-info');
    Route::patch('/game-session/{uuid}/status', [ManagementController::class, 'updateStatus'])->name('game-session.manage.update.status');
    Route::patch('/game-session/{uuid}/organizer', [ManagementController::class, 'updateOrganizer'])->name('game-session.manage.update.organizer');

    //comments:
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

    //game session request
    Route::post('/game-session-request', [GameSessionRequestController::class, 'store'])->name('game-session-request.store');

    //notification subscriptions
    Route::get('/notification', [NotificationSubscriptionController::class, 'edit'])->name('notification-subscription.edit');
    Route::post('/notification', [NotificationSubscriptionController::class, 'update'])->name('notification-subscription.update');

    //notifications:
    Route::get('/notifications', [InAppNotificationController::class, 'index'])->name('in-app-notifications.index');
});

//Pages:
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about-us');
Route::get('/terms-of-service', [PageController::class, 'termsOfService'])->name('terms-of-service');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');

//boardgame sessions:
Route::get('/game-session/{uuid}', [InteractionController::class, 'show'])->name('game-session.interaction.show');

//Magic link with auto login
Route::get('/magic-login', MagicLoginController::class)->name('magic-login');

require __DIR__.'/auth.php';
