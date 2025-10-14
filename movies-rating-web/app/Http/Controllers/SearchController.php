<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([]);
        }

        // Panggil TMDB multi search
        $response = Http::withToken(env('TMDB_TOKEN'))
            ->get('https://api.themoviedb.org/3/search/multi', [
                'query' => $query,
                // language en-US supaya judulnya tetap original
                'language' => 'en-US',
                // include adult false supaya aman
                'include_adult' => false,
            ]);

        // Ambil results
        $results = collect($response->json('results') ?? [])
            ->filter(function ($item) {
                // filter hanya movie & tv
                return in_array($item['media_type'], ['movie', 'tv']);
            })
            ->take(10) // batasi maksimal 10
            ->map(function ($item) {
                return [
                    'id'         => $item['id'],
                    'title'      => $item['title'] ?? $item['name'], // movie: title, tv: name
                    'poster'     => $item['poster_path']
                        ? 'https://image.tmdb.org/t/p/w185'.$item['poster_path'] // lebih ringan
                        : null,
                    'media_type' => $item['media_type'],
                ];
            })
            ->values();

        return response()->json($results);
    }
}
