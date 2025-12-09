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
    <h2 class="my-4">My Watchlist</h1>

    @if($watchlists->isEmpty())
        <p>You don't have any movies or series in your watchlist yet.</p>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach ($watchlists as $item)
                <a href="{{ $item->type === 'movie' ? url('/movie/'.$item->tmdb_id) : url('/tv/'.$item->tmdb_id) }}" class="row border-bottom pb-3 text-decoration-none text-dark">
                    <div class="col-2">
                        <img class="col-10 rounded object-fit" src="{{ 'https://image.tmdb.org/t/p/w500'.$item->poster_path }}"" alt="$item->title">
                    </div>
                    <div class="col-10">
                        <div class="d-flex justify-content-start flex-column">
                            <h3>{{ $item->title }}</h5>
                            <h5 class="text-muted fst-italic">{{ $item->tagline }}</h5>
                            <p>{{ $item->overview ?? 'no overview available' }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
    
</div>

</body>
</html>
