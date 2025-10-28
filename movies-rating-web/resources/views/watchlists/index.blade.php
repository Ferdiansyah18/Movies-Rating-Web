<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Watchlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

@php
use App\Models\Watchlist;
$watchlists = Watchlist::where('user_id', auth()->id())->get();
@endphp

<x-navbar textColor="text-dark"/>

<div class="container py-5">
    <h1 class="mb-4">My Watchlist</h1>

    @if($watchlists->isEmpty())
        <p>You don't have any movies or series in your watchlist yet.</p>
    @else
        <div class="row">
            @foreach($watchlists as $item)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        {{-- Poster --}}
                        <img src="{{ $item->poster_path ? 'https://image.tmdb.org/t/p/w500'.$item->poster_path : asset('images/default-poster.png') }}" 
                             class="card-img-top" 
                             alt="{{ $item->title }}">

                        <div class="card-body d-flex flex-column">
                            {{-- Title --}}
                            <h5 class="card-title">{{ $item->title }}</h5>

                            {{-- Type --}}
                            <p class="card-text">
                                {{ $item->type === 'movie' ? 'Movie' : 'Series' }}
                            </p>

                            {{-- View Details --}}
                            <a href="{{ $item->type === 'movie' ? url('/movie/'.$item->tmdb_id) : url('/tv/'.$item->tmdb_id) }}" 
                               class="btn btn-primary btn-sm mb-2">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

</body>
</html>
