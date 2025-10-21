@vite(['resources/css/app.css', 'resources/js/app.js'])
@props(['textColor' => 'text-light'])
@props(['position' => 'position-fixed'])

<div class="z-3 {{ $position }} w-100 top-0" id="mainNavbar">
    <nav class="navbar navbar-expand-lg navbar-dark px-md-5 bg-dark">
        <div class="container-fluid">
            {{-- Brand --}}
            <a class="navbar-brand {{ $textColor }}" href="{{ url('/') }}">CinePals</a>

            {{-- Toggler --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navbar Links --}}
            <div class="collapse navbar-collapse d-lg-flex justify-content-between" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link {{ $textColor }}" href="{{ route('movies.discover') }}">Movies</a>
                    <a class="nav-link {{ $textColor }}" href="{{ route('tv.discover') }}">TV Shows</a>
                </div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('logout') }}" class="ms-lg-auto">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>
</div>
