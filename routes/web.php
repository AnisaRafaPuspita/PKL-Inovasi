<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminInnovationController;
use App\Http\Controllers\AdminPermissionController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/innovations', [AdminInnovationController::class, 'index'])->name('innovations.index');
    Route::get('/innovations/create', [AdminInnovationController::class, 'create'])->name('innovations.create');
    Route::post('/innovations', [AdminInnovationController::class, 'store'])->name('innovations.store');
    Route::get('/innovations/{innovation}', [AdminInnovationController::class, 'show'])->name('innovations.show');
    Route::get('/innovations/{innovation}/edit', [AdminInnovationController::class, 'edit'])->name('innovations.edit');
    Route::put('/innovations/{innovation}', [AdminInnovationController::class, 'update'])->name('innovations.update');

    Route::get('permissions', [AdminPermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/{innovation}', [AdminPermissionController::class, 'show'])->name('permissions.show');
    Route::post('permissions/{innovation}/accept', [AdminPermissionController::class, 'accept'])->name('permissions.accept');
    Route::post('permissions/{innovation}/decline', [AdminPermissionController::class, 'decline'])->name('permissions.decline');
});
