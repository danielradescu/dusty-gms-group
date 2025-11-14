<?php

use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\NotificationSubscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GameSessionRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MagicLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth', 'verified')->group(function () {
    //dashboard:
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //boardgame sessions:
    Route::get('/create-game-session', [GameSessionController::class, 'create'])->name('game-sessions.create');
    Route::post('/create-game-session', [GameSessionController::class, 'store'])->name('game-sessions.store');
    Route::get('/game-sessions/{uuid}/created', [GameSessionController::class, 'created'])->name('game-session.created');
    Route::post('/game-session/{uuid}', [GameSessionController::class, 'handle'])->name('game-session.handle');

    //comments:
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

    //game session request
    Route::post('/game-session-request', [GameSessionRequestController::class, 'store'])->name('game-session-request.store');

    //notification subscriptions
    Route::get('/notification', [NotificationSubscriptionController::class, 'edit'])->name('notification.edit');
    Route::post('/notification', [NotificationSubscriptionController::class, 'update'])->name('notification.update');
});

//Pages:
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about-us');
Route::get('/terms-of-service', [PageController::class, 'termsOfService'])->name('terms-of-service');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');

//boardgame sessions:
Route::get('/game-session/{uuid}', [GameSessionController::class, 'show'])->name('show.game-session');

//Magic link with auto login
Route::get('/magic-login', MagicLoginController::class)->name('magic-login');

require __DIR__.'/auth.php';
