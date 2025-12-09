<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | CinePals</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<x-navbar-dashboard textColor="text-light" position="position-sticky"/>

<div class="container ">
  <div class="row justify-content-center">
    <div class="col-md-8">

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="card mb-3">
        <div class="card-body text-center">
          
          {{-- LOGIKA FOTO PROFIL --}}
          @if($user->profile_picture)
             {{-- Jika user punya foto --}}
             <img src="{{ asset('storage/'.$user->profile_picture) }}"
                  class="rounded-circle object-fit-cover mb-3" 
                  width="100" height="100" 
                  alt="Profile Picture">
          @else
             {{-- Jika user BELUM punya foto (Default Icon) --}}
             {{-- style font-size 100px diset agar ukurannya sama persis dengan width="100" pada img --}}
             <div class="mb-3">
                 <i class="bi bi-person-circle text-secondary" style="font-size: 100px; line-height: 1;"></i>
             </div>
          @endif

          <h5>{{ $user->name }}</h5>
          <p class="text-muted">{{ $user->email }}</p>
        </div>
      </div>

      <div class="card mb-4 shadow-sm">
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            <li class="list-group-item p-0">
              <a href="{{ route('favourites.index') }}" class="btn btn-light w-100 text-start py-3 px-4 d-flex align-items-center">
                <i class="bi bi-heart-fill me-2 text-danger"></i> Favourites
              </a>
            </li>
            <li class="list-group-item p-0">
              <a href="{{ route('watchlists.index') }}" class="btn btn-light w-100 text-start py-3 px-4 d-flex align-items-center">
                <i class="bi bi-bookmark-fill me-2 text-warning"></i> Watchlist
              </a>
            </li>
            <li class="list-group-item p-0">
              <a href="{{ route('profile.settings') }}" class="btn btn-light w-100 text-start py-3 px-4 d-flex align-items-center">
                <i class="bi bi-gear-fill me-2 text-secondary"></i> Settings
              </a>
            </li>
          </ul>
        </div>
      </div>

      </div>
  </div>
</div>

</body>
</html>