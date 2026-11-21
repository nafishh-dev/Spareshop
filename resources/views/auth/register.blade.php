<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - MotoPart Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            min-height: 100dvh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #1E293B;
            color: #0F172A;
        }

        a[href],
        button,
        label[for] {
            cursor: pointer;
        }

        :disabled {
            cursor: not-allowed;
        }

        .auth-shell {
            padding-block: clamp(1.25rem, 4vh, 2.5rem);
        }

        .auth-card-body {
            padding: clamp(1.25rem, 4vw, 2rem);
        }

        .auth-subtitle {
            color: #475569;
        }

        .brand-link {
            min-height: 44px;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
        }

        .brand-link:hover .brand-title {
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .brand-link:focus-visible,
        .back-link:focus-visible {
            outline: 3px solid #F8FAFC;
            outline-offset: 3px;
        }

        .input-group-text,
        .form-control {
            min-height: 46px;
            font-size: 1rem;
            border-color: #64748B;
        }

        .input-group-text {
            background-color: #F8FAFC;
            color: #475569;
        }

        .form-control::placeholder {
            color: #64748B;
        }

        .form-control:focus {
            border-color: #B91C1C;
            box-shadow: 0 0 0 3px rgba(185, 28, 28, 0.25);
            z-index: 3;
        }

        .btn-crimson {
            min-height: 46px;
            font-size: 1rem;
            background-color: #DC2626;
            color: #FFFFFF;
            border: 2px solid #DC2626;
        }

        .btn-crimson:hover {
            background-color: #B91C1C;
            border-color: #B91C1C;
            color: #FFFFFF;
        }

        .btn-crimson:active {
            background-color: #991B1B !important;
            border-color: #991B1B !important;
            color: #FFFFFF !important;
        }

        .btn-crimson:focus-visible {
            background-color: #B91C1C;
            border-color: #0F172A;
            color: #FFFFFF;
            outline: 3px solid #DC2626;
            outline-offset: 2px;
            box-shadow: none;
        }

        .btn-crimson:disabled {
            background-color: #94A3B8;
            border-color: #94A3B8;
            color: #FFFFFF;
        }

        .auth-link {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            padding: 0.25rem 0.375rem;
            color: #B91C1C;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 3px;
            border-radius: 0.375rem;
        }

        .auth-link:hover {
            color: #991B1B;
            background-color: #FEF2F2;
        }

        .auth-link:active {
            color: #7F1D1D;
        }

        .auth-link:focus-visible {
            outline: 3px solid #B91C1C;
            outline-offset: 2px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            padding: 0.375rem 0.75rem;
            color: #CBD5E1;
            text-decoration: none;
            border-radius: 0.5rem;
        }

        .back-link:hover {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.08);
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .back-link:active {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.14);
        }

        @media (prefers-reduced-motion: no-preference) {
            .btn-crimson:active,
            .auth-link:active,
            .back-link:active {
                transform: translateY(1px);
            }
        }
    </style>
</head>

<body class="d-flex flex-column justify-content-center auth-shell">
    <div class="container px-3">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-7 col-lg-5 col-xl-4">
                <header class="text-center mb-3">
                    <a href="/" class="brand-link text-decoration-none d-inline-flex align-items-center gap-2">
                        <i class="bi bi-wrench-adjustable-circle-fill fs-2" style="color: #DC2626" aria-hidden="true"></i>
                        <span class="brand-title fs-4 fw-bold text-white">MotoPart<span style="color: #DC2626">Store</span></span>
                    </a>
                </header>

                <main>
                    <div class="card border-0 shadow-lg rounded-4">
                        <div class="card-body auth-card-body">
                            <h1 class="h4 fw-bold text-center mb-1">Daftar Akun Baru</h1>
                            <p class="auth-subtitle small text-center mb-3">Buat pemesanan pertama Anda</p>

                            @if ($errors->any())
                                <div class="alert alert-danger py-2 small" role="alert">
                                    <div class="fw-semibold mb-1">
                                        <i class="bi bi-exclamation-octagon-fill me-1" aria-hidden="true"></i>Periksa kembali input Anda:
                                    </div>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="/register" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="register-name" class="form-label small fw-semibold">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0"><i class="bi bi-emoji-kiss" aria-hidden="true"></i></span>
                                        <input type="text" id="register-name" name="name" class="form-control border-start-0" placeholder="Nama lengkap Anda" required autocomplete="name" value="{{ old('name') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="register-email" class="form-label small fw-semibold">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                                        <input type="email" id="register-email" name="email" class="form-control border-start-0" placeholder="nama@email.com" required autocomplete="email" value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="register-password" class="form-label small fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0"><i class="bi bi-lock" aria-hidden="true"></i></span>
                                        <input type="password" id="register-password" name="password" class="form-control border-start-0" required minlength="6" placeholder="Minimal 6 karakter" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="register-password-confirmation" class="form-label small fw-semibold">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0"><i class="bi bi-lock" aria-hidden="true"></i></span>
                                        <input type="password" id="register-password-confirmation" name="password_confirmation" class="form-control border-start-0" required minlength="6" placeholder="Ulangi password" autocomplete="new-password">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-crimson w-100 fw-semibold rounded-3">Daftar</button>
                            </form>

                            <p class="small text-center mt-2 mb-0">
                                Sudah punya akun? <a href="/login" class="auth-link">Login di sini</a>
                            </p>
                        </div>
                    </div>
                </main>

                <footer class="text-center mt-2">
                    <a href="/" class="back-link small">
                        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali ke Beranda
                    </a>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>
