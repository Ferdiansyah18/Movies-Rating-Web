<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $review->title }} | CinePals Review</title>
    <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body { background-color: #fff; }
        .review-header { background-color: #f8f9fa; border-radius: 15px; }
        .avatar-lg { width: 50px; height: 50px; object-fit: cover; }
        
        /* Style baru untuk Poster Film */
        .movie-poster-sm {
            width: 100px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .movie-poster-sm:hover { transform: scale(1.05); }

        .rating-box {
            background: #212529; color: #ffc107; 
            padding: 10px 20px; border-radius: 10px;
            display: inline-block;
        }
        .content-body { font-size: 1.1rem; line-height: 1.8; color: #333; }
        .btn-like { transition: all 0.2s; }
        .btn-like:active { transform: scale(0.9); }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <x-navbar textColor="text-dark"/>

    <div class="container py-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-10"> {{-- Lebarkan sedikit jadi col-lg-10 --}}
                
                {{-- Header Section --}}
                <div class="review-header p-4 p-md-5 mb-4 position-relative overflow-hidden">
                    
                    <div class="row align-items-center">
                        
                        {{-- KOLOM 1: POSTER FILM (Data Snapshot) --}}
                        <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                            @php
                                // Tentukan Link Balik ke Detail Movie/TV
                                $backLink = $review->item_type == 'movie' 
                                            ? route('movies.detail', $review->item_id) 
                                            : route('tv.detail', $review->item_id);
                                
                                // URL Poster
                                $posterUrl = $review->media_poster 
                                            ? 'https://image.tmdb.org/t/p/w300' . $review->media_poster 
                                            : 'https://via.placeholder.com/300x450?text=No+Poster';
                            @endphp

                            <a href="{{ $backLink }}" title="Back to {{ $review->media_title }}">
                                <img src="{{ $posterUrl }}" class="movie-poster-sm" alt="{{ $review->media_title }}">
                            </a>
                        </div>

                        {{-- KOLOM 2: INFO REVIEW --}}
                        <div class="col">
                            {{-- Info Film Kecil di atas Judul Review --}}
                            <div class="mb-2 text-center text-md-start">
                                <a href="{{ $backLink }}" class="text-decoration-none text-secondary fw-bold text-uppercase small ls-1">
                                    Reviewing: {{ $review->media_title }} 
                                    <span class="fw-normal">({{ $review->media_year }})</span>
                                </a>
                            </div>

                            <h1 class="fw-bold mb-3 text-center text-md-start">{{ $review->title }}</h1>
                            
                            {{-- User Profile & Info --}}
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-3">
{{-- LOGIKA FOTO PROFIL --}}
@if($review->user && $review->user->profile_picture)
                                    <img src="{{ asset('storage/' . $review->user->profile_picture) }}" 
                                         class="rounded-circle avatar-lg shadow-sm border" alt="User">
@else
    {{-- 2. Jika user BELUM punya foto -> Pakai UI Avatars (Inisial Nama) --}}
    {{-- Fungsi urlencode() penting agar spasi di nama terbaca benar oleh URL --}}
    <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode(auth()->user()->name) }}" 
         alt="{{ auth()->user()->name }}" 
         class="rounded-circle object-fit-cover" 
         width="50" height="50">
@endif

                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'User' }}</h6>
                                    <small class="text-muted">Reviewed on {{ $review->created_at->format('d M Y') }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM 3: RATING (Pindah ke kanan/bawah) --}}
                        <div class="col-md-auto mt-4 mt-md-0 text-center">
                            <div class="rating-box shadow">
                                <div class="fs-1 fw-bold d-flex align-items-center gap-2">
                                    <i class="bi bi-star-fill text-warning"></i> {{ $review->rating }}
                                </div>
                                <small class="text-white-50 d-block mt-1">OUT OF 10</small>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Content Section --}}
                <div class="content-body px-md-3">
                    {!! nl2br(e($review->comment)) !!}
                </div>

                <hr class="my-5">

                {{-- Like Button Section --}}
                <div class="d-flex justify-content-center">
                    @auth
                        @php $isLiked = $review->isLikedBy(auth()->user()); @endphp
                        <button id="likeBtn" 
                                class="btn btn-like rounded-pill px-4 py-2 {{ $isLiked ? 'btn-dark' : 'btn-outline-dark' }}"
                                data-id="{{ $review->id }}">
                            <i class="bi {{ $isLiked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }} me-2"></i> 
                            Helpful? 
                            <span class="ms-2 badge bg-secondary text-white rounded-pill" id="likeCount">{{ $review->likes->count() }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-4 py-2">
                            <i class="bi bi-hand-thumbs-up me-2"></i> 
                            Helpful?
                            <span class="ms-2 badge bg-secondary text-white rounded-pill">{{ $review->likes->count() }}</span>
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </div>

    <x-footer/>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Like --}}
    @auth
    <script>
        document.getElementById('likeBtn').addEventListener('click', function() {
            const btn = this;
            const reviewId = btn.dataset.id;
            const icon = btn.querySelector('i');
            const counter = document.getElementById('likeCount');

            btn.disabled = true;

            fetch(`/reviews/${reviewId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    counter.innerText = data.count;
                    if(data.liked) {
                        btn.classList.remove('btn-outline-dark');
                        btn.classList.add('btn-dark');
                        icon.classList.remove('bi-hand-thumbs-up');
                        icon.classList.add('bi-hand-thumbs-up-fill');
                    } else {
                        btn.classList.remove('btn-dark');
                        btn.classList.add('btn-outline-dark');
                        icon.classList.remove('bi-hand-thumbs-up-fill');
                        icon.classList.add('bi-hand-thumbs-up');
                    }
                }
                btn.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
            });
        });
    </script>
    @endauth
</body>
</html>