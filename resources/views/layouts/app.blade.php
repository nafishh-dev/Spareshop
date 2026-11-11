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
        .navbar-motopart {
            background-color: #1E293B;
            border-bottom: 3px solid #DC2626;
        }
        .text-crimson {
            color: #DC2626 !important;
        }
        .bg-crimson {
            background-color: #DC2626 !important;
        }
        .btn-crimson {
            background-color: #DC2626;
            border-color: #DC2626;
            color: #fff;
        }
        .btn-crimson:hover {
            background-color: #B91C1C;
            border-color: #B91C1C;
            color: #fff;
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
            color: #fff;
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Sticky Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-motopart sticky-top py-2 shadow-sm">
        <div class="container">
            <!-- Left: Brand Logo -->
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="{{ route('katalog.index') }}">
                <i class="bi bi-wrench-adjustable-circle-fill text-crimson fs-3"></i>
                <span><span class="text-white">MotoPart</span><span class="text-crimson">Store</span></span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMotoPart">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMotoPart">
                <!-- Center: Navigation & "Garasi Saya" Session Widget -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2 ms-lg-2">
                    <li class="nav-item">
                        <a class="nav-link text-nowrap {{ request()->routeIs('katalog.*') ? 'active fw-semibold text-white' : '' }}" href="{{ route('katalog.index') }}">
                            <i class="bi bi-grid-fill me-1 text-crimson"></i> Katalog
                        </a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto">
                        @php $navMotors = \App\Models\Motorcycle::all(); @endphp
                        <form action="{{ route('katalog.index') }}" method="GET" class="d-flex align-items-center gap-1 w-100">
                            <div class="input-group input-group-sm flex-nowrap">
                                <span class="input-group-text bg-dark text-crimson border-secondary">
                                    <i class="bi bi-bicycle"></i>
                                </span>
                                <select name="motorcycle_id" class="form-select bg-dark text-white border-secondary" style="max-width: 200px" onchange="this.form.submit()" aria-label="Pilih Motor Anda">
                                    <option value="">Semua Tipe Motor</option>
                                    @foreach($navMotors as $m)
                                        <option value="{{ $m->id }}" {{ session('selected_motorcycle_id') == $m->id ? 'selected' : '' }}>
                                            {{ $m->model_name }} ({{ $m->production_year }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if(session('selected_motorcycle_id'))
                                <a href="{{ route('katalog.resetMotor') }}" class="btn btn-sm btn-outline-danger" title="Reset Filter Motor">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </form>
                    </li>
                </ul>

                <!-- Right: Auth & Cart Menu -->
                <div class="d-grid d-sm-flex align-items-center gap-2 mt-3 mt-lg-0 flex-wrap flex-lg-nowrap">
                    @auth
                        @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('qty'); @endphp
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm position-relative text-nowrap">
                            <i class="bi bi-cart3 me-1"></i>Keranjang
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-crimson">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-sm text-nowrap">
                            <i class="bi bi-receipt me-1"></i>Pesanan Saya
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm fw-semibold text-dark text-nowrap">
                                <i class="bi bi-speedometer2 me-1"></i>Panel Admin
                            </a>
                        @endif

                        <div class="dropdown">
                            <button class="btn btn-dark btn-sm dropdown-toggle d-flex align-items-center gap-1 border-secondary text-nowrap" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle text-crimson"></i>
                                <span>{{ auth()->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-crimson btn-sm px-3 fw-semibold">Daftar</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow-1 py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <div class="fw-semibold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i>Terdapat kesalahan input:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                    <h5 class="fw-bold text-white d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-wrench-adjustable-circle-fill text-crimson"></i>
                        <span>MotoPart <span class="text-crimson">Store</span></span>
                    </h5>
                    <p class="small mb-0">
                        Pusat suku cadang motor 100% original bergaransi resmi dengan layanan booking pasang langsung di bengkel rekanan tanpa antre.
                    </p>
                </div>

                <!-- Col 2: Kategori Populer -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Kategori Populer</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('katalog.index') }}"><i class="bi bi-chevron-right text-crimson me-1"></i> Oli & Pelumas</a></li>
                        <li><a href="{{ route('katalog.index') }}"><i class="bi bi-chevron-right text-crimson me-1"></i> Sistem Pengereman</a></li>
                        <li><a href="{{ route('katalog.index') }}"><i class="bi bi-chevron-right text-crimson me-1"></i> Komponen CVT</a></li>
                        <li><a href="{{ route('katalog.index') }}"><i class="bi bi-chevron-right text-crimson me-1"></i> Kelistrikan & Aki</a></li>
                    </ul>
                </div>

                <!-- Col 3: Layanan & Jam Operasional -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Layanan & Jam Bengkel</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><i class="bi bi-clock text-crimson me-2"></i> Senin - Sabtu: 08.00 - 17.00 WIB</li>
                        <li><i class="bi bi-tools text-crimson me-2"></i> Biaya Jasa Pasang: <strong class="text-white">Rp 25.000/item</strong></li>
                        <li><i class="bi bi-shield-check text-crimson me-2"></i> Garansi Pemasangan 7 Hari</li>
                    </ul>
                </div>

                <!-- Col 4: Kontak & Lokasi -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Kontak & Lokasi</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><i class="bi bi-geo-alt-fill text-crimson me-2"></i> Jl. Otomotif Raya No. 88, Jakarta</li>
                        <li><i class="bi bi-whatsapp text-crimson me-2"></i> +62 812-3456-7890 (CS)</li>
                        <li><i class="bi bi-envelope-fill text-crimson me-2"></i> support@motopartstore.id</li>
                    </ul>
                </div>
            </div>

            <div class="text-center pt-3 small">
                &copy; 2026 MotoPart Store - Sistem E-Commerce &amp; Ritel
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
