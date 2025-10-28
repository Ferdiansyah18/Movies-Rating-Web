<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover Movies | CinePals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
    <style>
        /* Hover effect untuk genre yang tidak aktif */
        .genre-btn.btn-outline-secondary.text-muted:hover {
            background-color: #0d6efd; /* Bootstrap primary */
            color: #fff !important; /* Text putih */
            border-color: #0d6efd;
        }

        /* Smooth transition */
        .genre-btn {
            transition: all 0.2s ease-in-out;
        }
    </style>
</head>
<body>
    <x-navbar textColor="text-dark" position="position-sticky"/>

    <section class="container mt-3">
        <h1>Discover Movies</h1>

        <div class="row flex-row g-4 mt-3 align-items-start">
            {{-- ======= FILTER GENRE ======= --}}
            <div class="border col-3 p-3 position-sticky" style="top: 80px;">
                <h5 class="mb-3">🎭 Genre Filter</h5>

                <form action="{{ route('movies.discover') }}" method="GET" id="genreForm">
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $activeGenres = request()->query('genre') ? explode(',', request()->query('genre')) : [];
                        @endphp

                        @foreach($genres as $genre)
                            <button type="button"
                                    class="btn btn-sm genre-btn {{ in_array($genre['id'], $activeGenres) ? 'btn-primary' : 'btn-outline-secondary text-muted' }}"
                                    data-id="{{ $genre['id'] }}">
                                {{ $genre['name'] }}
                            </button>
                        @endforeach
                    </div>

                    <input type="hidden" name="genre" id="selectedGenres" value="{{ request()->query('genre') ?? '' }}">
                    <button type="submit" class="btn btn-primary mt-3">Search</button>
                </form>
            </div>

            {{-- ======= MOVIES SECTION ======= --}}
            <div class="col-9 px-3">
                <div id="trendingSection"
                     class="d-flex flex-wrap justify-content-start align-items-start gap-3 p-3"
                     style="overflow: hidden; row-gap:20px;">

                    @if(empty($discoverMovies['results']))
                        @for ($i = 0; $i < 5; $i++)
                            <div class="card placeholder-glow border-0" style="width:170px; height:255px;">
                                <span class="placeholder col-12 bg-secondary"
                                      style="width:170px; height:255px; border-radius:8px; display:block;"></span>
                            </div>
                        @endfor
                    @else
                        @foreach($discoverMovies['results'] as $movie)
                            <a href="{{ route('movies.detail', $movie['id']) }}" class="text-decoration-none">
                                <div class="card border-0" style="width:170px;">
                                    <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                         alt="{{ $movie['title'] }}"
                                         class="rounded"
                                         style="width:170px; height:255px; object-fit:cover;">
                                    <p class="mt-2 text-center small fw-semibold">{{ $movie['title'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>

                {{-- Pagination --}}
                @if(isset($discoverMovies['page']))
                    <div class="d-flex justify-content-center my-4">
                        @php
                            $currentGenres = request()->query('genre');
                        @endphp
                        @if($discoverMovies['page'] > 1)
                            <a href="{{ route('movies.discover', ['genre' => $currentGenres, 'page' => $discoverMovies['page'] - 1]) }}"
                               class="btn btn-outline-secondary me-2">Previous</a>
                        @endif
                        @if($discoverMovies['page'] < $discoverMovies['total_pages'])
                            <a href="{{ route('movies.discover', ['genre' => $currentGenres, 'page' => $discoverMovies['page'] + 1]) }}"
                               class="btn btn-outline-secondary">Next</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const buttons = document.querySelectorAll('.genre-btn');
        const input = document.getElementById('selectedGenres');

        let selected = input.value ? input.value.split(',') : [];

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');

                if (selected.includes(id)) {
                    selected = selected.filter(i => i !== id);
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-secondary', 'text-muted');
                } else {
                    selected.push(id);
                    btn.classList.add('btn-primary');
                    btn.classList.remove('btn-outline-secondary', 'text-muted');
                }

                input.value = selected.join(',');
            });
        });
    </script>
</body>
</html>
