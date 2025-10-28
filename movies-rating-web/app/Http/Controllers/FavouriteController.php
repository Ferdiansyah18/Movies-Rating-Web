<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favourite;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = Favourite::where('user_id', auth()->id())->get();
        return view('favourites.index', compact('favourites'));
    }

    public function toggle(Request $request)
    {
        $userId = auth()->id();

        // Cek apakah item sudah ada di favourites
        $favourite = Favourite::where('user_id', $userId)
            ->where('tmdb_id', $request->tmdb_id)
            ->where('type', $request->type)
            ->first();

        if ($favourite) {
            $favourite->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
            ]);
        } else {
            Favourite::create([
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
