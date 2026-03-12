<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InnovationController;
use App\Http\Controllers\InnovatorOfMonthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\InnovatorController;
use App\Http\Controllers\StatistikController;



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

Route::get('/admin/login', fn () => 'Admin login page')
    ->name('admin.login');

/* ================= ADMIN LOGIN ================= */

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/* ========== RANGKINK =========== */ 
Route::get('/rankings/{ranking}', [RankingController::class, 'show'])
    ->name('rankings.show');

/* ===== INNOVATOR */
Route::get('/innovators', [InnovatorController::class, 'index'])
    ->name('innovators.index');

Route::get('/innovators/{innovator}', [InnovatorController::class, 'show'])
    ->name('innovators.show');


/* ==== NAVBAR ROUTES ==== */

Route::get('/inovasi', [InnovationController::class, 'index'])
    ->name('inovasi.index');

Route::get('/inovator', [InnovatorController::class, 'index'])
    ->name('inovator.index');

Route::get('/ranking', [RankingController::class, 'index'])
    ->name('ranking.index');

Route::get('/statistik', [StatistikController::class, 'index'])
    ->name('statistik.index');



