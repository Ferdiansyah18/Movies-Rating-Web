<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;

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

        return response()->json([
            'trending' => $trending,
            'popularMovies' => $popularMovies,
            'randomBackdrop' => $randomBackdrop,
            'tvTopRated' => $TvTopRated,
        ]);
    }
}
