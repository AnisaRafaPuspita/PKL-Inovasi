<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InnovationController;
use App\Http\Controllers\InnovatorOfMonthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/selengkapnya', [HomeController::class, 'about'])->name('about');

Route::get('/innovations/create', [InnovationController::class, 'create'])->name('innovations.create');
Route::post('/innovations', [InnovationController::class, 'store'])->name('innovations.store');

Route::get('/innovations', [InnovationController::class, 'index'])->name('innovations.index');
Route::get('/innovations/{innovation}', [InnovationController::class, 'show'])->name('innovations.show');

// upload page nanti untuk innovator (kalau belum ada bisa return view placeholder)
Route::get('/innovations-upload', [InnovationController::class, 'create'])->name('innovations.create');

// inovator of the month detail
Route::get('/inovator-of-the-month', [InnovatorOfMonthController::class, 'show'])->name('innovator-month.show');

// placeholder admin login route (biar navbar gak error)
Route::get('/admin/login', fn () => 'Admin login page (handled by admin team)')->name('admin.login');


