@vite(['resources/css/app.css', 'resources/js/app.js'])
@props(['textColor' => 'text-light']) {{-- default text-light --}}

<div class="z-3 position-fixed w-100 top-0" id="mainNavbar">
    <nav class="navbar navbar-expand-lg navbar-dark px-md-5">
        <div class="container-fluid">
            {{-- Brand --}}
            <a class="navbar-brand {{ $textColor }}" href="{{ url('/') }}">LuminaFlick</a>

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
                    <a class="nav-link {{ $textColor }}" href="#">Movies</a>
                    <a class="nav-link {{ $textColor }}" href="#">TV Shows</a>
                </div>

                {{-- User Section --}}
                <div class="navbar-user d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none gap-2">
                            <img 
                                src="{{ auth()->user()->profile_picture 
                                      ? asset('storage/' . auth()->user()->profile_picture) 
                                      : 'https://via.placeholder.com/35' }}" 
                                alt="Profile" 
                                class="rounded-circle object-fit-cover" 
                                width="35" height="35">
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
