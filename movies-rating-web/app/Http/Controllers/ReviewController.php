<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create($type, $id)
    {
        // Validasi tipe item agar tidak error
        if (!in_array($type, ['movie', 'tv'])) {
            abort(404);
        }

        // Kita bisa fetch data film/tv dari TMDB disini jika ingin menampilkan judul film di form
        // Tapi untuk simpelnya, kita pass ID-nya saja view
        return view('reviews.create', compact('type', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:10', // Asumsi skala 1-10
            'comment' => 'required|string',
            'item_id' => 'required',
            'item_type' => 'required',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'item_id' => $request->item_id,
            'item_type' => $request->item_type,
            'title' => $request->title,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Redirect kembali ke halaman detail film/tv
        if($request->item_type == 'movie'){
            return redirect()->route('movies.detail', $request->item_id)->with('success', 'Review posted!');
        } else {
            return redirect()->route('tv.detail', $request->item_id)->with('success', 'Review posted!');
        }
    }

    public function show(Review $review)
    {
        // Load user data untuk ditampilkan
        $review->load('user');
        return view('reviews.show', compact('review'));
    }

    public function toggleLike(Review $review)
{
    // Toggle: Kalau sudah like jadi unlike, kalau belum jadi like
    $review->likes()->toggle(auth()->id());

    return response()->json([
        'success' => true,
        'count' => $review->likes()->count(),
        'liked' => $review->isLikedBy(auth()->user())
    ]);
}
}
