<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MotoPart Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #1E293B;
        }

        .btn-crimson {
            background: #DC2626;
            color: #fff;
            border: none;
        }

        .btn-crimson:hover {
            background: #B91C1C;
            color: #fff;
        }
    </style>
</head>

<body class="d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-3">
                    <a href="/" class="text-decoration-none d-inline-flex align-items-center gap-2">
                        <i class="bi bi-wrench-adjustable-circle-fill fs-2" style="color: #DC2626"></i>
                        <span class="fs-4 fw-bold text-white">MotoPart<span style="color: #DC2626">Store</span></span>
                    </a>
                </div>
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-center mb-1">Masuk Akun</h5>
                        <p class="text-muted small text-center mb-3">Akses pesanan & booking bengkel Anda</p>

                        @if(session('error'))
                        <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
                        @elseif(session('success'))
                        <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                        @elseif(request('pesan') == 'belum_login')
                        <div class="alert alert-warning py-2 small" id="notif">Silakan login terlebih dahulu.</div>
                        @endif

                        <form action="/login" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" required value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" placeholder="passwd" required minlength="6">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-crimson w-100 py-2 fw-semibold rounded-3">Masuk</button>
                        </form>
                        <p class="text-center small mt-3 mb-0">
                            Belum punya akun? <a href="/register" class="fw-semibold text-decoration-none" style="color: #DC2626">Daftar di sini</a>
                        </p>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="/" class="text-secondary small text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>