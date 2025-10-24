<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CardController as AdminCardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CardController;

// Redirect root to dashboard; auth middleware will send guests to login
Route::redirect('/', '/dashboard');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Card details page
Route::get('/cards/{card}', [CardController::class, 'show'])->middleware('auth')->name('cards.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin panel (auth + approved)
Route::middleware(['auth', 'approved'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard РґРѕСЃС‚СѓРїРµРЅ Р°РґРјРёРЅСѓ Рё mager
    Route::middleware('role:admin|mager')->get('/', [AdminDashboardController::class, 'index'])->name('index');

    // Cards CRUD: admin|mager
    Route::middleware('role:admin|mager')->group(function () {
        Route::get('/cards', [AdminCardController::class, 'index'])->name('cards.index');
        Route::post('/cards', [AdminCardController::class, 'store'])->name('cards.store');
        Route::post('/cards/{card}', [AdminCardController::class, 'update'])->name('cards.update');
        Route::delete('/cards/{card}', [AdminCardController::class, 'destroy'])->name('cards.destroy');
    });

    // Users & Settings: С‚РѕР»СЊРєРѕ admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
        Route::post('/users/{user}/revoke', [AdminUserController::class, 'revoke'])->name('users.revoke');
        Route::post('/users/{user}/roles', [AdminUserController::class, 'setRoles'])->name('users.roles');

        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/pings', [AdminSettingsController::class, 'storePing'])->name('settings.pings.store');
        Route::post('/settings/pings/{ping}', [AdminSettingsController::class, 'updatePing'])->name('settings.pings.update');
        Route::delete('/settings/pings/{ping}', [AdminSettingsController::class, 'destroyPing'])->name('settings.pings.destroy');
    });
});

require __DIR__.'/auth.php';

// РќРµР°РІС‚РѕСЂРёР·РѕРІР°РЅРЅС‹Рµ РїРѕ Р»СЋР±С‹Рј С‡СѓР¶РёРј СЃСЃС‹Р»РєР°Рј РїРѕРїР°РґР°СЋС‚ РЅР° login
Route::fallback(function () {
    return redirect('/dashboard');
});







