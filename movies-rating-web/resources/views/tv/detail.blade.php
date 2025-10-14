<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tv['name'] }} | LuminaFlick</title>
</head>
<body style="overflow-x: hidden;">

    <x-navbar textColor="text-dark" />

    {{-- Gradient overlay on top of image --}}
    <div class="container position-absolute top-0 start-50 translate-middle-x w-100 h-100" 
         style="max-height: 57vh; pointer-events: none;
         background: linear-gradient(to right, white 0%, transparent 10%, transparent 70%, white 100%), 
                     linear-gradient(to left, white 0%, transparent 10%, transparent 70%, white 100%);">
    </div>

    {{-- TV Backdrop --}}
    <div class="container">
        @if (!empty($tv['backdrop_path']))
            <img src="https://image.tmdb.org/t/p/original{{ $tv['backdrop_path'] }}"
                 alt="{{ $tv['name'] }}" 
                 class="w-100" 
                 style="object-fit: cover; max-height: 50vh;">
        @else
            <div class="w-100 d-flex justify-content-center align-items-center"
                 style="background-color: #ccc; height: 50vh;">
                <span class="text-muted fs-4">Image Not Available</span>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <div class="container z-2 position-relative">
        <div class="row flex-column flex-md-row g-5">

            {{-- Poster --}}
            <div class="container col-5 col-md-3 col-xl-2 text-center">
                <div class="card">
                    <img src="https://image.tmdb.org/t/p/original{{ $tv['poster_path'] }}"
                         alt="{{ $tv['name'] }}" 
                         class="card-img-top">
                </div>
            </div>

            {{-- TV Info --}}
            <div class="container col-10 col-md-9 mt-md-5 pt-3 ps-md-5 ps-xl-0">
                <h1 class="fw-bold">{{ $tv['name'] }}</h1>

                @if (!empty($tv['tagline']))
                    <h5 class="text-muted fst-italic">{{ $tv['tagline'] }}</h5>
                @endif

                <p class="mt-3">{{ $tv['overview'] }}</p>

                {{-- Genres & Status --}}
                <ul class="list-inline mt-3">
                    @if(!empty($tv['genres']))
                        <li class="list-inline-item">
                            <h6>Genres:</h6>
                            @foreach($tv['genres'] as $genre)
                                <span class="badge bg-secondary">{{ $genre['name'] }}</span>
                            @endforeach
                        </li>
                    @endif

                    @if(!empty($tv['status']))
                        <li class="list-inline-item">
                            <h6>Status:</h6> {{ $tv['status'] }}
                        </li>
                    @endif
                </ul>

                {{-- Providers --}}
                <h6 class="mt-3">Watch It Online</h6>
                @if (isset($tvProviders['results']['ID']['flatrate']))
                    <div class="d-flex flex-wrap mt-3 gap-4">
                        @foreach ($tvProviders['results']['ID']['flatrate'] as $provider)
                            <div class="text-center d-flex flex-column align-items-center gap-2">
                                <img src="https://image.tmdb.org/t/p/original{{ $provider['logo_path'] }}"
                                     alt="{{ $provider['provider_name'] }}" 
                                     class="rounded" 
                                     style="height: 40px; width: 40px;">
                                <p class="mb-0 small">{{ $provider['provider_name'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mt-5 text-center">You can't watch it online yet</p>
                @endif

                {{-- Cast --}}
                <div class="mt-3">
                    <h6>Cast</h6>
                    @if (!empty($credits['cast']))
                        <div class="d-flex overflow-auto flex-nowrap gap-3 pb-2 mt-3">
                            @foreach($credits['cast'] as $cast)
                                <div class="card border-0" style="width: 120px; flex: 0 0 auto;">
                                    @if ($cast['profile_path'])
                                        <img src="https://image.tmdb.org/t/p/original{{ $cast['profile_path'] }}" 
                                             class="card-img-top rounded" 
                                             alt="{{ $cast['name'] ?? $cast['original_name'] }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                             style="height:180px;">
                                            <span class="text-muted small">No Image</span>
                                        </div>
                                    @endif

                                    <div class="card-body p-2 text-center">
                                        <small class="card-title d-block text-truncate" style="max-width: 100%;">
                                            {{ $cast['name'] }}
                                        </small>
                                        <small class="text-muted d-block text-truncate" style="max-width: 100%;">
                                            @if (!empty($cast['roles']))
                                                {{ collect($cast['roles'])->pluck('character')->join(', ') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No Cast Available</p>
                    @endif
                </div>

                {{-- Reviews --}}
                <div class="d-flex flex-column mt-4">
                    <h6 class="fw-semibold">Reviews</h6>

                    @php
                        use App\Models\Review;
                        $reviews = Review::where('item_id', $tv['id'])
                                        ->where('item_type', 'tv')
                                        ->with('user')
                                        ->latest()
                                        ->get();
                    @endphp

                    <div class="mb-4 mt-2">
                        @forelse($reviews as $review)
                            <div class="d-flex align-items-start mb-3">
                                <img 
                                    src="{{ $review->user && $review->user->profile_picture
                                                ? asset('storage/' . $review->user->profile_picture)
                                                : asset('images/default-avatar.png') }}" 
                                    class="rounded-circle object-fit-cover me-3" width="50" height="50" alt="User profile">
                                <div>
                                    <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                                    <p class="mb-1 text-muted" style="font-size: 0.9rem;">
                                        {{ $review->created_at->diffForHumans() }}
                                    </p>
                                    <div class="bg-light p-3 rounded shadow-sm">
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center">No reviews yet. Be the first to write one!</p>
                        @endforelse
                    </div>

                    {{-- Submit Review Form --}}
                    @auth
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $tv['id'] }}">
                        <input type="hidden" name="item_type" value="tv">
                        <textarea name="comment" class="form-control" rows="3" placeholder="Write your review..."></textarea>
                        <button type="submit" class="btn btn-primary mt-2">Submit Review</button>
                    </form>
                    @else
                        <p class="text-muted mt-3">
                            <a href="{{ route('login') }}">Login</a> untuk menulis review.
                        </p>
                    @endauth
                </div>

                {{-- Recommendations --}}
                <div class="mt-5 mb-5">
                    <h6>Recommendations</h6>
                    @if (!empty($recommendations))
                        <div class="d-flex justify-content-start gap-3 overflow-auto mt-3 pb-3">
                            @foreach($recommendations as $recommendation)
                                @if ($loop->iteration <= 8)
                                    <a href="{{ route('tv.detail', $recommendation['id']) }}" class="text-decoration-none">
                                        <div class="card">
                                            @if(!empty($recommendation['poster_path']))
                                                <img src="https://image.tmdb.org/t/p/original/{{ $recommendation['poster_path'] }}"
                                                     alt="{{ $recommendation['name'] }}"
                                                     class="img-fluid rounded"
                                                     style="max-width: 175px; max-height:255px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                     style="width:175px; height:255px;">
                                                    <span class="text-muted small text-center">
                                                        {{ $recommendation['name'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mt-5 text-center">We can't get recommendations for this show</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <x-footer />

</body>
</html>
