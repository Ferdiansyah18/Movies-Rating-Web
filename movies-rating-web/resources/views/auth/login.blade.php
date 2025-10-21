<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tambahkan Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .password-wrapper {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      border: none;
      background: none;
      cursor: pointer;
      color: #6c757d;
    }
    .toggle-password:hover {
      color: #000;
    }
  </style>
</head>
<body class="bg-light">

<div class="d-flex justify-content-center flex-wrap flex-md-nowrap">
  <!-- Gambar kiri (hanya tampil di md ke atas) -->
  <img src="{{ asset('image/login-banner.jpg') }}" 
       alt="Login Banner" 
       class="col-md-7 col-lg-8 col-xxl-9 d-none d-md-block" 
       style="height: 100vh; object-fit: cover; object-position: top;">

  <!-- Kolom kanan -->
  <div class="col-8 col-md-5 col-lg-4 col-xxl-3 d-flex align-items-start justify-content-center" style="min-height: 100vh;">
    <div class="w-100 h-auto mt-5 mt-md-4">
      <div class="p-4">
        <h4 class="text-center mb-4">Login</h4>

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
            @error('email')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label>Password</label>
            <div class="password-wrapper">
              <input type="password" name="password" id="password" class="form-control" required>
              <button type="button" class="toggle-password" id="togglePassword">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            @error('password')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="mt-3 text-center small">
          Don't have an account? <a href="{{ route('register') }}">Register</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
  const togglePassword = document.getElementById('togglePassword');
  const password = document.getElementById('password');
  const icon = togglePassword.querySelector('i');

  togglePassword.addEventListener('click', function () {
    const isPassword = password.getAttribute('type') === 'password';
    password.setAttribute('type', isPassword ? 'text' : 'password');
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
  });
</script>

</body>
</html>
