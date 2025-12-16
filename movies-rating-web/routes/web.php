<?php

use Illuminate\Support\Facades\Route;

// Import Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\WatchlistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================= 1. PUBLIC ROUTES (Bisa diakses tanpa login) =================

Route::get('/', [HomeController::class, 'index']);

// Discover & Details
Route::get('/discover/movies', [MovieController::class, 'index'])->name('movies.discover');
Route::get('/discover/tv', [TvController::class, 'index'])->name('tv.discover');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.detail');
Route::get('/tv/{id}', [TvController::class, 'show'])->name('tv.detail');

// API Internal (Untuk Javascript/AJAX di Frontend)
Route::get('/api', [ApiController::class, 'fetchMovies']);
Route::get('/api/search', [SearchController::class, 'index'])->name('search');
Route::get('/api/latest-reviews', [ReviewController::class, 'getLatestReviews']);

// Reviews (Read Only)
Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');

// Auth (Login & Register)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// ================= 2. PROTECTED ROUTES (Wajib Login) =================

Route::middleware(['auth'])->group(function () {
    
    // Auth Action
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- REVIEWS (CREATE & ACTION) ---
    // Pastikan link di tombol view Anda mengarah ke route ini: 'reviews.create'
    Route::get('/reviews/create/{type}/{id}', [ReviewController::class, 'create'])->name('reviews.create'); 
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/like', [ReviewController::class, 'toggleLike'])->name('reviews.like');

    // --- USER LISTS (Watchlist & Favourites) ---
    // Note: Jika halaman index butuh login, pindahkan route get index ke sini. 
    // Jika bisa dilihat publik, biarkan di luar.
    Route::post('/watchlists/toggle', [WatchlistController::class, 'toggle']);
    Route::post('/favourites/toggle', [FavouriteController::class, 'toggle']);

    // --- DASHBOARD & SETTINGS ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('profile.settings');
    Route::post('/settings/update', [DashboardController::class, 'updateProfile'])->name('dashboard.update');
    Route::post('/settings/password', [DashboardController::class, 'updatePassword'])->name('dashboard.password');
});

// ================= 3. OPTIONAL: PUBLIC LISTS =================
// (Taruh di sini jika list favorit bisa dilihat orang lain tanpa login)
Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');
Route::get('/watchlists', [WatchlistController::class, 'index'])->name('watchlists.index');