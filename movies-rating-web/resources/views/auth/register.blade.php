<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="text-center mb-4">Daftar Akun Baru</h4>
          <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
              <label>Nama</label>
              <input type="text" name="name" class="form-control" required>
              @error('name')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
              @error('email')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Konfirmasi Password</label>
              <input type="password" name="password_confirmation" class="form-control" required>
              @error('password')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-success w-100">Daftar</button>
          </form>
          <p class="mt-3 text-center small">
            Sudah punya akun? <a href="{{ route('login') }}">Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
