<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;
use App\Models\Review;

class TvController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
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

        if (!$tv) {
            abort(404, 'Tv Series Not Found!');
        }

        return view('tv.detail', compact('tv', 'tvProviders', 'credits', 'recommendations'));
    }
}
