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
    document.addEventListener('DOMContentLoaded', async () => {
      // Elements Selection
      const trendingSection = document.getElementById('trendingSection');
      const popularSection = document.getElementById('popularSection');
      const topratedSection = document.getElementById('topratedSection');
      const reviewsSection = document.getElementById('reviewsSection'); 
      const backdropSection = document.getElementById('backdropSection');
      
      const searchBox = document.getElementById('searchBox');
      const searchResults = document.getElementById('searchResults');

      // ================= 1. FETCH DATA UTAMA =================
      try {
        const response = await fetch('/api'); 
        const data = await response.json();

        // 1.A Render Backdrop
        if (data.randomBackdrop) {
          backdropSection.innerHTML = `
            <img src="${data.randomBackdrop}" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: cover; filter: brightness(30%); z-index:1;">
          `;
        }

        // 1.B Helper Function Render Card
        const renderCards = (items) => items.map(item => {
            const title = item.title ?? item.name;
            let finalRoute = '/movie/' + item.id;
            
            if (item.media_type === 'tv') {
                finalRoute = '/tv/' + item.id;
            } else if (!item.media_type) {
                if (item.first_air_date) finalRoute = '/tv/' + item.id;
                else finalRoute = '/movie/' + item.id;
            }

            return `
              <a href="${finalRoute}" class="hover-card flex-shrink-0">
                <img src="https://image.tmdb.org/t/p/w500${item.poster_path}" class="img-fluid rounded shadow-sm" style="width:170px; height:255px; object-fit:cover;" alt="${title}">
              </a>
            `;
        }).join('');

        // 1.C Render Section Movies
        if(trendingSection) trendingSection.innerHTML = renderCards(data.trending);
        if(popularSection) popularSection.innerHTML = renderCards(data.popularMovies);
        if(topratedSection) topratedSection.innerHTML = renderCards(data.tvTopRated);

        // 1.D Render Reviews
        if (data.latestReviews && data.latestReviews.length > 0) {
            // Kita render lebih banyak agar scroll berfungsi (misal 15)
            const reviewsToRender = data.latestReviews.slice(0, 15); 

            if(reviewsSection) {
                reviewsSection.innerHTML = reviewsToRender.map(review => {
                    const avatar = review.user && review.user.profile_picture ? `/storage/${review.user.profile_picture}` : '/image/default-avatar.png';
                    const userName = review.user ? review.user.name : 'Anonymous';
                    const shortComment = review.comment.length > 80 ? review.comment.substring(0, 80) + '...' : review.comment;

                    return `
                    <a href="/reviews/${review.id}" class="text-decoration-none text-dark flex-shrink-0">
                        <div class="card border-0 shadow-sm p-3 hover-card h-100" style="width: 300px; min-height: 180px; background: white; border-radius: 12px;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                 <div class="d-flex align-items-center">
                                    <img src="${avatar}" class="rounded-circle object-fit-cover border" width="40" height="40" alt="${userName}">
                                    <div class="ms-2">
                                        <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">${userName}</h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">${new Date(review.created_at).toLocaleDateString()}</small>
                                    </div>
                                 </div>
                                 <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> ${review.rating}</span>
                            </div>
                            <h6 class="card-title fw-bold text-truncate mb-1">${review.title}</h6>
                            <p class="card-text text-muted small" style="line-height: 1.4;">"${shortComment}"</p>
                            <div class="mt-auto d-flex align-items-center gap-1 text-primary small fw-semibold">Read full review <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </a>
                    `;
                }).join('');
            }
        } else {
            if(reviewsSection) reviewsSection.innerHTML = `<p class="text-muted w-100 text-center">No reviews yet. Be the first to write one!</p>`;
        }

      } catch (error) {
        console.error('Error fetching home data:', error);
      }

      // ================= 2. UNIVERSAL SCROLL & SHADOW LOGIC =================
      // Fungsi ini digunakan untuk semua section agar tidak mengulang kode
      
      const setupScrollSection = (sectionId, wrapperId, prevBtnId, nextBtnId, cardWidth, cardGap) => {
          const section = document.getElementById(sectionId);
          const wrapper = document.getElementById(wrapperId);
          const prevBtn = document.getElementById(prevBtnId);
          const nextBtn = document.getElementById(nextBtnId);

          if (!section || !wrapper || !prevBtn || !nextBtn) return;

          const scrollStep = (cardWidth + cardGap) * 2; // Slide 2 kartu

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

      // --- SETUP SEMUA SECTION ---
      
      // 1. Trending (Card 170px + Gap 1rem/16px)
      setupScrollSection('trendingSection', 'trendingWrapper', 'trendingPrevBtn', 'trendingNextBtn', 170, 16);

      // 2. Popular (Card 170px + Gap 1rem/16px)
      setupScrollSection('popularSection', 'popularWrapper', 'popularPrevBtn', 'popularNextBtn', 170, 16);

      // 3. Top Rated (Card 170px + Gap 1rem/16px)
      setupScrollSection('topratedSection', 'topratedWrapper', 'topratedPrevBtn', 'topratedNextBtn', 170, 16);

      // 4. Reviews (Card 300px + Gap 1.5rem/24px)
      setupScrollSection('reviewsSection', 'reviewsWrapper', 'reviewPrevBtn', 'reviewNextBtn', 300, 24);


      // ================= 3. SEARCH LOGIC =================
      let debounceTimer;

      const performSearch = async (query) => {
          if (!query) {
              searchResults.innerHTML = `
                  <div class="p-3 text-center text-muted">
                      <i class="bi bi-search mb-2 fs-4 d-block"></i>
                      <span>Type something to search...</span>
                  </div>
              `;
              searchResults.classList.remove('d-none');
              return;
          }

          searchResults.classList.remove('d-none');
          searchResults.innerHTML = `
              <div class="p-4 text-center">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
              </div>
          `;
          
          try {
              const res = await fetch(`/api/search?query=${encodeURIComponent(query)}`);
              if (!res.ok) throw new Error('Network response was not ok');
              const results = await res.json();

              if (results.length === 0) {
                  searchResults.innerHTML = `
                      <div class="p-3 text-center text-muted">
                          <i class="bi bi-emoji-frown mb-2 fs-4 d-block"></i>
                          <span>No results found for "${query}"</span>
                      </div>
                  `;
              } else {
                  searchResults.innerHTML = results.map(item => {
                      const type = item.media_type === 'movie' ? 'Movie' : 'TV Show';
                      const title = item.title || item.name;
                      const year = (item.release_date || item.first_air_date || '').substring(0, 4);
                      const img = item.poster_path ? `https://image.tmdb.org/t/p/w92${item.poster_path}` : 'https://placehold.co/45x68?text=No+Img';
                      const link = item.media_type === 'movie' ? `/movie/${item.id}` : `/tv/${item.id}`;

                      return `
                          <a href="${link}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-2 border-0 border-bottom">
                              <img src="${img}" class="rounded" width="45" height="68" style="object-fit:cover;">
                              <div>
                                  <h6 class="mb-0 fw-bold text-dark">${title}</h6>
                                  <small class="text-muted">${type} • ${year}</small>
                              </div>
                          </a>
                      `;
                  }).join('');
              }
          } catch (err) {
              console.error(err);
              searchResults.innerHTML = `<div class="p-3 text-center text-danger">Failed to load results.</div>`;
          }
      };

      if(searchBox) {
          searchBox.addEventListener('focus', () => {
              if (searchBox.value.trim() === '') performSearch(''); 
              else searchResults.classList.remove('d-none');
          });

          searchBox.addEventListener('input', (e) => {
              clearTimeout(debounceTimer);
              const query = e.target.value.trim();
              if (query === '') { performSearch(''); return; }
              debounceTimer = setTimeout(() => { performSearch(query); }, 500);
          });

          document.addEventListener('click', (e) => {
              if (!searchBox.contains(e.target) && !searchResults.contains(e.target)) {
                  searchResults.classList.add('d-none');
              }
          });
      }

    });
  </script>

</body>
</html>