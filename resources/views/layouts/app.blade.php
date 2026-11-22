<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MotoPart Store - Suku Cadang & Booking Bengkel')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F8FAFC;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1E293B;
        }
        a[href],
        button,
        summary,
        select,
        label[for],
        input[type="checkbox"],
        input[type="radio"] {
            cursor: pointer;
        }
        :disabled {
            cursor: not-allowed;
        }
        .tap-target {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .navbar-motopart {
            background-color: #1E293B;
            border-bottom: 3px solid #DC2626;
        }
        .navbar-motopart .nav-link {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            padding-inline: 0.75rem;
            border-radius: 0.5rem;
        }
        .navbar-motopart .navbar-brand {
            min-height: 44px;
        }
        .navbar-motopart a:focus-visible,
        .navbar-motopart button:focus-visible,
        .navbar-motopart select:focus-visible,
        .footer-motopart a:focus-visible {
            outline: 3px solid #F8FAFC;
            outline-offset: 2px;
        }
        .nav-motor-select {
            min-height: 44px;
            width: 100%;
            font-size: 0.9375rem;
        }
        .nav-motor-addon {
            min-height: 44px;
        }
        .nav-reset-btn {
            min-height: 44px;
            min-width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 991.98px) {
            .navbar-motopart .navbar-toggler {
                touch-action: manipulation;
            }
            .navbar-motopart .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                z-index: 1020;
                background-color: #1E293B;
                border-top: 1px solid rgba(255, 255, 255, 0.08);
                border-bottom: 3px solid #DC2626;
                box-shadow: 0 12px 24px rgba(15, 23, 42, 0.35);
                max-height: calc(100dvh - 68px);
                overflow-y: auto;
                overscroll-behavior: contain;
            }
            .navbar-motopart .navbar-collapse.collapsing {
                overflow: hidden;
                contain: layout style;
                transition: height 0.28s ease-out;
            }
            .navbar-motopart .nav-collapse-inner {
                width: 100%;
                margin-inline: auto;
                padding: 0.75rem 0.75rem 1rem;
            }
            .navbar-motopart .dropdown-menu {
                position: static;
                margin-top: 0.35rem;
            }
        }
        @media (min-width: 576px) and (max-width: 767.98px) {
            .navbar-motopart .nav-collapse-inner {
                max-width: 540px;
            }
        }
        @media (min-width: 768px) and (max-width: 991.98px) {
            .navbar-motopart .nav-collapse-inner {
                max-width: 720px;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .navbar-motopart .navbar-collapse.collapsing {
                transition: none;
            }
        }
        @media (min-width: 992px) {
            .navbar-motopart .nav-collapse-inner {
                display: contents;
            }
            .navbar-motopart .btn.tap-target {
                min-height: 38px;
                padding-block: 0.35rem;
                padding-inline: 0.8rem !important;
                font-size: 0.9rem;
            }
            .navbar-motopart .nav-link {
                min-height: 38px;
                padding-inline: 0.65rem;
                font-size: 0.9375rem;
            }
            .navbar-motopart .navbar-brand {
                min-height: 38px;
            }
            .nav-motor-select {
                min-height: 38px;
                padding-block: 0.35rem;
                max-width: 210px;
                font-size: 0.9rem;
            }
            .nav-motor-addon {
                min-height: 38px;
                padding-block: 0.35rem;
            }
            .nav-reset-btn {
                min-height: 38px;
                min-width: 38px;
                padding: 0.35rem 0.6rem;
            }
        }
        .text-crimson {
            color: #DC2626 !important;
        }
        .bg-crimson {
            background-color: #DC2626 !important;
        }
        .btn-crimson {
            background-color: #DC2626;
            border: 2px solid #DC2626;
            color: #FFFFFF;
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
        .pagination {
            --bs-pagination-active-bg: #DC2626;
            --bs-pagination-active-border-color: #DC2626;
            --bs-pagination-color: #DC2626;
            --bs-pagination-hover-color: #B91C1C;
            --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(220, 38, 38, 0.25);
        }
        .footer-motopart {
            background-color: #0F172A;
            color: #94A3B8;
        }
        .footer-motopart a {
            color: #94A3B8;
            text-decoration: none;
        }
        .footer-motopart a:hover {
            color: #FFFFFF;
        }
        @media (prefers-reduced-motion: no-preference) {
            .btn-crimson:active {
                transform: translateY(1px);
            }
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Sticky Top Header & Navbar -->
    <header class="sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-motopart py-2 shadow-sm" aria-label="Navigasi utama">
            <div class="container">
                <!-- Left: Brand Logo -->
                <a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <i class="bi bi-wrench-adjustable-circle-fill text-crimson fs-3" aria-hidden="true"></i>
                    <span><span class="text-white">MotoPart</span><span class="text-crimson">Store</span></span>
                </a>

                <button class="navbar-toggler tap-target gap-2 px-3 border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMotoPart" aria-controls="navbarMotoPart" aria-expanded="false" aria-label="Buka menu navigasi">
                    <span class="navbar-toggler-icon"></span>
                    <span class="small fw-semibold text-white">Menu</span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMotoPart">
                    <div class="nav-collapse-inner">
                        <!-- Center: Navigation & "Garasi Saya" Session Widget -->
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-2 ms-lg-2">
                            <li class="nav-item">
                                <a class="nav-link text-nowrap {{ request()->routeIs('katalog.*') ? 'active fw-semibold text-white' : '' }}" href="{{ route('katalog.index') }}">
                                    <i class="bi bi-grid-fill me-1 text-crimson" aria-hidden="true"></i> Katalog
                                </a>
                            </li>
                            <li class="nav-item w-100 w-lg-auto">
                                @php $navMotors = \App\Models\Motorcycle::all(); @endphp
                                <form action="{{ route('katalog.index') }}" method="GET" class="d-flex align-items-center gap-2 w-100">
                                    <label for="nav-motorcycle-id" class="visually-hidden">Pilih Motor Anda</label>
                                    <div class="input-group flex-nowrap flex-grow-1">
                                        <span class="input-group-text nav-motor-addon bg-dark text-crimson border-secondary">
                                            <i class="bi bi-bicycle" aria-hidden="true"></i>
                                        </span>
                                        <select id="nav-motorcycle-id" name="motorcycle_id" class="form-select nav-motor-select bg-dark text-white border-secondary" onchange="this.form.submit()">
                                            <option value="">Semua Tipe Motor</option>
                                            @foreach($navMotors as $m)
                                                <option value="{{ $m->id }}" {{ session('selected_motorcycle_id') == $m->id ? 'selected' : '' }}>
                                                    {{ $m->model_name }} ({{ $m->production_year }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if(session('selected_motorcycle_id'))
                                        <a href="{{ route('katalog.resetMotor') }}" class="btn btn-outline-danger nav-reset-btn" title="Reset Filter Motor" aria-label="Reset filter motor">
                                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </form>
                            </li>
                        </ul>

                        <!-- Right: Auth & Cart Menu -->
                        <div class="d-grid d-sm-flex align-items-center gap-2 mt-3 mt-lg-0 flex-wrap flex-lg-nowrap">
                            @auth
                                @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('qty'); @endphp
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-light tap-target px-3 position-relative text-nowrap">
                                    <i class="bi bi-cart3 me-1" aria-hidden="true"></i>Keranjang
                                    @if($cartCount > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-crimson">
                                            {{ $cartCount }}
                                        </span>
                                    @endif
                                </a>

                                <a href="{{ route('orders.index') }}" class="btn btn-outline-light tap-target px-3 text-nowrap">
                                    <i class="bi bi-receipt me-1" aria-hidden="true"></i>Pesanan Saya
                                </a>

                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-warning tap-target px-3 fw-semibold text-dark text-nowrap">
                                        <i class="bi bi-speedometer2 me-1" aria-hidden="true"></i>Panel Admin
                                    </a>
                                @endif

                                <div class="dropdown">
                                    <button class="btn btn-dark tap-target px-3 dropdown-toggle d-flex align-items-center gap-2 border-secondary text-nowrap w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle text-crimson" aria-hidden="true"></i>
                                        <span>{{ auth()->user()->name }}</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                                <i class="bi bi-person-vcard me-1 text-crimson" aria-hidden="true"></i> Profil Saya
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item py-2 text-danger">
                                                    <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i> Keluar
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}" class="btn btn-outline-light tap-target px-3">Masuk</a>
                                <a href="{{ route('register') }}" class="btn btn-crimson tap-target px-3 fw-semibold">Daftar</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow-1 py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2" aria-hidden="true"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2" aria-hidden="true"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-octagon-fill me-2" aria-hidden="true"></i>Terdapat kesalahan input:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup notifikasi"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-motopart pt-5 pb-3 mt-auto">
        <div class="container">
            <div class="row g-4 pb-4 border-bottom border-secondary border-opacity-25">
                <!-- Col 1: Brand -->
                <div class="col-lg-3 col-md-6">
                    <h2 class="h5 fw-bold text-white d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-wrench-adjustable-circle-fill text-crimson" aria-hidden="true"></i>
                        <span>MotoPart <span class="text-crimson">Store</span></span>
                    </h2>
                    <p class="small mb-0">
                        Pusat suku cadang motor 100% original bergaransi resmi dengan layanan booking pasang langsung di bengkel rekanan tanpa antre.
                    </p>
                </div>

                <!-- Col 2: Kategori Populer -->
                <div class="col-lg-3 col-md-6">
                    <h2 class="h6 fw-bold text-white mb-3">Kategori Populer</h2>
                    @php
                        $footerCategories = cache()->remember('footer_categories', 3600, fn() => \App\Models\Category::take(5)->get());
                    @endphp
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        @foreach($footerCategories as $fCat)
                            <li>
                                <a href="{{ route('katalog.index', ['category_id' => $fCat->id]) }}">
                                    <i class="bi bi-chevron-right text-crimson me-1" aria-hidden="true"></i> {{ $fCat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Col 3: Layanan & Jam Operasional -->
                <div class="col-lg-3 col-md-6">
                    <h2 class="h6 fw-bold text-white mb-3">Layanan &amp; Jam Bengkel</h2>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><i class="bi bi-clock text-crimson me-2" aria-hidden="true"></i> Senin - Sabtu: 08.00 - 17.00 WIB</li>
                        <li><i class="bi bi-tools text-crimson me-2" aria-hidden="true"></i> Biaya Jasa Pasang: <strong class="text-white">Rp 25.000/item</strong></li>
                        <li><i class="bi bi-shield-check text-crimson me-2" aria-hidden="true"></i> Garansi Pemasangan 7 Hari</li>
                    </ul>
                </div>

                <!-- Col 4: Kontak & Lokasi -->
                <div class="col-lg-3 col-md-6">
                    <h2 class="h6 fw-bold text-white mb-3">Kontak &amp; Lokasi</h2>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><i class="bi bi-geo-alt-fill text-crimson me-2" aria-hidden="true"></i> Jl. Otomotif Raya No. 88, Jakarta</li>
                        <li><i class="bi bi-whatsapp text-crimson me-2" aria-hidden="true"></i> +62 812-3456-7890 (CS)</li>
                        <li><i class="bi bi-envelope-fill text-crimson me-2" aria-hidden="true"></i> support@motopartstore.id</li>
                    </ul>
                </div>
            </div>

            <div class="text-center pt-3 small">
                &copy; 2026 MotoPart Store
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
