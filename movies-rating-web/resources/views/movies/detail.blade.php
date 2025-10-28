<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] }} | CinePals</title>
    <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body style="overflow-x: hidden;">

    <x-navbar textColor="text-dark"/>

    {{-- Background Gradient Overlay --}}
    <div class="container position-absolute top-0 start-50 translate-middle-x w-100 h-100"
         style="max-height: 57vh; pointer-events: none;
                background: linear-gradient(to right, white 0%, transparent 10%, transparent 70%, white 100%), 
                            linear-gradient(to left, white 0%, transparent 10%, transparent 70%, white 100%);">
    </div>

    {{-- Backdrop Section --}}
    <div class="container">
        @if (!empty($movie['backdrop_path']))
            <img src="https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}" 
                 alt="{{ $movie['title'] }}" 
                 class="w-100" style="object-fit: cover; max-height: 50vh;">
        @else
            <div class="w-100 d-flex justify-content-center align-items-center" 
                 style="background-color: #ccc; height: 50vh;">
                <span class="text-muted fs-4">Image Not Available</span>
            </div>
        @endif
    </div>

    <div class="container z-2 position-relative">
        <div class="row flex-column flex-md-row g-5">

            {{-- Poster --}}
            <div class="container col-5 col-md-3 col-xl-2">
                <div class="card position-sticky border-0" style="top: 80px;">
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                         alt="{{ $movie['title'] }}" class="card-img-top mb-3">
                    <div>
                        <ul class="list-inline d-none d-md-flex flex-column gap-3">
                            @if($movie['original_language'] !== 'en')
                                <li class="list-inline-item">
                                    <h6>Original Name:</h6> {{ $movie['original_title'] }}
                                </li>
                            @endif
                            <li>
                                <h6>Duration:</h6>
                                <span>{{ $movie['runtime'] }} Min</span>
                            </li>
                            <li>
                                <h6>Genres:</h6>
                                @foreach($movie['genres'] as $genre)
                                    <span class="badge bg-secondary">{{ $genre['name'] }}</span>
                                @endforeach
                            </li>
                            <li>
                                <h6>Release Date:</h6>
                                <span>{{ $movie['release_date'] }}</span>
                            </li>
                            <li>
                                <h6>Status:</h6>
                                <span>{{ $movie['status'] }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Movie Details --}}
            <div class="container col-10 col-md-9 mt-md-5 pt-3 ps-md-5 ps-xl-0">
                <div class="row align-items-center">
                    <h1 class="fw-bold col-9">{{ $movie['title'] }}</h1>
                    <div class="col-3 d-flex justify-content-center gap-2">
                        <!-- Favourites -->
                        <button id="favouriteBtn" 
                                class="btn d-flex align-items-center gap-2 {{ $favourite ? 'btn-danger' : 'btn-outline-danger' }}"
                                data-id="{{ $movie['id'] }}"
                                data-title="{{ $movie['title'] }}"
                                data-poster="{{ $movie['poster_path'] }}"
                                data-type="movie">
                            <i class="bi {{ $favourite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                        </button>

                        <!-- Watchlist -->
                        <button id="watchlistBtn" 
                                class="btn d-flex align-items-center gap-2 {{ $watchlist ? 'btn-warning' : 'btn-outline-warning' }}"
                                data-id="{{ $movie['id'] }}"
                                data-title="{{ $movie['title'] }}"
                                data-poster="{{ $movie['poster_path'] }}"
                                data-type="movie">
                            <i class="bi {{ $watchlist ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                        </button>
                    </div>
                </div>

                @if (!empty($movie['tagline']))
                    <h5 class="text-muted fst-italic">{{ $movie['tagline'] }}</h5>
                @endif

                <p class="mt-3">{{ $movie['overview'] }}</p>

                {{-- Metadata --}}
                <ul class="list-inline d-flex d-md-none gap-3 mt-3">
                    @if($movie['original_language'] !== 'en')
                        <li class="list-inline-item">
                            <h6>Original Name:</h6> {{ $movie['original_title'] }}
                        </li>
                    @endif

                    @if(!empty($movie['runtime']))
                        <li>
                            <h6>Duration:</h6>
                            <span>{{ $movie['runtime'] }} Min</span>
                        </li>
                    @endif

                    @if(!empty($movie['genres']))
                        <li class="list-inline-item">
                            <h6>Genres:</h6>
                            @foreach($movie['genres'] as $genre)
                                <span class="badge bg-secondary">{{ $genre['name'] }}</span>
                            @endforeach
                        </li>
                    @endif

                    @if(!empty($movie['first_air_date']))
                        <li class="list-inline-item">
                            <h6>Release Date:</h6> {{ $movie['first_air_date'] }}
                        </li>
                    @endif

                    @if(!empty($movie['status']))
                        <li class="list-inline-item">
                            <h6>Status:</h6> {{ $movie['status'] }}
                        </li>
                    @endif
                </ul>

                {{-- Watch Providers --}}
                <h6 class="mt-3">Watch It Online</h6>
                @if (isset($movieProviders['results']['ID']['flatrate']))
                    <div class="d-flex flex-wrap mt-3 gap-4">
                        @foreach ($movieProviders['results']['ID']['flatrate'] as $provider)
                            <div class="text-center d-flex flex-column align-items-center gap-2">
                                <img src="https://image.tmdb.org/t/p/w500{{ $provider['logo_path'] }}" 
                                     alt="{{ $provider['provider_name'] }}" class="rounded" 
                                     style="height: 40px; width: 40px;">
                                <p class="mb-0 small">{{ $provider['provider_name'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mt-5 text-center">You can't watch it online yet</p>
                @endif

                {{-- Cast Section --}}
                <div class="mt-3">
                    <h6>Cast</h6>
                    @if (!empty($credits['cast']))
                        <div class="d-flex overflow-auto flex-nowrap gap-3 pb-2 mt-3">
                            @foreach($credits['cast'] as $cast)
                                <div class="card border-0" style="width: 120px; flex: 0 0 auto;">
                                    @if ($cast['profile_path'])
                                        <img src="https://image.tmdb.org/t/p/h632{{ $cast['profile_path'] }}" 
                                             class="card-img-top rounded" 
                                             alt="{{ $cast['name'] ?? $cast['original_name'] }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                             style="height:180px;">
                                            <span class="text-muted small">No Image</span>
                                        </div>
                                    @endif
                                    <div class="card-body p-2 text-center">
                                        <small class="card-title d-block text-truncate">{{ $cast['name'] }}</small>
                                        <small class="text-muted d-block text-truncate">{{ $cast['character'] ?? '' }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No Cast Available</p>
                    @endif
                </div>

                {{-- Reviews Section --}}
                <div class="d-flex flex-column mt-4">
                    <h6 class="fw-semibold">Reviews</h6>

                    @php
                        use App\Models\Review;
                        $reviews = Review::where('item_id', $movie['id'])
                                        ->where('item_type', 'movie')
                                        ->with('user')
                                        ->latest()
                                        ->get();
                    @endphp

                    {{-- Display Existing Reviews --}}
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
                            <input type="hidden" name="item_id" value="{{ $movie['id'] }}">
                            <input type="hidden" name="item_type" value="movie">
                            <textarea name="comment" class="form-control" rows="3" placeholder="Write your review..."></textarea>
                            <button type="submit" class="btn btn-primary mt-2">Submit Review</button>
                        </form>
                    @else
                        <p class="text-muted text-center mt-3">
                            <a href="{{ route('login') }}">Login</a> untuk menulis review.
                        </p>
                    @endauth
                </div>

                {{-- Recommendations --}}
                <div class="mt-5 mb-5">
                    <h6>Recommendations</h6>
                    @if (!empty($recommendations))
                        <div class="d-flex justify-content-start gap-3 overflow-auto mt-3">
                            @foreach($recommendations as $recommendation)
                                @if ($loop->iteration <= 8)
                                    <a href="{{ route('movies.detail', $recommendation['id']) }}" class="text-decoration-none">
                                        <div class="card">
                                            @if(!empty($recommendation['poster_path']))
                                                <img src="https://image.tmdb.org/t/p/w500/{{ $recommendation['poster_path'] }}"
                                                     alt="{{ $recommendation['title'] }}"
                                                     class="img-fluid rounded"
                                                     style="max-width: 175px; max-height:255px;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                     style="width:175px; height:255px;">
                                                    <span class="text-muted small text-center">{{ $recommendation['title'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mt-5 text-center">We can't get recommendations for this movie</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <x-footer/>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const showToast = (message, icon = 'success') => {
        Swal.fire({
            toast: true,
            position: 'top',
            icon: icon,
            title: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
        });
    };

    // === Favourite Button ===
    const favBtn = document.getElementById('favouriteBtn');
    favBtn.addEventListener('click', function() {
        const btn = this;

        fetch('/favourites/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tmdb_id: btn.dataset.id,
                title: btn.dataset.title,
                poster_path: btn.dataset.poster,
                type: btn.dataset.type,
            }),
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const isAdded = res.action === 'added';

                // Ubah tampilan tombol
                if (isAdded) {
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-danger');
                    btn.querySelector('i').classList.replace('bi-heart', 'bi-heart-fill');
                    showToast('Added to favourites ❤️');
                } else {
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                    btn.querySelector('i').classList.replace('bi-heart-fill', 'bi-heart');
                    showToast('Removed from favourites 💔', 'error');
                }
            }
        });
    });

    // === Watchlist Button ===
    const watchBtn = document.getElementById('watchlistBtn');
    watchBtn.addEventListener('click', function() {
        const btn = this;

        fetch('/watchlists/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tmdb_id: btn.dataset.id,
                title: btn.dataset.title,
                poster_path: btn.dataset.poster,
                type: btn.dataset.type,
            }),
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const isAdded = res.action === 'added';

                if (isAdded) {
                    btn.classList.remove('btn-outline-warning');
                    btn.classList.add('btn-warning');
                    btn.querySelector('i').classList.replace('bi-bookmark', 'bi-bookmark-fill');
                    showToast('Added to watchlist 📚');
                } else {
                    btn.classList.remove('btn-warning');
                    btn.classList.add('btn-outline-warning');
                    btn.querySelector('i').classList.replace('bi-bookmark-fill', 'bi-bookmark');
                    showToast('Removed from watchlist 🗑️', 'error');
                }
            }
        });
    });
});
</script>


</body>
</html>
