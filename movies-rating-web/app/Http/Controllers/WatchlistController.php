<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Watchlist::where('user_id', auth()->id())->get();
        return view('watchlists.index', compact('watchlists'));
    }
    
    public function toggle(Request $request)
    {
        $userId = auth()->id();

        // Cek apakah item sudah ada di watchlist
        $watchlist = Watchlist::where('user_id', $userId)
            ->where('tmdb_id', $request->tmdb_id)
            ->where('type', $request->type)
            ->first();

        if ($watchlist) {
            $watchlist->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
            ]);
        } else {
            Watchlist::create([
                'user_id' => $userId,
                'tmdb_id' => $request->tmdb_id,
                'title' => $request->title,
                'poster_path' => $request->poster_path,
                'type' => $request->type,
            ]);

            return response()->json([
                'success' => true,
                'action' => 'added',
            ]);
        }
    }
}
