<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReviewController extends Controller
{
    /**
     * Mengembalikan daftar review terbaru dalam format JSON.
     * Digunakan oleh Javascript di Homepage.
     */
    public function getLatestReviews()
    {
        $reviews = Review::with('user')
            ->latest()
            ->take(15)
            ->get();

        return response()->json([
            'latestReviews' => $reviews
        ]);
    }

    /**
     * Menampilkan form pembuatan review.
     * Mengambil data film/tv dari TMDB terlebih dahulu untuk snapshot.
     */
public function create($type, $id)
{
    // 1. Validasi tipe
    if (!in_array($type, ['movie', 'tv'])) {
        abort(404);
    }

    // 2. Ambil Token
    $token = env('TMDB_TOKEN'); 

    // --- DEBUG 1: Cek apakah Token terbaca? ---
    if (empty($token)) {
        dd("STOP: Token TMDB tidak terbaca dari file .env. Pastikan namanya TMDB_TOKEN dan jalankan 'php artisan config:clear'");
    }

    // 3. Request ke TMDB
    $url = "https://api.themoviedb.org/3/{$type}/{$id}";
    
    $response = Http::withToken($token)->get($url); // Jika token Anda tipe Bearer (panjang)
    // ATAU: $response = Http::get($url, ['api_key' => $token]); // Jika token Anda tipe API Key (pendek)
    
    // --- DEBUG 2: Cek kenapa TMDB menolak? ---
    if ($response->failed()) {
        dd([
            'Pesan Error' => 'Request ke TMDB Gagal',
            'URL yang ditembak' => $url,
            'Status Code' => $response->status(), // 401 = Token Salah, 404 = ID Film Tidak Ada
            'Respon Asli TMDB' => $response->json() ?? $response->body()
        ]);
    }

    $item = $response->json();

    return view('reviews.create', compact('item', 'type'));
}

    /**
     * Menyimpan review baru ke database beserta snapshot data film.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validatedData = $request->validate([
            // Data Review User
            'title'   => 'required|string|max:255',
            'rating'  => 'required|integer|min:1|max:10',
            'comment' => 'required|string',
            
            // Data Snapshot (Hidden Inputs)
            'item_id'      => 'required',
            'item_type'    => 'required|in:movie,tv',
            'media_title'  => 'required|string',
            'media_poster' => 'nullable|string',
            'media_year'   => 'nullable|string',
        ]);

        // 2. Simpan ke Database
        // Cara Elegan: Membuat review langsung dari relasi user yg sedang login
        // Ini otomatis mengisi kolom 'user_id'
        $request->user()->reviews()->create($validatedData);

        // 3. Redirect Dinamis
        // Tentukan route tujuan berdasarkan tipe item (movies.detail atau tv.detail)
        $routeTarget = $request->item_type === 'movie' ? 'movies.detail' : 'tv.detail';

        return redirect()
            ->route($routeTarget, $request->item_id)
            ->with('success', 'Review posted successfully!');
    }

    /**
     * Menampilkan detail satu review.
     */
    public function show(Review $review)
    {
        // Eager loading user dan likes untuk performa
        $review->load(['user', 'likes']); 
        
        return view('reviews.show', compact('review'));
    }

    /**
     * Fitur Like/Unlike menggunakan AJAX.
     */
    public function toggleLike(Review $review)
    {
        $review->likes()->toggle(auth()->id());

        return response()->json([
            'success' => true,
            'count'   => $review->likes()->count(),
            'liked'   => $review->isLikedBy(auth()->user())
        ]);
    }
}