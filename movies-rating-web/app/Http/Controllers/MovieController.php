<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use App\Models\Review;

class MovieController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    public function show($id)
    {
        $movie = $this->tmdb->getMovieDetails($id);

        $movieProviders = $this->tmdb->getMovieWatchProviders($id);

        $credits = $this->tmdb->getMovieCast($id);

        $recommendations = $this->tmdb->getMovieRecommendations($id);

        $reviews = Review::where('item_id', $id)
            ->where('item_type', 'movie')
            ->with('user') // <— ini penting banget
            ->latest()
            ->get();

        
        if (!$movie) {
            abort(404, 'Movie Not Found!');
        }

        return view('movies.detail', compact('movie', 'movieProviders', 'credits', 'recommendations'));
    }
}
