<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use App\Models\Review;

class ApiController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    public function fetchMovies()
    {
        $trending = $this->tmdb->getTrendingAll('day');
        $popularMovies = $this->tmdb->getPopularMovies();
        $randomBackdrop = $this->tmdb->randomBackdrop();
        $TvTopRated = $this->tmdb->getTvTopRated();
        $latestReviews = Review::with('user') // Eager load user agar tidak N+1 problem
                        ->latest()
                        ->take(10) // Ambil 10 review terakhir
                        ->get();

        return response()->json([
            'trending' => $trending,
            'popularMovies' => $popularMovies,
            'randomBackdrop' => $randomBackdrop,
            'tvTopRated' => $TvTopRated,
            'latestReviews' => $latestReviews
        ]);
    }
}
