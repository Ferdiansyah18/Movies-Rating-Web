<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.detail');

Route::get('/tv/{id}', [TvController::class, 'show'])->name('tv.detail');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/api', [ApiController::class, 'fetchMovies']);

Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/update', [ProfileController::class, 'update'])->name('dashboard.update');
    Route::post('/dashboard/password', [ProfileController::class, 'updatePassword'])->name('dashboard.password');
});
