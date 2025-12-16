<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <title>Home | CinePals</title>

  {{-- Load Assets via Vite --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
      /* --- 1. GLOBAL SCROLLBAR STYLING --- */
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
          padding-top: 15px !important; 
          padding-bottom: 15px !important; 
      }

      /* --- 2. CARD HOVER EFFECT --- */
      .hover-card {
          transition: transform 0.3s ease, box-shadow 0.3s ease;
          display: block;
          position: relative;
      }
      
      .hover-card:hover {
          transform: translateY(-10px);
          box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important;
          z-index: 1; 
      }
      
      .hover-card img {
          border-radius: 8px;
      }

      /* --- 3. KHUSUS SEARCH DROPDOWN --- */
      #searchResults {
          max-height: 400px;
          overflow-y: auto; 
      }

      #searchResults::-webkit-scrollbar {
          width: 8px; 
      }
      #searchResults::-webkit-scrollbar-track {
          background: #f1f1f1;
          border-radius: 4px;
      }
      #searchResults::-webkit-scrollbar-thumb {
          background: #888;
          border-radius: 4px;
      }
      #searchResults::-webkit-scrollbar-thumb:hover {
          background: #555;
      }

      /* --- 4. REVIEW SECTION (Dan Section Lainnya) --- */
      .review-scroll-container { /* Bisa dipakai general */
          display: flex;
          overflow-x: auto;
          scroll-behavior: smooth;
          -ms-overflow-style: none; 
          scrollbar-width: none; 
          padding-top: 10px;
          padding-bottom: 10px;
      }
      .review-scroll-container::-webkit-scrollbar {
          display: none; 
      }

      /* Tombol Navigasi */
      .nav-btn {
          width: 45px;
          height: 45px;
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
          z-index: 5; /* Agar di atas shadow */
      }
      .nav-btn:hover {
          background-color: #f8f9fa;
          box-shadow: 0 6px 15px rgba(0,0,0,0.2);
          transform: scale(1.1);
      }

      /* --- 5. SCROLL FADE EFFECT SYSTEM --- */
      .scroll-wrapper {
          position: relative;
          overflow: hidden;
      }
      
      /* Base Style untuk Shadow */
      .scroll-wrapper::before,
      .scroll-wrapper::after {
          content: '';
          position: absolute;
          top: 0;
          bottom: 0;
          width: 100px; 
          pointer-events: none; 
          opacity: 0; 
          transition: opacity 0.4s ease-in-out;
          z-index: 4; /* Di bawah tombol nav */
      }

      /* Posisi */
      .scroll-wrapper::before { left: 0; }
      .scroll-wrapper::after { right: 0; }

      /* Toggle Opacity */
      .scroll-wrapper.show-shadow-left::before { opacity: 1; }
      .scroll-wrapper.show-shadow-right::after { opacity: 1; }

      /* --- VARIANT 1: WHITE FADE (Untuk Trending, Popular, Top Rated) --- */
      .scroll-wrapper.white-fade::before {
          background: linear-gradient(to right, #ffffff 20%, transparent 100%);
      }
      .scroll-wrapper.white-fade::after {
          background: linear-gradient(to right, transparent 0%, #ffffff 80%);
      }

      /* --- VARIANT 2: LIGHT GRAY FADE (Untuk Reviews) --- */
      /* Warna #f8f9fa sesuai bg-light Bootstrap */
      .scroll-wrapper.gray-fade::before {
          background: linear-gradient(to right, #f8f9fa 20%, transparent 100%);
      }
      .scroll-wrapper.gray-fade::after {
          background: linear-gradient(to right, transparent 0%, #f8f9fa 80%);
      }

  </style>
</head>
<body>

  <x-navbar textColor="text-light" />

  <div class="container-fluid position-relative p-0" style="height: 50vh; overflow: visible;">

    <div class="w-100 h-100 position-absolute top-0 start-0" id="backdropSection">
      <div class="placeholder-glow w-100 h-100 d-flex align-items-center justify-content-center">
        <div class="placeholder w-100 h-100"></div>
      </div>
    </div>

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

            <input type="search"
                   id="searchBox"
                   class="form-control rounded-pill p-2 px-4 shadow-sm"
                   placeholder="Search Movies or TV Series..."
                   autocomplete="off">

            <div id="searchResults"
                 class="list-group position-absolute top-100 start-0 w-100 bg-white rounded shadow d-none mt-2 overflow-auto"
                 style="z-index: 1050;">
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container py-5">
    <h3 class="pb-3 border-bottom mb-4">Trending Today</h3>
    
    <div class="d-flex align-items-center">
        <button class="nav-btn prev d-none d-md-flex me-3" id="trendingPrevBtn">
            <i class="bi bi-chevron-left fs-5"></i>
        </button>

        <div id="trendingWrapper" class="scroll-wrapper white-fade flex-grow-1">
            <div id="trendingSection" class="review-scroll-container d-flex gap-3">
              @for ($i = 0; $i < 7; $i++)
                <div class="card placeholder-glow border-0 flex-shrink-0" style="width:170px; height:255px;">
                  <span class="placeholder col-12 bg-secondary" style="width:170px; height:255px; border-radius:8px; display:block;"></span>
                </div>
              @endfor
            </div>
        </div>

        <button class="nav-btn next d-none d-md-flex ms-3" id="trendingNextBtn">
            <i class="bi bi-chevron-right fs-5"></i>
        </button>
    </div>
  </div>

  <div class="container py-5">
    <h3 class="pb-3 border-bottom mb-4">Popular Movies</h3>

    <div class="d-flex align-items-center">
        <button class="nav-btn prev d-none d-md-flex me-3" id="popularPrevBtn">
            <i class="bi bi-chevron-left fs-5"></i>
        </button>

        <div id="popularWrapper" class="scroll-wrapper white-fade flex-grow-1">
            <div id="popularSection" class="review-scroll-container d-flex gap-3">
              @for ($i = 0; $i < 7; $i++)
                <div class="card placeholder-glow border-0 flex-shrink-0" style="width:170px; height:255px;">
                  <span class="placeholder col-12 bg-secondary" style="width:170px; height:255px; border-radius:8px; display:block;"></span>
                </div>
              @endfor
            </div>
        </div>

        <button class="nav-btn next d-none d-md-flex ms-3" id="popularNextBtn">
            <i class="bi bi-chevron-right fs-5"></i>
        </button>
    </div>
  </div>

  <div class="container py-5">
    <h3 class="pb-3 border-bottom mb-4">Top Rated Series</h3>

    <div class="d-flex align-items-center">
        <button class="nav-btn prev d-none d-md-flex me-3" id="topratedPrevBtn">
            <i class="bi bi-chevron-left fs-5"></i>
        </button>

        <div id="topratedWrapper" class="scroll-wrapper white-fade flex-grow-1">
            <div id="topratedSection" class="review-scroll-container d-flex gap-3">
              @for ($i = 0; $i < 7; $i++)
                <div class="card placeholder-glow border-0 flex-shrink-0" style="width:170px; height:255px;">
                  <span class="placeholder col-12 bg-secondary" style="width:170px; height:255px; border-radius:8px; display:block;"></span>
                </div>
              @endfor
            </div>
        </div>

        <button class="nav-btn next d-none d-md-flex ms-3" id="topratedNextBtn">
            <i class="bi bi-chevron-right fs-5"></i>
        </button>
    </div>
  </div>

  <div class="bg-light py-5 mt-4">
    <div class="container">
      <h3 class="pb-3 border-bottom mb-4">Latest Community Reviews</h3>
      
      <div class="d-flex align-items-center">
          <button class="nav-btn prev d-none d-md-flex me-3" id="reviewPrevBtn">
              <i class="bi bi-chevron-left fs-5"></i>
          </button>

          <div id="reviewsWrapper" class="scroll-wrapper gray-fade flex-grow-1">
              <div id="reviewsSection" class="review-scroll-container d-flex gap-4">
                   @for ($i = 0; $i < 8; $i++) 
                    <div class="card border-0 shadow-sm flex-shrink-0 p-3" style="width: 300px; height: 180px;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="placeholder col-2 rounded-circle bg-secondary" style="height: 40px; width: 40px;"></div>
                            <div class="ms-2 w-50">
                                <span class="placeholder col-8"></span>
                                <span class="placeholder col-5"></span>
                            </div>
                        </div>
                        <span class="placeholder col-12 mb-1"></span>
                        <span class="placeholder col-8"></span>
                    </div>
                   @endfor
              </div>
          </div>

          <button class="nav-btn next d-none d-md-flex ms-3" id="reviewNextBtn">
              <i class="bi bi-chevron-right fs-5"></i>
          </button>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-center align-items-center my-3 py-5">
    <div class="container d-flex align-items-center justify-content-center">
      <h4 class="text-muted text-center">🎬 Have you watched something recently? <br> Search and review it now!</h4>
    </div>
  </div>
  
  <x-footer />

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ================= CONFIGURATION & SELECTORS =================
        const DOM = {
            sections: {
                trending: document.getElementById('trendingSection'),
                popular: document.getElementById('popularSection'),
                toprated: document.getElementById('topratedSection'),
                reviews: document.getElementById('reviewsSection'),
                backdrop: document.getElementById('backdropSection'),
            },
            search: {
                box: document.getElementById('searchBox'),
                results: document.getElementById('searchResults'),
            }
        };

        // ================= 1. HELPER FUNCTIONS =================
        const getImageUrl = (path, size = 'w500') => 
            path ? `https://image.tmdb.org/t/p/${size}${path}` : 'https://placehold.co/500x750?text=No+Image';

        const formatDate = (dateString) => {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
        };

        const getYear = (dateString) => dateString ? `(${dateString.substring(0, 4)})` : '';

        // ================= 2. COMPONENT RENDERERS =================
        
        // A. Render Movie/TV Card (Potrait)
        const createMovieCard = (item) => {
            const title = item.title || item.name;
            const isTv = item.media_type === 'tv' || (!item.media_type && item.first_air_date);
            const route = isTv ? `/tv/${item.id}` : `/movie/${item.id}`;

            return `
                <a href="${route}" class="hover-card flex-shrink-0" title="${title}">
                    <img src="${getImageUrl(item.poster_path)}" 
                         class="img-fluid rounded shadow-sm" 
                         style="width:170px; height:255px; object-fit:cover;" 
                         alt="${title}" loading="lazy">
                </a>
            `;
        };

        // B. Render Review Card (Landscape with Snapshot Data)
        const createReviewCard = (review) => {
            // User Info
            const avatar = review.user && review.user.profile_picture 
                ? `/storage/${review.user.profile_picture}` 
                : 'https://ui-avatars.com/api/?background=random&name=' + (review.user ? review.user.name : 'User');
            const userName = review.user ? review.user.name : 'Anonymous';
            
            // Review Info
            const shortComment = review.comment.length > 80 ? review.comment.substring(0, 80) + '...' : review.comment;
            
            // Movie/TV Info (Dari Snapshot Database)
            const posterUrl = getImageUrl(review.media_poster, 'w154'); // Pakai ukuran kecil w154
            const mediaTitle = review.media_title || 'Unknown Title';
            const mediaYear = review.media_year ? `(${review.media_year})` : '';

            return `
            <a href="/reviews/${review.id}" class="text-decoration-none text-dark flex-shrink-0">
                <div class="card border-0 shadow-sm p-3 hover-card h-100" style="width: 320px; min-height: 220px; background: white; border-radius: 12px;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                         <div class="d-flex align-items-center">
                            <img src="${avatar}" class="rounded-circle border" width="30" height="30" style="object-fit: cover;">
                            <div class="ms-2 lh-1">
                                <div class="fw-bold" style="font-size: 0.8rem;">${userName}</div>
                                <small class="text-muted" style="font-size: 0.65rem;">${formatDate(review.created_at)}</small>
                            </div>
                         </div>
                         <div class="badge bg-warning text-dark d-flex align-items-center gap-1">
                            <i class="bi bi-star-fill" style="font-size: 0.7rem;"></i> ${review.rating}
                         </div>
                    </div>

                    <div class="d-flex align-items-center bg-light rounded p-2 mb-3 border">
                        <img src="${posterUrl}" class="rounded flex-shrink-0" width="40" height="60" style="object-fit: cover;">
                        <div class="ms-2 overflow-hidden">
                            <small class="text-secondary fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">REVIEWING</small>
                            <h6 class="mb-0 fw-bold text-truncate" style="font-size: 0.85rem;">${mediaTitle}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">${mediaYear}</small>
                        </div>
                    </div>

                    <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.95rem;">${review.title}</h6>
                    <p class="card-text text-muted small" style="line-height: 1.4; font-size: 0.85rem;">"${shortComment}"</p>
                    
                    <div class="mt-auto pt-2 border-top d-flex align-items-center gap-1 text-primary small fw-semibold">
                        Read full review <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </a>`;
        };

        // ================= 3. MAIN LOGIC =================
        
        const initHomePage = async () => {
            try {
                const response = await fetch('/api');
                const data = await response.json();

                // 1. Render Backdrop
                if (data.randomBackdrop && DOM.sections.backdrop) {
                    DOM.sections.backdrop.innerHTML = `
                        <img src="${data.randomBackdrop}" class="w-100 h-100 position-absolute top-0 start-0" 
                             style="object-fit: cover; filter: brightness(30%); z-index:1;">
                    `;
                }

                // 2. Render Movie Sections
                if (DOM.sections.trending) DOM.sections.trending.innerHTML = data.trending.map(createMovieCard).join('');
                if (DOM.sections.popular) DOM.sections.popular.innerHTML = data.popularMovies.map(createMovieCard).join('');
                if (DOM.sections.toprated) DOM.sections.toprated.innerHTML = data.tvTopRated.map(createMovieCard).join('');

                // 3. Render Reviews Section
                if (DOM.sections.reviews) {
                    if (data.latestReviews && data.latestReviews.length > 0) {
                        DOM.sections.reviews.innerHTML = data.latestReviews.slice(0, 15).map(createReviewCard).join('');
                    } else {
                        DOM.sections.reviews.innerHTML = `<div class="w-100 text-center text-muted py-4">No reviews yet. Be the first!</div>`;
                    }
                }

            } catch (error) {
                console.error('Error loading home data:', error);
            }
        };

        // ================= 4. CAROUSEL LOGIC =================
        const initScrollHandlers = () => {
            const setupSection = (sectionId, wrapperId, prevId, nextId, cardWidth, gap) => {
                const section = document.getElementById(sectionId);
                const wrapper = document.getElementById(wrapperId);
                const prevBtn = document.getElementById(prevId);
                const nextBtn = document.getElementById(nextId);

                if (!section || !wrapper || !prevBtn || !nextBtn) return;

                const scrollAmount = (cardWidth + gap) * 2; // Scroll 2 kartu sekaligus

                const handleShadows = () => {
                    const maxScroll = section.scrollWidth - section.clientWidth - 5;
                    wrapper.classList.toggle('show-shadow-left', section.scrollLeft > 5);
                    wrapper.classList.toggle('show-shadow-right', section.scrollLeft < maxScroll);
                };

                nextBtn.onclick = () => section.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                prevBtn.onclick = () => section.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                
                section.addEventListener('scroll', handleShadows);
                window.addEventListener('resize', handleShadows);
                setTimeout(handleShadows, 500); // Initial check
            };

            // Setup parameters: ID Section, ID Wrapper, ID Prev, ID Next, Card Width, Gap
            setupSection('trendingSection', 'trendingWrapper', 'trendingPrevBtn', 'trendingNextBtn', 170, 16);
            setupSection('popularSection', 'popularWrapper', 'popularPrevBtn', 'popularNextBtn', 170, 16);
            setupSection('topratedSection', 'topratedWrapper', 'topratedPrevBtn', 'topratedNextBtn', 170, 16);
            setupSection('reviewsSection', 'reviewsWrapper', 'reviewPrevBtn', 'reviewNextBtn', 320, 24); // Card Review lebih lebar
        };

        // ================= 5. SEARCH LOGIC =================
        const initSearch = () => {
            const { box, results } = DOM.search;
            if (!box || !results) return;

            let debounce;

            const renderResults = (items) => {
                if (items.length === 0) {
                    results.innerHTML = `<div class="p-3 text-center text-muted">No results found.</div>`;
                    return;
                }
                results.innerHTML = items.map(item => {
                    const title = item.title || item.name;
                    const type = item.media_type === 'movie' ? 'Movie' : 'TV Show';
                    const date = item.release_date || item.first_air_date;
                    const link = item.media_type === 'movie' ? `/movie/${item.id}` : `/tv/${item.id}`;
                    
                    return `
                        <a href="${link}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-2 border-0 border-bottom">
                            <img src="${getImageUrl(item.poster_path, 'w92')}" class="rounded" width="45" height="68" style="object-fit:cover;">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">${title}</h6>
                                <small class="text-muted">${type} • ${getYear(date)}</small>
                            </div>
                        </a>
                    `;
                }).join('');
            };

            const doSearch = async (query) => {
                if (!query) {
                    results.innerHTML = `<div class="p-3 text-center text-muted">Type to search...</div>`;
                    return;
                }
                
                results.classList.remove('d-none');
                results.innerHTML = `<div class="p-4 text-center"><div class="spinner-border text-primary spinner-border-sm"></div></div>`;

                try {
                    const res = await fetch(`/api/search?query=${encodeURIComponent(query)}`);
                    const data = await res.json();
                    renderResults(data);
                } catch (e) {
                    results.innerHTML = `<div class="p-3 text-center text-danger">Error loading results.</div>`;
                }
            };

            // Event Listeners
            box.addEventListener('focus', () => results.classList.remove('d-none'));
            
            box.addEventListener('input', (e) => {
                clearTimeout(debounce);
                debounce = setTimeout(() => doSearch(e.target.value.trim()), 500);
            });

            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!box.contains(e.target) && !results.contains(e.target)) {
                    results.classList.add('d-none');
                }
            });
        };

        // ================= START =================
        initHomePage();
        initScrollHandlers();
        initSearch();
    });
</script>

</body>
</html>