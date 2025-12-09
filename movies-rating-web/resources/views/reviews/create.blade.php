<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write a Review | CinePals</title>
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('image/favicon_io/android-chrome-512x512.png') }}" type="image/x-icon">
    
    {{-- Bootstrap CSS & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        body { background-color: #f8f9fa; }
        .form-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .rating-select { font-size: 1.1rem; padding: 10px; }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <x-navbar textColor="text-dark"/>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                {{-- Header / Back Button --}}
                <div class="mb-4">
                    <a href="{{ url()->previous() }}" class="text-decoration-none text-muted fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Cancel & Go Back
                    </a>
                </div>

                {{-- Form Card --}}
                <div class="card form-card bg-white p-4 p-md-5">
                    <h2 class="fw-bold mb-1">Write your review</h2>
                    <p class="text-muted mb-4">Share your thoughts with the CinePals community.</p>

                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        {{-- Hidden Inputs --}}
                        <input type="hidden" name="item_id" value="{{ $id }}">
                        <input type="hidden" name="item_type" value="{{ $type }}">

                        {{-- 1. Rating --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Your Rating</label>
                            <div class="input-group">
                                <span class="input-group-text bg-warning text-dark border-0">
                                    <i class="bi bi-star-fill"></i>
                                </span>
                                <select name="rating" class="form-select rating-select bg-light border-0" required>
                                    <option value="" disabled selected>Select a score (1-10)</option>
                                    <option value="10">10 - Masterpiece (Sempurna)</option>
                                    <option value="9">9 - Amazing (Luar Biasa)</option>
                                    <option value="8">8 - Great (Sangat Bagus)</option>
                                    <option value="7">7 - Good (Bagus)</option>
                                    <option value="6">6 - Decent (Lumayan)</option>
                                    <option value="5">5 - Average (Rata-rata)</option>
                                    <option value="4">4 - Mediocre (Biasa Saja)</option>
                                    <option value="3">3 - Bad (Buruk)</option>
                                    <option value="2">2 - Terrible (Sangat Buruk)</option>
                                    <option value="1">1 - Abysmal (Hancur)</option>
                                </select>
                            </div>
                        </div>

                        {{-- 2. Headline --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Headline</label>
                            <input type="text" name="title" class="form-control form-control-lg bg-light border-0" 
                                   placeholder="Give a catchy title for your review" required>
                        </div>

                        {{-- 3. Review Body --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Review</label>
                            <textarea name="comment" class="form-control bg-light border-0" rows="8" 
                                      placeholder="What did you like or dislike? How was the plot, acting, or visuals?" required></textarea>
                            <div class="form-text">Your review should be honest and respectful.</div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg py-3 fw-bold">
                                Post Review <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Footer --}}
    <x-footer/>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>