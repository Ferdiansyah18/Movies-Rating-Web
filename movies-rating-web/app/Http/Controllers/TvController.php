<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Favourite;
use App\Models\Watchlist;

class TvController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    public function index(Request $request) {
        $genreId = $request->query('genre');
        $page = $request->query('page', 1);

        $discoverTv = $this->tmdb->getDiscoverTv($genreId, $page);
        $genres = $this->tmdb->getGenresTv();

        return view('tv.discover', compact('discoverTv', 'genres', 'genreId'));
    }

    public function show($id) {
        $tv = $this->tmdb->getTvDetails($id);

        $tvProviders = $this->tmdb->getTvWatchProviders($id);

        $credits = $this->tmdb->getTvCast($id);

        $recommendations = $this->tmdb->getTvRecommendations($id);

        $reviews = Review::where('item_id', $id)
            ->where('item_type', 'movie')
            ->with('user') // <— ini penting banget
            ->latest()
            ->get();

        
    $userId = auth()->id();

    // Cek apakah sudah favourite / watchlist
    $favourite = Favourite::where('user_id', $userId)
                          ->where('tmdb_id', $id)
                          ->where('type', 'tv')
                          ->exists();

    $watchlist = Watchlist::where('user_id', $userId)
                          ->where('tmdb_id', $id)
                          ->where('type', 'tv')
                          ->exists();

        if (!$tv) {
            abort(404, 'Tv Series Not Found!');
        }

        return view('tv.detail', compact('tv', 'tvProviders', 'credits', 'recommendations', 'favourite', 'watchlist'));
    }
}
