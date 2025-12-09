<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\WatchlistController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', [HomeController::class, 'index']);

Route::get('/discover/movies', [MovieController::class, 'index'])->name('movies.discover');

Route::get('/discover/tv', [TvController::class, 'index'])->name('tv.discover');

Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.detail');

Route::get('/tv/{id}', [TvController::class, 'show'])->name('tv.detail');

Route::get('/api/search', [SearchController::class, 'index'])->name('search');

Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');
Route::get('/watchlists', [WatchlistController::class, 'index'])->name('watchlists.index');

Route::middleware('auth')->group(function () {
    Route::post('/watchlists/toggle', [WatchlistController::class, 'toggle']);
    Route::post('/favourites/toggle', [FavouriteController::class, 'toggle']);
});

Route::get('/api', [ApiController::class, 'fetchMovies']);

Route::middleware(['auth'])->group(function () {
    // Halaman Form Review (Menerima tipe dan ID item)
    Route::get('/reviews/create/{type}/{id}', [ReviewController::class, 'create'])->name('reviews.create');
    
    // Proses Simpan
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Halaman Detail Review (Bisa diakses publik)
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

Route::post('/reviews/{review}/like', [App\Http\Controllers\ReviewController::class, 'toggleLike'])
    ->name('reviews.like')->middleware('auth');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('profile.settings');
    Route::post('/settings/update', [DashboardController::class, 'updateProfile'])->name('dashboard.update');
    Route::post('/settings/password', [DashboardController::class, 'updatePassword'])->name('dashboard.password');
});
