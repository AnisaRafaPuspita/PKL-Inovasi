<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InnovationController;
use App\Http\Controllers\InnovatorOfMonthController;
use App\Http\Controllers\AdminAuthController;


/*
|--------------------------------------------------------------------------
| Web Routes (Public)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/selengkapnya', [HomeController::class, 'about'])
    ->name('about');

/* ================= INNOVATIONS ================= */

Route::get('/innovations', [InnovationController::class, 'index'])
    ->name('innovations.index');

Route::get('/innovations/create', [InnovationController::class, 'create'])
    ->name('innovations.create');

Route::post('/innovations', [InnovationController::class, 'store'])
    ->name('innovations.store');

Route::get('/innovations/{innovation}', [InnovationController::class, 'show'])
    ->name('innovations.show');

/* ================= INNOVATOR OF THE MONTH ================= */

Route::get('/inovator-of-the-month', [InnovatorOfMonthController::class, 'show'])
    ->name('innovator-month.show');

/* ================= ADMIN PLACEHOLDER ================= */

// biar navbar gak error (login dihandle admin team)
Route::get('/admin/login', fn () => 'Admin login page')
    ->name('admin.login');

/* ================= ADMIN LOGIN ================= */

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');


