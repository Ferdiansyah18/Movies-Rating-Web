<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // PERBAIKAN 1: Ganti 'q' menjadi 'query' agar sesuai dengan fetch frontend
        $query = $request->get('query');

        if (!$query) {
            return response()->json([]);
        }

        try {
            // Panggil TMDB multi search
            $response = Http::withToken(env('TMDB_TOKEN'))
                ->get('https://api.themoviedb.org/3/search/multi', [
                    'query' => $query,
                    'language' => 'en-US',
                    'include_adult' => false,
                ]);

            if ($response->failed()) {
                return response()->json([], 500); // Return error jika TMDB gagal
            }

            // Ambil results
            $results = collect($response->json('results') ?? [])
                ->filter(function ($item) {
                    // Filter hanya movie & tv, dan pastikan ada poster/backdrop agar tampilan bagus (opsional)
                    return isset($item['media_type']) && in_array($item['media_type'], ['movie', 'tv']);
                })
                ->take(10) // Batasi 10 hasil
                ->values(); // Reset array keys agar menjadi JSON array yang valid

            // PERBAIKAN 2: Jangan di-map/ubah strukturnya.
            // Biarkan return data mentah (raw) agar logic JS di frontend (item.poster_path, item.release_date) tetap jalan.
            
            return response()->json($results);

        } catch (\Exception $e) {
            // Log error jika perlu
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}