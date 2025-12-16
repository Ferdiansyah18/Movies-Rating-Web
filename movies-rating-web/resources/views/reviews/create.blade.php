<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write a Review | CinePals</title>
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
    
    {{-- Bootstrap CSS & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body { background-color: #f8f9fa; }
        .form-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .rating-select { font-size: 1.1rem; padding: 10px; }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <x-navbar textColor="text-dark"/>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <div class="d-flex align-items-center mb-4">
                        {{-- Tampilkan Poster Kecil di atas Form --}}
                        @php
                            $poster = $item['poster_path'] ? 'https://image.tmdb.org/t/p/w92' . $item['poster_path'] : 'https://via.placeholder.com/92x138';
                            $title = $type == 'movie' ? $item['title'] : $item['name'];
                            $year = $type == 'movie' 
                                    ? substr($item['release_date'] ?? '', 0, 4) 
                                    : substr($item['first_air_date'] ?? '', 0, 4);
                        @endphp
                        
                        <img src="{{ $poster }}" class="rounded me-3 shadow-sm">
                        <div>
                            <small class="text-muted uppercase fw-bold">WRITING REVIEW FOR</small>
                            <h4 class="mb-0 fw-bold">{{ $title }} <span class="text-muted fw-normal">({{ $year }})</span></h4>
                        </div>
                    </div>

                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf

                        {{-- === BAGIAN PENTING: SNAPSHOT DATA (HIDDEN) === --}}
                        <input type="hidden" name="item_id" value="{{ $item['id'] }}">
                        <input type="hidden" name="item_type" value="{{ $type }}">
                        <input type="hidden" name="media_title" value="{{ $title }}">
                        <input type="hidden" name="media_poster" value="{{ $item['poster_path'] }}">
                        <input type="hidden" name="media_year" value="{{ $year }}">

                        {{-- Form User --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Your Headline</label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g., A Masterpiece of visual storytelling!" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rating</label>
                            <select name="rating" class="form-select" required>
                                <option value="" selected disabled>Select Rating</option>
                                @for($i=10; $i>=1; $i--)
                                    <option value="{{ $i }}">{{ $i }} - {{ $i == 10 ? 'Masterpiece' : ($i == 1 ? 'Terrible' : 'Stars') }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Your Review</label>
                            <textarea name="comment" class="form-control" rows="5" placeholder="Write your thoughts here..." required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg">Publish Review</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Footer --}}
    <x-footer/>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>