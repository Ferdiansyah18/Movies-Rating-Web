@vite(['resources/css/app.css', 'resources/js/app.js'])
@props(['textColor' => 'text-light'])
@props(['position' => 'position-fixed'])

<div class="{{ $position }} w-100 top-0" id="mainNavbar" style="z-index: 9999999;">
    <nav class="navbar navbar-expand-lg navbar-dark px-md-5">
        <div class="container-fluid">
            {{-- Brand --}}
            <a class="navbar-brand {{ $textColor }}" href="{{ url('/') }}">CinePals</a>

            {{-- Toggler --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navbar links + User --}}
            <div class="collapse navbar-collapse d-lg-flex justify-content-between" id="navbarNavAltMarkup">
                {{-- Links --}}
                <div class="navbar-nav">
                    <a class="nav-link {{ $textColor }}" href="{{ route('movies.discover') }}">Movies</a>
                    <a class="nav-link {{ $textColor }}" href="{{ route('tv.discover') }}">TV Shows</a>
                </div>

                {{-- User Section --}}
                <div class="navbar-user d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none gap-2">
                            
{{-- LOGIKA FOTO PROFIL --}}
@if(auth()->user()->profile_picture)
    {{-- 1. Jika user punya foto custom di storage --}}
    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
         alt="{{ auth()->user()->name }}" 
         class="rounded-circle object-fit-cover" 
         width="35" height="35">
@else
    {{-- 2. Jika user BELUM punya foto -> Pakai UI Avatars (Inisial Nama) --}}
    {{-- Fungsi urlencode() penting agar spasi di nama terbaca benar oleh URL --}}
    <img src="https://ui-avatars.com/api/?background=random&name={{ urlencode(auth()->user()->name) }}" 
         alt="{{ auth()->user()->name }}" 
         class="rounded-circle object-fit-cover" 
         width="35" height="35">
@endif

                            <span class="{{ $textColor }}">{{ auth()->user()->name }}</span>
                        </a>
                    @else
                        <div class="d-flex gap-4">
                            <a href="{{ route('login') }}" class="nav-link {{ $textColor }}">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="nav-link {{ $textColor }}">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>