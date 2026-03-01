<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPamfletController;
use App\Http\Controllers\AdminInnovationController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminInnovationRankingController;
use App\Http\Controllers\AdminInnovatorController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['admin'])->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/pamflet', [AdminPamfletController::class, 'index'])->name('pamflet.index');
        Route::post('/pamflet', [AdminPamfletController::class, 'update'])->name('pamflet.update');
        Route::delete('/pamflet/{slot}', [AdminPamfletController::class, 'destroy'])->name('pamflet.delete');

        Route::get('/innovations', [AdminInnovationController::class, 'index'])->name('innovations.index');
        Route::get('/innovations/create', [AdminInnovationController::class, 'create'])->name('innovations.create');
        Route::post('/innovations', [AdminInnovationController::class, 'store'])->name('innovations.store');
        Route::get('/innovations/{innovation}', [AdminInnovationController::class, 'show'])->name('innovations.show');
        Route::get('/innovations/{innovation}/edit', [AdminInnovationController::class, 'edit'])->name('innovations.edit');
        Route::put('/innovations/{innovation}', [AdminInnovationController::class, 'update'])->name('innovations.update');
        Route::delete('/innovations/{innovation}', [AdminInnovationController::class, 'destroy'])->name('innovations.destroy');
        Route::patch('/innovations/{innovation}/status', [AdminInnovationController::class, 'updateStatus'])->name('innovations.status');

        Route::get('/permissions', [AdminPermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/{innovation}', [AdminPermissionController::class, 'show'])->name('permissions.show');
        Route::post('/permissions/{innovation}/accept', [AdminPermissionController::class, 'accept'])->name('permissions.accept');
        Route::post('/permissions/{innovation}/decline', [AdminPermissionController::class, 'decline'])->name('permissions.decline');

        Route::get('/innovators', [AdminInnovatorController::class, 'index'])->name('innovators.index');
        Route::get('/innovators/create', [AdminInnovatorController::class, 'create'])->name('innovators.create');
        Route::post('/innovators', [AdminInnovatorController::class, 'store'])->name('innovators.store');
        Route::get('/innovators/{item}/edit', [AdminInnovatorController::class, 'edit'])->name('innovators.edit');
        Route::put('/innovators/{item}', [AdminInnovatorController::class, 'update'])->name('innovators.update');
        Route::delete('/innovators/{item}', [AdminInnovatorController::class, 'destroy'])->name('innovators.destroy');
        Route::patch('/innovators/{item}/status', [AdminInnovatorController::class, 'updateStatus'])->name('innovators.status');

        Route::get('/innovator-of-the-month', function () {
            return redirect()->route('admin.innovators.index');
        })->name('innovator_of_month.index');

        Route::get('/innovator-of-the-month/create', function () {
            return redirect()->route('admin.innovators.create');
        })->name('innovator_of_month.create');

        Route::get('/innovation-rankings', [AdminInnovationRankingController::class, 'index'])->name('innovation_rankings.index');
Route::get('/innovation-rankings/create', [AdminInnovationRankingController::class, 'create'])->name('innovation_rankings.create');
Route::post('/innovation-rankings', [AdminInnovationRankingController::class, 'store'])->name('innovation_rankings.store');
Route::get('/innovation-rankings/{ranking}/edit', [AdminInnovationRankingController::class, 'edit'])->name('innovation_rankings.edit');
Route::put('/innovation-rankings/{ranking}', [AdminInnovationRankingController::class, 'update'])->name('innovation_rankings.update');
Route::delete('/innovation-rankings/{ranking}', [AdminInnovationRankingController::class, 'destroy'])->name('innovation_rankings.destroy');
Route::patch('/innovation-rankings/{ranking}/status', [AdminInnovationRankingController::class, 'updateStatus'])->name('innovation_rankings.status');
    });
});