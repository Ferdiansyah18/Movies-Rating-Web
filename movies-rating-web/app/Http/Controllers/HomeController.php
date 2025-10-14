<?php

namespace App\Http\Controllers;

use App\Services\TmdbService;

class HomeController extends Controller
{
    protected $tmdb;

    public function __construct(TmdbService $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    public function index() // gabungan upcoming + popular
    {
        $upcomingMovies = $this->tmdb->getUpcomingMovies();

        // kirim keduanya ke view
        return view('home', compact('upcomingMovies'));
    }

}
