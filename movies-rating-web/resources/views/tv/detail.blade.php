<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tv['name'] }} | CinePals</title>
    <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <style>
        /* --- 1. SCROLL CONTAINER STYLING --- */
        .scroll-container::-webkit-scrollbar {
            height: 8px;
        }
        .scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .scroll-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .scroll-container {
            display: flex; /* Wajib flex untuk horizontal layout */
            overflow-x: auto;
            scroll-behavior: smooth;
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
            padding-top: 15px;
            padding-bottom: 15px;
            padding-left: 5px; 
            padding-right: 5px;
        }
        
        /* Hide scrollbar standard agar diganti tombol nav */
        .scroll-container::-webkit-scrollbar {
            display: none;
        }

        /* --- 2. CARD HOVER EFFECT (FIXED Z-INDEX) --- */
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease, z-index 0s; 
            display: block;
            position: relative; 
            z-index: 1;         
        }

        .hover-card:hover {
            transform: scale(1.05) translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
            z-index: 20; /* Naik ke atas overlay fade */
        }
        
        .hover-card img {
            border-radius: 8px; 
        }

        /* --- 3. NAVIGATION BUTTONS (Prev/Next) --- */
        .nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            flex-shrink: 0;
            z-index: 30; 
        }
        .nav-btn:hover {
            background-color: #f8f9fa;
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        /* --- 4. SCROLL FADE EFFECT --- */
        .scroll-wrapper {
            position: relative;
            overflow: hidden; 
        }
        
        .scroll-wrapper::before,
        .scroll-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 80px; 
            pointer-events: none; 
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
            z-index: 9999; 
        }

        /* Fade Left (White) */
        .scroll-wrapper::before {
            left: 0;
            background: linear-gradient(to right, #ffffff 20%, transparent 100%);
        }
        
        /* Fade Right (White) */
        .scroll-wrapper::after {
            right: 0;
            background: linear-gradient(to right, transparent 0%, #ffffff 80%);
        }

        /* Toggle Classes */
        .scroll-wrapper.show-shadow-left::before { opacity: 1; }
        .scroll-wrapper.show-shadow-right::after { opacity: 1; }

    </style>
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

            {{-- Poster (Static - No Hover Effect) --}}
            <div class="container col-5 col-md-3 col-xl-2">
                <div class="card position-sticky border-0" style="top: 80px;">
                    <img src="https://image.tmdb.org/t/p/w500{{ $tv['poster_path'] }}"
                         alt="{{ $tv['name'] }}" 
                         class="card-img-top mb-3 shadow rounded">
                    <div>
                        <ul class="list-inline d-none d-md-flex flex-column gap-3">
                            @if($tv['original_language'] !== 'en')
                                <li class="list-inline-item">
                                    <h6>Original Name:</h6> {{ $tv['original_name'] }}
                                </li>
                            @endif
                            <li>
                                <h6>Genres:</h6>
                                @foreach($tv['genres'] as $genre)
                                    <span class="badge bg-secondary">{{ $genre['name'] }}</span>
                                @endforeach
                            </li>
                            <li>
                                <h6>Release Date:</h6>
                                <span>{{ $tv['first_air_date'] }}</span>
                            </li>
                            <li>
                                <h6>Status:</h6>
                                <span>{{ $tv['status'] }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- TV Info --}}
            <div class="container col-10 col-md-9 mt-md-5 pt-3 ps-md-5 ps-xl-0">
                <div class="row align-items-center">
                    <h1 class="fw-bold col-9">{{ $tv['name'] }}</h1>
                    <div class="col-3 d-flex justify-content-center gap-2">
                        @auth
                            <button id="favouriteBtn" 
                                    class="btn d-flex align-items-center gap-2 {{ $favourite ? 'btn-danger' : 'btn-outline-danger' }}"
                                    data-id="{{ $tv['id'] }}"
                                    data-title="{{ $tv['name'] }}"
                                    data-poster="{{ $tv['poster_path'] }}"
                                    data-overview="{{ $tv['overview'] }}"
                                    data-tagline="{{ $tv['tagline'] }}"
                                    data-type="tv">
                                <i class="bi {{ $favourite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>

                            <button id="watchlistBtn" 
                                    class="btn d-flex align-items-center gap-2 {{ $watchlist ? 'btn-warning' : 'btn-outline-warning' }}"
                                    data-id="{{ $tv['id'] }}"
                                    data-title="{{ $tv['name'] }}"
                                    data-poster="{{ $tv['poster_path'] }}"
                                    data-overview="{{ $tv['overview'] }}"
                                    data-tagline="{{ $tv['tagline'] }}"
                                    data-type="tv">
                                <i class="bi {{ $watchlist ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                            </button>
                        @else
                            <button class="btn btn-outline-danger d-flex align-items-center gap-2 login-required">
                                <i class="bi bi-heart"></i>
                            </button>

                            <button class="btn btn-outline-warning d-flex align-items-center gap-2 login-required">
                                <i class="bi bi-bookmark"></i>
                            </button>
                        @endauth
                    </div>
                </div>

                @if (!empty($tv['tagline']))
                    <h5 class="text-muted fst-italic">{{ $tv['tagline'] }}</h5>
                @endif

                <p class="mt-3">{{ $tv['overview'] }}</p>

                {{-- Genres & Status for mobile --}}
                <ul class="list-inline d-flex d-md-none gap-3 mt-3 overflow-auto">
                    @if(!empty($tv['first_air_date']))
                        <li class="list-inline-item">
                            <h6>Release Date:</h6> {{ $tv['first_air_date'] }}
                        </li>
                    @endif
                </ul>

                {{-- Providers --}}
                <h6 class="mt-3">Watch It Online</h6>
                @if (isset($tvProviders['results']['ID']['flatrate']))
                    <div class="d-flex flex-wrap mt-3 gap-4">
                        @foreach ($tvProviders['results']['ID']['flatrate'] as $provider)
                            <div class="text-center d-flex flex-column align-items-center gap-2">
                                <img src="https://image.tmdb.org/t/p/w500{{ $provider['logo_path'] }}"
                                     alt="{{ $provider['provider_name'] }}" 
                                     class="rounded hover-card shadow-sm" 
                                     style="height: 40px; width: 40px;">
                                <p class="mb-0 small">{{ $provider['provider_name'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mt-2">You can't watch it online yet</p>
                @endif

                {{-- ================= CAST SECTION (Horizontal Scroll + Shadow) ================= --}}
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Cast</h5>
                    @if (!empty($credits['cast']))
                        <div class="d-flex align-items-center">
                            
                            {{-- Prev Button --}}
                            <button class="nav-btn prev d-none d-md-flex me-3" id="castPrevBtn">
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            {{-- Wrapper Scroll --}}
                            <div id="castWrapper" class="scroll-wrapper flex-grow-1">
                                <div id="castSection" class="scroll-container gap-3">
                                    @foreach($credits['cast'] as $cast)
                                        <div class="card border-0 flex-shrink-0 hover-card shadow-sm" style="width: 120px;">
                                            @if ($cast['profile_path'])
                                                <img src="https://image.tmdb.org/t/p/h632{{ $cast['profile_path'] }}" 
                                                     class="card-img-top rounded-top" 
                                                     alt="{{ $cast['name'] ?? $cast['original_name'] }}"
                                                     style="height: 180px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded-top" 
                                                     style="height:180px;">
                                                    <span class="text-muted small">No Image</span>
                                                </div>
                                            @endif
                                            <div class="card-body p-2 text-center font-sm">
                                                <small class="card-title d-block text-truncate fw-bold mb-0">{{ $cast['name'] }}</small>
                                                <small class="text-muted d-block text-truncate" style="font-size: 0.8rem;">
                                                    @if (!empty($cast['roles']))
                                                        {{ collect($cast['roles'])->pluck('character')->join(', ') }}
                                                    @elseif(!empty($cast['character']))
                                                        {{ $cast['character'] }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Next Button --}}
                            <button class="nav-btn next d-none d-md-flex ms-3" id="castNextBtn">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    @else
                        <p class="text-muted">No Cast Available</p>
                    @endif
                </div>

                {{-- ================= REVIEWS SECTION (Vertical Stack) ================= --}}
<div class="d-flex flex-column mt-5 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">User Reviews</h4>
        
        @auth
            <a href="{{ route('reviews.create', ['type' => 'tv', 'id' => $tv['id']]) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                <i class="bi bi-pencil-square me-2"></i>Write a Review
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                Login to Review
            </a>
        @endauth
    </div>

    @php
        use App\Models\Review;
        $reviews = Review::where('item_id', $tv['id'])
                        ->where('item_type', 'tv')
                        ->with('user')
                        ->withCount('likes')
                        ->latest()
                        ->take(3)
                        ->get();
        
        $allReviewsCount = Review::where('item_id', $tv['id'])
                        ->where('item_type', 'tv')
                        ->count();
    @endphp

    @if($reviews->count() > 0)
        <div class="d-flex flex-column gap-3">
            @foreach($reviews as $review)
                <a href="{{ route('reviews.show', $review->id) }}" class="text-decoration-none text-dark d-block">
                    <div class="card shadow-sm border-0 hover-card p-3">
                        <div class="card-body p-0">
                            {{-- Header Rating & Tanggal --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-star-fill"></i> {{ $review->rating }}/10
                                    </span>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            </div>

                            <h5 class="card-title fw-bold">{{ $review->title }}</h5>
                            
                            <p class="card-text text-muted" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $review->comment }}
                            </p>

                            {{-- Footer Card (Avatar & Likes) --}}
                            <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-3">
                                <div class="d-flex align-items-center gap-2">
                                    
{{-- LOGIKA FOTO PROFIL --}}
@if(auth()->user()->profile_picture)
    {{-- 1. Jika user punya foto custom di storage --}}
    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
         alt="{{ auth()->user()->name }}" 
         class="rounded-circle object-fit-cover" 
         width="25" height="25">
@else
    {{-- 2. Jika user BELUM punya foto -> Pakai UI Avatars (Inisial Nama) --}}
    {{-- Fungsi urlencode() penting agar spasi di nama terbaca benar oleh URL --}}
    <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode(auth()->user()->name) }}" 
         alt="{{ auth()->user()->name }}" 
         class="rounded-circle object-fit-cover" 
         width="25" height="25">
@endif

                                    <small class="fw-semibold text-muted">{{ $review->user->name ?? 'Anonymous' }}</small>
                                </div>
                                <div class="text-muted small">
                                    <i class="bi bi-hand-thumbs-up-fill text-dark"></i> {{ $review->likes_count }} Likes
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($allReviewsCount > 3)
            <div class="text-center mt-4">
                <a href="#" class="btn btn-outline-dark rounded-pill px-4">
                    See All {{ $allReviewsCount }} Reviews
                </a>
            </div>
        @endif
    @else
        <div class="text-center py-5 bg-light rounded-3 border border-dashed my-3">
            <i class="bi bi-chat-quote fs-1 text-muted opacity-50"></i>
            <h5 class="text-muted mt-3 fw-normal">There's no review yet, be the first to write one!</h5>
        </div>
    @endif
</div>

                {{-- ================= RECOMMENDATIONS SECTION (Horizontal Scroll + Shadow) ================= --}}
                <div class="mt-5 mb-5">
                    <h5 class="fw-bold mb-3">Recommendations</h5>
                    @if (!empty($recommendations))
                        <div class="d-flex align-items-center">
                            
                            {{-- Prev Button --}}
                            <button class="nav-btn prev d-none d-md-flex me-3" id="recPrevBtn">
                                <i class="bi bi-chevron-left"></i>
                            </button>

                            {{-- Wrapper Scroll --}}
                            <div id="recWrapper" class="scroll-wrapper flex-grow-1">
                                <div id="recSection" class="scroll-container gap-3">
                                    @foreach($recommendations as $recommendation)
                                        <a href="{{ route('tv.detail', $recommendation['id']) }}" class="text-decoration-none flex-shrink-0 hover-card">
                                            <div class="card border-0 shadow-sm rounded" style="width: 175px;">
                                                @if(!empty($recommendation['poster_path']))
                                                    <img src="https://image.tmdb.org/t/p/w500/{{ $recommendation['poster_path'] }}"
                                                         alt="{{ $recommendation['name'] }}"
                                                         class="img-fluid rounded"
                                                         style="height:255px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                         style="height:255px;">
                                                        <span class="text-muted small text-center p-2">{{ $recommendation['name'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Next Button --}}
                            <button class="nav-btn next d-none d-md-flex ms-3" id="recNextBtn">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    @else
                        <p class="text-muted mt-3">We can't get recommendations for this show</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        
        // --- LOGIC SCROLL & SHADOW (Reusable) ---
        const setupScrollSection = (sectionId, wrapperId, prevBtnId, nextBtnId, cardWidth, cardGap) => {
            const section = document.getElementById(sectionId);
            const wrapper = document.getElementById(wrapperId);
            const prevBtn = document.getElementById(prevBtnId);
            const nextBtn = document.getElementById(nextBtnId);

            if (!section || !wrapper || !prevBtn || !nextBtn) return;

            const scrollStep = (cardWidth + cardGap) * 3; // Scroll 3 kartu sekaligus

            const updateShadow = () => {
                const maxScrollLeft = section.scrollWidth - section.clientWidth - 5; 
                const currentScroll = section.scrollLeft;

                if (currentScroll > 5) wrapper.classList.add('show-shadow-left');
                else wrapper.classList.remove('show-shadow-left');

                if (currentScroll < maxScrollLeft) wrapper.classList.add('show-shadow-right');
                else wrapper.classList.remove('show-shadow-right');
            };

            nextBtn.addEventListener('click', () => {
                section.scrollBy({ left: scrollStep, behavior: 'smooth' });
            });

            prevBtn.addEventListener('click', () => {
                section.scrollBy({ left: -scrollStep, behavior: 'smooth' });
            });

            section.addEventListener('scroll', updateShadow);
            
            // Initial check (delay agar render selesai)
            setTimeout(updateShadow, 500); 
            window.addEventListener('resize', updateShadow);
        };

        // 1. Setup Cast Section (Card 120px + gap 1rem/16px)
        setupScrollSection('castSection', 'castWrapper', 'castPrevBtn', 'castNextBtn', 120, 16);

        // 2. Setup Recommendations Section (Card 175px + gap 1rem/16px)
        setupScrollSection('recSection', 'recWrapper', 'recPrevBtn', 'recNextBtn', 175, 16);


        // --- LOGIC FAVOURITE & WATCHLIST ---
        const showToast = (message, icon = 'success') => {
            Swal.fire({ toast: true, position: 'top', icon: icon, title: message, showConfirmButton: false, timer: 2000, timerProgressBar: true, });
        };

        const requireLoginAlert = () => {
            Swal.fire({ icon: "warning", title: "Login Required", text: "You need to login first to use this feature.", showCancelButton: true, confirmButtonText: "Login", cancelButtonText: "Cancel", confirmButtonColor: "#3085d6", cancelButtonColor: "#d33" }).then(result => { if (result.isConfirmed) { window.location.href = "{{ route('login') }}"; } });
        };

        // Favourite
        const favBtn = document.getElementById('favouriteBtn');
        if (favBtn) { 
            favBtn.addEventListener('click', function() {
                const btn = this;
                fetch('/favourites/toggle', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', },
                    body: JSON.stringify({ tmdb_id: btn.dataset.id, title: btn.dataset.title, poster_path: btn.dataset.poster, overview:btn.dataset.overview, tagline:btn.dataset.tagline, type: btn.dataset.type, }),
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        const isAdded = res.action === 'added';
                        if (isAdded) {
                            btn.classList.remove('btn-outline-danger'); btn.classList.add('btn-danger'); btn.querySelector('i').classList.replace('bi-heart', 'bi-heart-fill'); showToast('Added to favourites ❤️');
                        } else {
                            btn.classList.remove('btn-danger'); btn.classList.add('btn-outline-danger'); btn.querySelector('i').classList.replace('bi-heart-fill', 'bi-heart'); showToast('Removed from favourites 💔', 'info');
                        }
                    }
                });
            });
        }

        // Watchlist
        const watchBtn = document.getElementById('watchlistBtn');
        if (watchBtn) {
            watchBtn.addEventListener('click', function() {
                const btn = this;
                fetch('/watchlists/toggle', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', },
                    body: JSON.stringify({ tmdb_id: btn.dataset.id, title: btn.dataset.title, poster_path: btn.dataset.poster, overview:btn.dataset.overview, tagline:btn.dataset.tagline, type: btn.dataset.type, }),
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        const isAdded = res.action === 'added';
                        if (isAdded) {
                            btn.classList.remove('btn-outline-warning'); btn.classList.add('btn-warning'); btn.querySelector('i').classList.replace('bi-bookmark', 'bi-bookmark-fill'); showToast('Added to watchlist 📚');
                        } else {
                            btn.classList.remove('btn-warning'); btn.classList.add('btn-outline-warning'); btn.querySelector('i').classList.replace('bi-bookmark-fill', 'bi-bookmark'); showToast('Removed from watchlist 🗑️', 'info');
                        }
                    }
                });
            });
        }

        document.querySelectorAll(".login-required").forEach(btn => {
            btn.addEventListener("click", event => { event.preventDefault(); requireLoginAlert(); });
        });
    });
    </script>

</body>
</html>