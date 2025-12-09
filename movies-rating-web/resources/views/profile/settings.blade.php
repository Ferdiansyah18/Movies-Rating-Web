<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings | CinePals</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="bg-light">
  <x-navbar-dashboard textColor="text-light" position="position-sticky"/>

  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">

        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div>
          <h3>Edit Profile</h3>
          <div class="row p-3">
            <div class="col-lg-8 col-md-9 col-sm-12 order-md-1 order-2">
              <form method="POST" action="{{ route('dashboard.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label class="form-label">Name</label>
                  <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Profile Picture</label>
                  <input type="file" name="profile_picture" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary w-100">Save Changes</button>
              </form>
            </div>

            <div class="col-lg-4 col-md-3 col-sm-12 text-center mb-3 order-md-2 order-1 d-flex align-items-center justify-content-center">
              
              {{-- LOGIKA FOTO PROFIL --}}
              @if($user->profile_picture)
                  {{-- Jika ada foto --}}
                  <img src="{{ asset('storage/'.$user->profile_picture) }}"
                       class="rounded-circle object-fit-cover mb-3"
                       width="100" height="100"
                       alt="Profile Picture">
              @else
                  {{-- Jika TIDAK ada foto (Icon Default) --}}
                  <div class="mb-3">
                      <i class="bi bi-person-circle text-secondary" style="font-size: 100px; line-height: 1;"></i>
                  </div>
              @endif

            </div>
          </div>
        </div>

        <div class="mt-3 pt-3 border-top">
          <h3>Change Password</h3>
          <div class="row p-3">
            <div class="col-12">
              <form id="passwordForm" method="POST" action="{{ route('dashboard.password') }}">
                @csrf
                <div class="mb-3">
                  <label class="form-label">Current Password</label>
                  <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">New Password</label>
                  <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Confirm New Password</label>
                  <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-outline-danger w-100">Change Password</button>
              </form>
            </div>
          </div>
        </div>

        <div class="text-center mt-4">
          <a href="{{ route('dashboard') }}" class="text-decoration-none text-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
          </a>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
      event.preventDefault(); // cegah submit langsung

      Swal.fire({
        title: 'Yakin ingin mengubah password?',
        text: "Pastikan kamu ingat password barumu.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, ubah!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit(); // submit form jika dikonfirmasi
        }
      });
    });
  </script>
</body>
</html>