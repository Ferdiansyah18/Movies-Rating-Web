import 'bootstrap/dist/js/bootstrap.bundle.min.js';

document.addEventListener("DOMContentLoaded", () => {
    // ---------------------------
    // Navbar scroll effect
    // ---------------------------
    const navbar = document.getElementById("mainNavbar");
    const navLinks = navbar.querySelectorAll(".nav-link, .navbar-brand, .navbar-user span");

    // Simpan warna awal di data-color
    navLinks.forEach(link => {
        if (link.classList.contains("text-dark")) link.dataset.color = "text-dark";
        else if (link.classList.contains("text-light")) link.dataset.color = "text-light";
    });

    const handleNavbarScroll = () => {
        if (window.scrollY > 50) {
            navbar.classList.add("bg-dark");
            navLinks.forEach(link => {
                link.classList.remove("text-dark");
                link.classList.add("text-light");
            });
        } else {
            navbar.classList.remove("bg-dark");
            navLinks.forEach(link => {
                link.classList.remove("text-light", "text-dark");
                if (link.dataset.color) link.classList.add(link.dataset.color);
            });
        }
    };

    // Jalankan saat scroll dan sekali saat load
    window.addEventListener("scroll", handleNavbarScroll);
    handleNavbarScroll();

    // ---------------------------
    // Live Search
    // ---------------------------
    const searchBox = document.getElementById('searchBox');
    const searchResults = document.getElementById('searchResults');

    const movieRouteTemplate = document.getElementById('movieRoute').href;
    const tvRouteTemplate = document.getElementById('tvRoute').href;

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        }
    }

    const fetchResults = debounce(function(query) {
        if (query.length < 2) {
            searchResults.innerHTML = '';
            return;
        }

        fetch(`/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    searchResults.innerHTML = '';
                    return;
                }

                searchResults.innerHTML = data.map(item => {
                    const url = item.media_type === 'movie'
                        ? movieRouteTemplate.replace('REPLACE_ID', item.id)
                        : tvRouteTemplate.replace('REPLACE_ID', item.id);

                    return `
                        <a href="${url}" class="list-group-item list-group-item-action d-flex align-items-center">
                            ${item.poster ? `<img src="${item.poster}" class="me-2" width="40" height="60">` : ''}
                            <div>
                                <div>${item.title}</div>
                                <small class="text-muted">
                                    ${item.media_type === 'movie' ? '🎬 Movie' : '📺 TV Series'}
                                </small>
                            </div>
                        </a>
                    `;
                }).join('');
            })
            .catch(err => {
                console.error(err);
                searchResults.innerHTML = '';
            });
    }, 400);

    searchBox.addEventListener('input', function () {
        fetchResults(this.value.trim());
    });

    document.addEventListener('click', (e) => {
        if (!searchResults.contains(e.target) && e.target !== searchBox) {
            searchResults.innerHTML = '';
        }
    });
});

// Favourite and Watchlist Button
    document.addEventListener('DOMContentLoaded', function() {
        const favBtn = document.getElementById('favouriteBtn');
        const watchBtn = document.getElementById('watchlistBtn');

        favBtn.addEventListener('click', () => {
            favBtn.classList.toggle('btn-danger');
            favBtn.classList.toggle('btn-outline-danger');
            const icon = favBtn.querySelector('i');
            icon.classList.toggle('bi-heart');
            icon.classList.toggle('bi-heart-fill');
        });

        watchBtn.addEventListener('click', () => {
            watchBtn.classList.toggle('btn-warning');
            watchBtn.classList.toggle('btn-outline-warning');
            const icon = watchBtn.querySelector('i');
            icon.classList.toggle('bi-bookmark');
            icon.classList.toggle('bi-bookmark-fill');
        });
    });