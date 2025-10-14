<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService {
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('TMDB_TOKEN');
        $this->baseUrl = 'https://api.themoviedb.org/3';
    }

    public function getTrendingAll($time_window = 'day') {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/trending/all/{$time_window}");
        return $response->json()['results'] ?? [];
    }

    public function getUpcomingMovies() {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/movie/upcoming?apiKey&region=ID");
        return $response->json()['results'] ?? []; 
    }

    public function getPopularMovies() {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/movie/popular?apiKey&region=ID");
        return $response->json()['results'] ?? [];
    }

    public function getMovieDetails($id) {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/movie/{$id}");
        return $response->successful() ? $response->json() : null;
    }

    public function getMovieWatchProviders($id) {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/movie/{$id}/watch/providers");
        return $response->successful() ? $response->json() : null;
    }

    public function getMovieCast($id) {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/movie/{$id}/credits");
        return $response->successful() ? $response->json(): null;
    }

    public function getMovieRecommendations($id) {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/movie/{$id}/recommendations");
        return $response->json()['results'] ?? [];
    }

    public function randomBackdrop() {

        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/trending/all/day");

        $movies = collect($response->json('results') ?? []);

        $backdrops = $movies->pluck('backdrop_path')->filter()->values();

        if ($backdrops->isEmpty()) {
            return null;
        }

        $randomBackdrop = $backdrops->random();
        return 'https://image.tmdb.org/t/p/original' . $randomBackdrop;
    }

    // TV Function

    public function getTvDetails($id) {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/tv/{$id}");
        return $response->successful() ? $response->json() : null;
    }

    public function getTvWatchProviders($id) {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/tv/{$id}/watch/providers");
        return $response->successful() ? $response->json() : null;
    }

    public function getTvCast($id) {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/tv/{$id}/aggregate_credits");
        return $response->successful() ? $response->json(): null;
    }

    public function getTvRecommendations($id) {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/tv/{$id}/recommendations");
        return $response->json()['results'] ?? [];
    }

    public function getTvTopRated() {
        $response = Http::withToken($this->apiKey)
            ->get("{$this->baseUrl}/tv/top_rated");
        return $response->json()['results'] ?? [];
    }
}