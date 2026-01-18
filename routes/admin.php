<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminInnovationController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminInnovatorOfTheMonthController;
use App\Http\Controllers\AdminInnovationRankingController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('auth.login');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');

    Route::middleware(['admin'])->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/innovations', [AdminInnovationController::class, 'index'])
            ->name('innovations.index');

        Route::get('/innovations/create', [AdminInnovationController::class, 'create'])
            ->name('innovations.create');

        Route::post('/innovations', [AdminInnovationController::class, 'store'])
            ->name('innovations.store');

        Route::get('/innovations/{innovation}', [AdminInnovationController::class, 'show'])
            ->name('innovations.show');

        Route::get('/innovations/{innovation}/edit', [AdminInnovationController::class, 'edit'])
            ->name('innovations.edit');

        Route::put('/innovations/{innovation}', [AdminInnovationController::class, 'update'])
            ->name('innovations.update');

        Route::get('/permissions', [AdminPermissionController::class, 'index'])
            ->name('permissions.index');

        Route::get('/permissions/{innovation}', [AdminPermissionController::class, 'show'])
            ->name('permissions.show');

        Route::post('/permissions/{innovation}/accept', [AdminPermissionController::class, 'accept'])
            ->name('permissions.accept');

        Route::post('/permissions/{innovation}/decline', [AdminPermissionController::class, 'decline'])
            ->name('permissions.decline');

        Route::get('/innovator-of-the-month', [AdminInnovatorOfTheMonthController::class, 'edit'])
            ->name('innovator_of_month.edit');

        Route::post('/innovator-of-the-month', [AdminInnovatorOfTheMonthController::class, 'update'])
            ->name('innovator_of_month.update');

        Route::get('/innovation-rankings', [AdminInnovationRankingController::class, 'index'])
            ->name('innovation_rankings.index');

        Route::get('/innovation-rankings/create', [AdminInnovationRankingController::class, 'create'])
            ->name('innovation_rankings.create');

        Route::post('/innovation-rankings', [AdminInnovationRankingController::class, 'store'])
            ->name('innovation_rankings.store');

        Route::get('/innovation-rankings/{ranking}/edit', [AdminInnovationRankingController::class, 'edit'])
            ->name('innovation_rankings.edit');

        Route::put('/innovation-rankings/{ranking}', [AdminInnovationRankingController::class, 'update'])
            ->name('innovation_rankings.update');

        Route::delete('/innovation-rankings/{ranking}', [AdminInnovationRankingController::class, 'destroy'])
            ->name('innovation_rankings.destroy');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        });
    });
});
