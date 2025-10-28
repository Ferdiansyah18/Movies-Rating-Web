<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | CinePals</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
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
          <img src="{{ $user->profile_picture ? asset('storage/'.$user->profile_picture) : 'https://via.placeholder.com/100' }}"
               class="rounded-circle object-fit-cover mb-3" width="100" height="100" alt="Profile Picture">
          <h5>{{ $user->name }}</h5>
          <p class="text-muted">{{ $user->email }}</p>
        </div>
      </div>

      <!-- Navigasi Dashboard -->
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

      <!-- Form Edit Profil -->
      <!-- <div class="card mb-4">
        <div class="card-header bg-primary text-white">Edit Profil</div>
        <div class="card-body">
          <form method="POST" action="{{ route('dashboard.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label>Nama</label>
              <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
              @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
              @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label>Foto Profil</label>
              <input type="file" name="profile_picture" class="form-control">
              @error('profile_picture') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </form>
        </div>
      </div> -->

      <!-- Form Ganti Password -->
      <!-- <div class="card">
        <div class="card-header bg-warning">Ganti Password</div>
        <div class="card-body">
          <form method="POST" action="{{ route('dashboard.password') }}">
            @csrf
            <div class="mb-3">
              <label>Password Sekarang</label>
              <input type="password" name="current_password" class="form-control" required>
              @error('current_password') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label>Password Baru</label>
              <input type="password" name="new_password" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Konfirmasi Password Baru</label>
              <input type="password" name="new_password_confirmation" class="form-control" required>
              @error('new_password') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-warning">Ganti Password</button>
          </form>
        </div>
      </div> -->

    </div>
  </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

</body>
</html>
