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
        .avatar-lg { width: 60px; height: 60px; object-fit: cover; }
        .rating-box {
            background: #212529; color: #ffc107; 
            padding: 10px 20px; border-radius: 10px;
            display: inline-block;
        }
        .content-body { font-size: 1.1rem; line-height: 1.8; color: #333; }
        
        /* Animasi kecil saat like ditekan */
        .btn-like { transition: all 0.2s; }
        .btn-like:active { transform: scale(0.9); }
    </style>
</head>
<body>

    <x-navbar textColor="text-dark"/>

    <div class="container py-5">
        
        {{-- Back Button --}}
        <div class="mb-4">
            @if($review->item_type == 'movie')
                <a href="{{ route('movies.detail', $review->item_id) }}" class="text-decoration-none text-muted fw-semibold hover-underline">
                    <i class="bi bi-arrow-left"></i> Back to Movie
                </a>
            @else
                <a href="{{ route('tv.detail', $review->item_id) }}" class="text-decoration-none text-muted fw-semibold hover-underline">
                    <i class="bi bi-arrow-left"></i> Back to TV Show
                </a>
            @endif
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                {{-- Header Section --}}
                <div class="review-header p-4 p-md-5 mb-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h1 class="fw-bold mb-2">{{ $review->title }}</h1>
                            <div class="d-flex align-items-center gap-3 mt-3">
                                
                                {{-- LOGIKA FOTO PROFIL --}}
                                @if($review->user && $review->user->profile_picture)
                                    {{-- Jika ada foto --}}
                                    <img src="{{ asset('storage/' . $review->user->profile_picture) }}" 
                                         class="rounded-circle avatar-lg shadow-sm" 
                                         alt="User">
                                @else
                                    {{-- Jika TIDAK ada foto (Default Icon) --}}
                                    {{-- Font-size 60px diset agar sama dengan class .avatar-lg --}}
                                    <i class="bi bi-person-circle text-secondary shadow-sm rounded-circle" 
                                       style="font-size: 60px; line-height: 1; background-color: #fff;"></i>
                                @endif

                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'CinePals User' }}</h6>
                                    <small class="text-muted">Reviewed on {{ $review->created_at->format('d F Y') }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- Rating Badge --}}
                        <div class="text-center">
                            <div class="rating-box shadow">
                                <div class="fs-2 fw-bold d-flex align-items-center gap-2">
                                    <i class="bi bi-star-fill"></i> {{ $review->rating }}
                                </div>
                                <small class="text-white-50">OUT OF 10</small>
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
                        @php
                            $isLiked = $review->isLikedBy(auth()->user());
                        @endphp
                        <button id="likeBtn" 
                                class="btn btn-like rounded-pill px-4 py-2 {{ $isLiked ? 'btn-dark' : 'btn-outline-dark' }}"
                                data-id="{{ $review->id }}">
                            <i class="bi {{ $isLiked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }} me-2"></i> 
                            Likes
                            <span class="ms-2 badge bg-secondary text-white" id="likeCount">{{ $review->likes->count() }}</span>
                        </button>
                    @else
                        {{-- Jika belum login, arahkan ke login --}}
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-4 py-2">
                            <i class="bi bi-hand-thumbs-up me-2"></i> 
                            Likes
                            <span class="ms-2 badge bg-secondary text-white">{{ $review->likes->count() }}</span>
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </div>

    <x-footer/>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script untuk Like --}}
    @auth
    <script>
        document.getElementById('likeBtn').addEventListener('click', function() {
            const btn = this;
            const reviewId = btn.dataset.id;
            const icon = btn.querySelector('i');
            const counter = document.getElementById('likeCount');

            // Disable button sementara biar ga spam klik
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
                    // Update Angka
                    counter.innerText = data.count;

                    // Update Tampilan Tombol & Icon
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