@vite(['resources/css/app.css', 'resources/js/app.js'])

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
  <title>Home | CinePals</title>
</head>
<body>

  <!-- Navbar -->
  <x-navbar textColor="text-light" />

  <!-- ================= HERO SECTION ================= -->
  <div class="container-fluid position-relative p-0" style="height: 50vh; overflow: visible;">

    <!-- Backdrop Placeholder -->
    <div class="w-100 h-100 position-absolute top-0 start-0" id="backdropSection">
      <div class="placeholder-glow w-100 h-100 d-flex align-items-center justify-content-center">
        <div class="placeholder w-100 h-100"></div>
      </div>
    </div>

    <!-- Hero Content -->
    <div class="d-flex flex-column justify-content-center align-items-center gap-5 h-100 position-relative" style="z-index:2;">
      <h1 class="text-center text-md-start text-light">
        @auth
          Hi, {{ auth()->user()->name }}! <br>Ready to explore some movies today?
        @else
          Hi there! <br>Ready to explore some movies today?
        @endauth
      </h1>

      <div class="row w-100 justify-content-center">
        <div class="col-10 col-md-6">
          <div class="position-relative">

            <!-- Search Input -->
            <input type="search"
                   id="searchBox"
                   class="form-control rounded-pill p-2 px-4"
                   placeholder="Search Movies or TV Series..."
                   autocomplete="off">

            <!-- Dropdown hasil search -->
            <div id="searchResults"
                 class="list-group position-absolute top-100 start-0 w-100 z-3 bg-white rounded shadow">
              <!-- Inject via JS -->
            </div>

            <!-- Spinner Bootstrap -->
            <div id="searchSpinner"
                 class="position-absolute top-50 start-50 translate-middle d-none z-3">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>

            <!-- Hidden route template -->
            <a href="{{ route('movies.detail', 'REPLACE_ID') }}" id="movieRoute" class="d-none"></a>
            <a href="{{ route('tv.detail', 'REPLACE_ID') }}" id="tvRoute" class="d-none"></a>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ================= END HERO SECTION ================= -->

  <!-- ================= TRENDING SECTION ================= -->
  <div class="container py-5">
    <h3 class="pb-3">Trending Today</h3>

    <div id="trendingSection"
         class="d-flex justify-content-start gap-3 pb-3"
         style="overflow-x: hidden;">
      {{-- Placeholder Loading --}}
      @for ($i = 0; $i < 7; $i++)
        <div class="card placeholder-glow border-0" style="width:170px; height:255px;">
          <span class="placeholder col-12 bg-secondary"
                style="width:170px; height:255px; border-radius:8px; display:block;"></span>
        </div>
      @endfor
    </div>
  </div>
  <!-- ================= END TRENDING SECTION ================= -->

  <!-- ================= REVIEW SECTION ================= -->
  <div class="bg-light d-flex justify-content-center align-items-center my-3 py-5">
    <div class="container d-flex align-items-center justify-content-center">
      <h4>🎬 Let your voice be heard in the world of cinema.</h4>
    </div>
  </div>
  <!-- ================= END REVIEW SECTION ================= -->

  <!-- ================= POPULAR MOVIES SECTION ================= -->
  <div class="container py-5">
    <h3 class="pb-3">Popular Movies</h3>

    <div id="popularSection"
         class="d-flex justify-content-start gap-3 pb-3"
         style="overflow-x: hidden;">
      {{-- Placeholder Loading --}}
      @for ($i = 0; $i < 7; $i++)
        <div class="card placeholder-glow border-0" style="width:170px; height:255px;">
          <span class="placeholder col-12 bg-secondary"
                style="width:170px; height:255px; border-radius:8px; display:block;"></span>
        </div>
      @endfor
    </div>
  </div>
  <!-- ================= END POPULAR MOVIES SECTION ================= -->

  <!-- ================= TOP RATED TV SECTION ================= -->
  <div class="container py-5">
    <h3 class="pb-3">Top Rated Series</h3>

    <div id="topratedSection"
         class="d-flex justify-content-start gap-3 pb-3"
         style="overflow-x: hidden;">
      {{-- Placeholder Loading --}}
      @for ($i = 0; $i < 7; $i++)
        <div class="card placeholder-glow border-0" style="width:170px; height:255px;">
          <span class="placeholder col-12 bg-secondary"
                style="width:170px; height:255px; border-radius:8px; display:block;"></span>
        </div>
      @endfor
    </div>
  </div>
  <!-- ================= END TOP RATED TV SECTION ================= -->

  <!-- ================= FOOTER ================= -->
  <x-footer />

  <!-- ================= JS: LOAD DATA API ================= -->
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      const trendingSection = document.getElementById('trendingSection');
      const popularSection = document.getElementById('popularSection');
      const topratedSection = document.getElementById('topratedSection')
      const backdropSection = document.getElementById('backdropSection');

      try {
        // Ambil data dari endpoint Laravel API
        const response = await fetch('/api');
        const data = await response.json();

        // ================= BACKDROP =================
        if (data.randomBackdrop) {
          backdropSection.innerHTML = `
            <img src="${data.randomBackdrop}" 
                 class="w-100 h-100 position-absolute top-0 start-0" 
                 style="object-fit: cover; filter: brightness(30%); z-index:1;">
          `;
        }

        // ================= TRENDING =================
        trendingSection.innerHTML = `
          <div class="d-flex justify-content-start gap-3 pb-3" style="overflow-x:auto;">
            ${data.trending.map(item => {
              const title = item.title ?? item.name;
              const route = item.media_type === 'movie'
                ? '/movie/' + item.id
                : '/tv/' + item.id;
              return `
                <a href="${route}">
                  <img src="https://image.tmdb.org/t/p/w500${item.poster_path}"
                       class="img-fluid rounded"
                       style="max-width:175px; max-height:255px;"
                       alt="${title}">
                </a>
              `;
            }).join('')}
          </div>
        `;

        // ================= POPULAR =================
        popularSection.innerHTML = `
          <div class="d-flex justify-content-start gap-3 pb-3" style="overflow-x:auto;">
            ${data.popularMovies.map(movie => `
              <a href="/movie/${movie.id}">
                <img src="https://image.tmdb.org/t/p/w500${movie.poster_path}"
                     class="img-fluid rounded"
                     style="max-width:175px; max-height:255px;"
                     alt="${movie.title}">
              </a>
            `).join('')}
          </div>
        `;

        // ================= POPULAR =================
        topratedSection.innerHTML = `
          <div class="d-flex justify-content-start gap-3 pb-3" style="overflow-x:auto;">
            ${data.tvTopRated.map(series => `
              <a href="/tv/${series.id}">
                <img src="https://image.tmdb.org/t/p/w500${series.poster_path}"
                     class="img-fluid rounded"
                     style="max-width:175px; max-height:255px;"
                     alt="${series.title}">
              </a>
            `).join('')}
          </div>
        `;

      } catch (error) {
        console.error('Error fetching movies:', error);
        trendingSection.innerHTML = `<p class="text-danger">Failed to load trending movies.</p>`;
        popularSection.innerHTML = `<p class="text-danger">Failed to load popular movies.</p>`;
        backdropSection.innerHTML = `<p class="text-center text-light">Failed to load backdrop.</p>`;
      }
    });
  </script>

</body>
</html>
