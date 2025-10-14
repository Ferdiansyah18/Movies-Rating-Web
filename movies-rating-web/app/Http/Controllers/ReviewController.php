<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
        'item_id' => 'required|integer',
        'item_type' => 'required|in:movie,tv',
    ]);

    Review::create([
        'user_id' => auth()->id(),
        'item_id' => $request->item_id,
        'item_type' => $request->item_type,
        'comment' => $request->comment,
    ]);

    return back()->with('success', 'Review submitted!');
}
}
