<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>MotoPart Register</title>
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
            <div class="col-md-4">
                <div class="text-center mb-2">
                    <a href="/" class="text-decoration-none d-inline-flex align-items-center gap-2">
                        <i class="bi bi-wrench-adjustable-circle-fill fs-2" style="color: #DC2626"></i>
                        <span class="fs-4 fw-bold text-white">MotoPart<span style="color: #DC2626">Store</span></span>
                    </a>
                </div>
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-center mb-1">Daftar Akun Baru</h4>
                        <p class="text-muted small text-center mb-3">Buat pemesanan pertama Anda</p>
                        @if ($errors->any())
                        <div class="alert alert-danger py-2 small">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="/register" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-emoji-kiss"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0" placeholder="Subianto Prabowo" required>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@email.com" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                     <input type="password" name="password" class="form-control" required minlength="6" placeholder="Min. 6 Karakter">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-crimson w-100">Daftar</button>
                        </form>
                        <p class="small text-center mt-3 mb-0">
                            Sudah punya akun? <a href="/login" class="fw-semibold text-decoration-none" style="color: #DC2626;">Login di sini</a>
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