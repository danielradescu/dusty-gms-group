<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameSession\CreateSessionController;
use App\Http\Controllers\GameSession\FinalizeGameSessionController;
use App\Http\Controllers\GameSession\InteractionController;
use App\Http\Controllers\GameSession\ManagementController as GameSessionManagementController;
use App\Http\Controllers\GameSessionRequestController;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\JoinRequest\ManagementController as JoinRequestManagementController;
use App\Http\Controllers\JoinRequest\MemberController;
use App\Http\Controllers\JoinRequest\PublicController;
use App\Http\Controllers\MagicLoginController;
use App\Http\Controllers\NotificationSubscriptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ExtendedWeekendController;
use App\Http\Controllers\RankingController;
use Illuminate\Support\Facades\Route;

// --- Custom Route Binding (runs before routes load)
Route::bind('admin_user', function ($value) {
    return User::withoutGlobalScopes()->findOrFail($value);
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('role:Admin')->group(function () {
    Route::get('/admin/dashboard', fn () => view('admin.dashboard'));
});

Route::middleware('auth', 'verified', 'verified.reviewer')->group(function () {
    //dashboard:
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //boardgame sessions:
    Route::get('/create-game-session', [CreateSessionController::class, 'create'])->name('game-session.create');
    Route::post('/create-game-session', [CreateSessionController::class, 'store'])->name('game-session.store');
    Route::get('/game-session/{uuid}/created', [CreateSessionController::class, 'show'])->name('game-session.create.show');
    Route::post('/game-session/{uuid}', [InteractionController::class, 'store'])->name('game-session.interaction.store');
    Route::get('/game-session/{uuid}/manage', [GameSessionManagementController::class, 'edit'])->name('game-session.manage.edit');
    Route::patch('/game-session/{uuid}/core-info', [GameSessionManagementController::class, 'updateCoreInfo'])->name('game-session.manage.update.core-info');
    Route::patch('/game-session/{uuid}/status', [GameSessionManagementController::class, 'updateStatus'])->name('game-session.manage.update.status');
    Route::patch('/game-session/{uuid}/organizer', [GameSessionManagementController::class, 'updateOrganizer'])->name('game-session.manage.update.organizer');
    Route::get('/game-session/{uuid}/finalize', [FinalizeGameSessionController::class, 'create'])->name('game-session.finalize.create');
    Route::post('/game-session/{uuid}/finalize', [FinalizeGameSessionController::class, 'store'])->name('game-session.finalize.store');

    //comments:
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    //announcement, same as comment, but special
    Route::post('/announcement', [AnnouncementController::class, 'store'])->name('announcement.store');

    //game session request
    Route::post('/game-session-request', [GameSessionRequestController::class, 'store'])->name('game-session-request.store');

    //notification subscriptions
    Route::get('/notification-subscription', [NotificationSubscriptionController::class, 'edit'])->name('notification-subscription.edit');
    Route::post('/notification-subscription', [NotificationSubscriptionController::class, 'update'])->name('notification-subscription.update');
    Route::get('/unsubscribe', [NotificationSubscriptionController::class, 'edit'])->name('unsubscribe');

    //notifications:
    Route::get('/notifications', [InAppNotificationController::class, 'index'])->name('in-app-notifications.index');

    //admin user management
    Route::prefix('admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('admin.users.index');

        Route::get('/users/{admin_user}/edit', [UserManagementController::class, 'edit'])
            ->name('admin.user.edit');

        Route::patch('/users/{admin_user}', [UserManagementController::class, 'update'])
            ->name('admin.user.update');
    });
    ute::patch('/users/{user}', [UserManagementController::class, 'update'])->name('admin.user.update');
    });

    //join-request member
    Route::get('/invite', [MemberController::class, 'create'])->name('member-invite-create');
    Route::post('/invite', [MemberController::class, 'store'])->name('member-invite-store');

    //join-request management
    Route::get('/join-request', [JoinRequestManagementController::class, 'index'])->name('management-join-request-index');
    Route::get('/join-request/{joinRequest}/edit', [JoinRequestManagementController::class, 'edit'])->name('management-join-request-edit');
    Route::put('/join-request/{joinRequest}/update', [JoinRequestManagementController::class, 'update'])->name('management-join-request-update');

    //contact
    Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

    Route::get('/extended-weekend', [ExtendedWeekendController::class, 'edit'])->name('extended-weekend.edit');
    Route::patch('/extended-weekend', [ExtendedWeekendController::class, 'update'])->name('extended-weekend.update');

    //ranking
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
});

//Pages:
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about-us');
Route::get('/terms-of-service', [PageController::class, 'termsOfService'])->name('terms-of-service');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');

//boardgame sessions:
Route::get('/game-session/{uuid}', [InteractionController::class, 'show'])->name('game-session.interaction.show');

//Magic link with auto login
Route::get('/magic-login', MagicLoginController::class)->name('magic-login');

//join-request public
Route::get('/join', [PublicController::class, 'create'])->name('public-join-create');
Route::post('/join', [PublicController::class, 'store'])->name('public-join-store');

require __DIR__.'/auth.php';
