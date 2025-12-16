<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie['title'] }} | CinePals</title>
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
            /* Padding horizontal agar item pertama/terakhir tidak terpotong saat scale */
            padding-left: 5px; 
            padding-right: 5px;
        }
        
        /* Hide scrollbar standard agar diganti tombol nav */
        .scroll-container::-webkit-scrollbar {
            display: none;
        }

        /* --- 2. CARD HOVER EFFECT (FIXED Z-INDEX) --- */
        .hover-card {
            /* Penting: z-index 0s agar layer langsung naik tanpa delay animasi */
            transition: transform 0.3s ease, box-shadow 0.3s ease, z-index 0s; 
            display: block;
            position: relative; /* Wajib relative agar z-index berfungsi */
            z-index: 1;         /* Level normal (di bawah overlay fade) */
        }

        .hover-card:hover {
            transform: scale(1.05) translateY(-5px); /* Kombinasi scale & angkat sedikit */
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
            
            /* SOLUSI: Z-index 20 agar lebih tinggi dari fade overlay (z-index 4) */
            z-index: 20; 
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
            z-index: 30; /* Pastikan tombol nav selalu paling atas */
        }
        .nav-btn:hover {
            background-color: #f8f9fa;
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        /* --- 4. SCROLL FADE EFFECT --- */
        .scroll-wrapper {
            position: relative;
            /* Hapus overflow: hidden jika card terpotong, tapi biarkan untuk menahan layout rapi */
            overflow: hidden; 
        }
        
        .scroll-wrapper::before,
        .scroll-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 80px; /* Lebar fade area */
            pointer-events: none; /* Penting: agar klik tembus ke card di bawahnya */
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
            z-index: 999; /* Overlay level menengah */
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

            {{-- Poster Sidebar --}}
            <div class="container col-5 col-md-3 col-xl-2">
                <div class="card position-sticky border-0" style="top: 80px;">
                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                         alt="{{ $movie['title'] }}" class="card-img-top mb-3 shadow rounded">
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

            {{-- Movie Details Content --}}
            <div class="container col-10 col-md-9 mt-md-5 pt-3 ps-md-5 ps-xl-0">
                <div class="row align-items-center">
                    <h1 class="fw-bold col-9">{{ $movie['title'] }}</h1>
                    <div class="col-3 d-flex justify-content-center gap-2">
                        @auth
                            <button id="favouriteBtn" 
                                    class="btn d-flex align-items-center gap-2 {{ $favourite ? 'btn-danger' : 'btn-outline-danger' }}"
                                    data-id="{{ $movie['id'] }}"
                                    data-title="{{ $movie['title'] }}"
                                    data-poster="{{ $movie['poster_path'] }}"
                                    data-overview="{{ $movie['overview'] }}"
                                    data-tagline="{{ $movie['tagline'] }}"
                                    data-type="movie">
                                <i class="bi {{ $favourite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>

                            <button id="watchlistBtn" 
                                    class="btn d-flex align-items-center gap-2 {{ $watchlist ? 'btn-warning' : 'btn-outline-warning' }}"
                                    data-id="{{ $movie['id'] }}"
                                    data-title="{{ $movie['title'] }}"
                                    data-poster="{{ $movie['poster_path'] }}"
                                    data-overview="{{ $movie['overview'] }}"
                                    data-tagline="{{ $movie['tagline'] }}"
                                    data-type="movie">
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

                @if (!empty($movie['tagline']))
                    <h5 class="text-muted fst-italic">{{ $movie['tagline'] }}</h5>
                @endif

                <p class="mt-3">{{ $movie['overview'] }}</p>

                {{-- Metadata Mobile View --}}
                <ul class="list-inline d-flex d-md-none gap-3 mt-3 overflow-auto">
                    @if(!empty($movie['runtime']))
                        <li><h6>Duration:</h6><span>{{ $movie['runtime'] }} Min</span></li>
                    @endif
                    @if(!empty($movie['first_air_date']))
                        <li class="list-inline-item"><h6>Release Date:</h6> {{ $movie['first_air_date'] }}</li>
                    @endif
                </ul>

                {{-- Watch Providers --}}
                <h6 class="mt-3">Watch It Online</h6>
                @if (isset($movieProviders['results']['ID']['flatrate']))
                    <div class="d-flex flex-wrap mt-3 gap-4">
                        @foreach ($movieProviders['results']['ID']['flatrate'] as $provider)
                            <div class="text-center d-flex flex-column align-items-center gap-2">
                                <img src="https://image.tmdb.org/t/p/w500{{ $provider['logo_path'] }}" 
                                     alt="{{ $provider['provider_name'] }}" class="rounded hover-card shadow-sm" 
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
                                                     alt="{{ $cast['name'] }}"
                                                     style="height: 180px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center rounded-top" 
                                                     style="height:180px;">
                                                    <span class="text-muted small">No Image</span>
                                                </div>
                                            @endif
                                            <div class="card-body p-2 text-center font-sm">
                                                <small class="card-title d-block text-truncate fw-bold mb-0">{{ $cast['name'] }}</small>
                                                <small class="text-muted d-block text-truncate" style="font-size: 0.8rem;">{{ $cast['character'] }}</small>
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

                {{-- ================= REVIEWS SECTION (Limited 3 Items) ================= --}}
<div class="d-flex flex-column mt-5 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">User Reviews</h4>
        
        @auth
            <a href="{{ route('reviews.create', ['type' => 'movie', 'id' => $movie['id']]) }}" class="btn btn-dark btn-sm rounded-pill px-3">
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
        // Ambil semua review untuk movie ini
        $allReviews = Review::where('item_id', $movie['id'])
                            ->where('item_type', 'movie')
                            ->latest()
                            ->get();
        
        // Ambil 3 review terbaru untuk ditampilkan
        $displayReviews = Review::where('item_id', $movie['id'])
                            ->where('item_type', 'movie')
                            ->with('user')
                            ->withCount('likes')
                            ->latest()
                            ->take(3)
                            ->get();
    @endphp

    @if($displayReviews->count() > 0)
        <div class="d-flex flex-column gap-3">
            @foreach($displayReviews as $review)
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

        {{-- Tombol See All (Jika review > 3) --}}
        @if($allReviews->count() > 3)
            <div class="text-center mt-4">
                <a href="#" class="btn btn-outline-dark rounded-pill px-4">
                    See All {{ $allReviews->count() }} Reviews
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
                                        <a href="{{ route('movies.detail', $recommendation['id']) }}" class="text-decoration-none flex-shrink-0 hover-card">
                                            <div class="card border-0 shadow-sm rounded" style="width: 175px;">
                                                @if(!empty($recommendation['poster_path']))
                                                    <img src="https://image.tmdb.org/t/p/w500/{{ $recommendation['poster_path'] }}"
                                                         alt="{{ $recommendation['title'] }}"
                                                         class="img-fluid rounded"
                                                         style="height:255px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                         style="height:255px;">
                                                        <span class="text-muted small text-center p-2">{{ $recommendation['title'] }}</span>
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
                        <p class="text-muted mt-3">We can't get recommendations for this movie</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <x-footer/>

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


        // --- LOGIC FAVOURITE & WATCHLIST (TIDAK BERUBAH) ---
        const showToast = (message, icon = 'success') => {
            Swal.fire({ toast: true, position: 'top', icon: icon, title: message, showConfirmButton: false, timer: 2000, timerProgressBar: true, });
        };

        const requireLoginAlert = () => {
            Swal.fire({ icon: "warning", title: "Login Required", text: "You need to login first to use this feature.", showCancelButton: true, confirmButtonText: "Login", cancelButtonText: "Cancel", confirmButtonColor: "#3085d6", cancelButtonColor: "#d33" }).then(result => { if (result.isConfirmed) { window.location.href = "{{ route('login') }}"; } });
        };

        // Favourite
        const favBtn = document.getElementById("favouriteBtn");
        if (favBtn) {
            favBtn.addEventListener("click", function () {
                fetch('/favourites/toggle', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', },
                    body: JSON.stringify({ tmdb_id: this.dataset.id, title: this.dataset.title, poster_path: this.dataset.poster, overview: this.dataset.overview, tagline: this.dataset.tagline, type: this.dataset.type, })
                }).then(res => res.json()).then(res => { if (res.success) { const icon = this.querySelector('i'); const isAdded = res.action === 'added'; this.classList.toggle('btn-outline-danger', !isAdded); this.classList.toggle('btn-danger', isAdded); icon.classList.toggle('bi-heart', !isAdded); icon.classList.toggle('bi-heart-fill', isAdded); showToast(isAdded ? "Added to Favourites ❤️" : "Removed from Favourites 💔", isAdded ? "success" : "error"); } });
            });
        } 

        // Watchlist
        const watchBtn = document.getElementById("watchlistBtn");
        if (watchBtn) {
            watchBtn.addEventListener("click", function () {
                fetch('/watchlists/toggle', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', },
                    body: JSON.stringify({ tmdb_id: this.dataset.id, title: this.dataset.title, poster_path: this.dataset.poster, overview: this.dataset.overview, tagline: this.dataset.tagline, type: this.dataset.type, })
                }).then(res => res.json()).then(res => { if (res.success) { const icon = this.querySelector('i'); const isAdded = res.action === 'added'; this.classList.toggle('btn-outline-warning', !isAdded); this.classList.toggle('btn-warning', isAdded); icon.classList.toggle('bi-bookmark', !isAdded); icon.classList.toggle('bi-bookmark-fill', isAdded); showToast(isAdded ? "Added to Watchlist 📚" : "Removed from Watchlist 🗑️", isAdded ? "success" : "error"); } });
            });
        }

        document.querySelectorAll(".login-required").forEach(btn => {
            btn.addEventListener("click", event => { event.preventDefault(); requireLoginAlert(); });
        });
    });
    </script>

</body>
</html>