<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use Illuminate\Http\Request;
use App\Models\Review;

class MovieController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    public function index(Request $request) // ✅ perbaiki: tambahkan Request type hint
    {
        $genreId = $request->query('genre');
        $page = $request->query('page', 1);

        $discoverMovies = $this->tmdb->getDiscoverMovies($genreId, $page);
        $genres = $this->tmdb->getGenresMovies();

        return view('movies.discover', compact('discoverMovies', 'genres', 'genreId'));
    }

    public function show($id)
    {
        $movie = $this->tmdb->getMovieDetails($id);
        $movieProviders = $this->tmdb->getMovieWatchProviders($id);
        $credits = $this->tmdb->getMovieCast($id);
        $recommendations = $this->tmdb->getMovieRecommendations($id);

        $reviews = Review::where('item_id', $id)
            ->where('item_type', 'movie')
            ->with('user')
            ->latest()
            ->get();

        if (!$movie) {
            abort(404, 'Movie Not Found!');
        }

        return view('movies.detail', compact('movie', 'movieProviders', 'credits', 'recommendations', 'reviews'));
    }
}
