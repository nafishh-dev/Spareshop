<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') - MotoPart Admin</title>
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
        input[type="radio"],
        input[type="file"] {
            cursor: pointer;
        }

        :disabled {
            cursor: not-allowed;
        }

        .sidebar-admin {
            width: 260px;
            background-color: #1E293B !important;
            --bs-offcanvas-bg: #1E293B;
            --bs-offcanvas-width: 260px;
        }

        @media (min-width: 992px) {
            .sidebar-admin {
                position: fixed !important;
                top: 0;
                left: 0;
                bottom: 0;
                height: 100vh;
                overflow-y: auto;
                z-index: 1030;
            }

            .main-wrapper {
                margin-left: 260px;
            }

            body.sidebar-collapsed .sidebar-admin {
                width: 76px;
            }

            body.sidebar-collapsed .main-wrapper {
                margin-left: 76px;
            }

            body.sidebar-collapsed .sidebar-admin .sidebar-text {
                display: none !important;
            }

            body.sidebar-collapsed .sidebar-admin .nav-link,
            body.sidebar-collapsed .sidebar-admin .brand-link,
            body.sidebar-collapsed .sidebar-admin .btn-logout {
                justify-content: center !important;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }

        .sidebar-admin .nav-link {
            color: #CBD5E1;
            border-radius: 0.5rem;
            padding: 0.65rem 1rem;
            min-height: 44px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            white-space: nowrap;
        }

        .sidebar-admin .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
        }

        .sidebar-admin .nav-link.active {
            background-color: #DC2626;
            color: #FFFFFF;
            font-weight: 600;
        }

        .sidebar-admin a:focus-visible,
        .sidebar-admin button:focus-visible {
            outline: 3px solid #F8FAFC;
            outline-offset: 2px;
        }

        .btn-logout-sidebar {
            color: #FCA5A5;
            border: 1px solid #F87171;
            background-color: transparent;
            min-height: 44px;
        }

        .btn-logout-sidebar:hover {
            background-color: #DC2626;
            border-color: #DC2626;
            color: #FFFFFF;
        }

        .btn-logout-sidebar:active {
            background-color: #991B1B !important;
            border-color: #991B1B !important;
            color: #FFFFFF !important;
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

        .badge-stock-ok {
            background-color: #DCFCE7;
            color: #166534;
            border: 1px solid #86EFAC;
        }

        .badge-stock-zero {
            background-color: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        .upload-placeholder-text {
            color: #475569;
        }

        .pagination {
            --bs-pagination-active-bg: #DC2626;
            --bs-pagination-active-border-color: #DC2626;
            --bs-pagination-color: #DC2626;
            --bs-pagination-hover-color: #B91C1C;
            --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(220, 38, 38, 0.25);
        }

        @media (max-width: 991.98px) {
            .main-wrapper .btn,
            .main-wrapper .form-control,
            .main-wrapper .form-select,
            .admin-tap-mobile {
                min-height: 44px;
            }

            .admin-icon-btn {
                min-width: 44px;
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            .sidebar-admin {
                transition: width 0.2s ease, margin-left 0.2s ease;
            }

            .main-wrapper {
                transition: margin-left 0.2s ease;
            }

            .sidebar-admin .nav-link {
                transition: background-color 0.15s ease, color 0.15s ease;
            }

            .btn-crimson:active {
                transform: translateY(1px);
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="flex-grow-1 d-flex flex-column min-vh-100 main-wrapper" style="min-width: 0;">
        <aside class="offcanvas-lg offcanvas-start sidebar-admin d-flex flex-column flex-shrink-0 p-3 text-white" tabindex="-1" id="adminSidebar" aria-label="Sidebar panel admin">
            <div class="d-flex align-items-center justify-content-between px-2 py-2 mb-3 border-bottom border-secondary border-opacity-50">
                <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex align-items-center gap-2 text-white text-decoration-none overflow-hidden">
                    <i class="bi bi-wrench-adjustable-circle-fill text-crimson fs-3 flex-shrink-0" aria-hidden="true"></i>
                    <span class="fs-5 fw-bold sidebar-text text-nowrap">MotoPart <span class="text-crimson">Admin</span></span>
                </a>
                <button type="button" class="btn-close btn-close-white d-lg-none p-2" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Tutup menu navigasi"></button>
            </div>

            <nav aria-label="Menu navigasi admin" class="mb-auto">
                <ul class="nav nav-pills flex-column gap-1">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard" aria-label="Dashboard">
                            <i class="bi bi-speedometer2 fs-5" aria-hidden="true"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('spareparts.index') }}" class="nav-link {{ request()->routeIs('spareparts.*') ? 'active' : '' }}" title="Data Sparepart" aria-label="Data Sparepart">
                            <i class="bi bi-gear-wide-connected fs-5" aria-hidden="true"></i>
                            <span class="sidebar-text">Data Sparepart</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" title="Kelola Pesanan" aria-label="Kelola Pesanan">
                            <i class="bi bi-bag-check fs-5" aria-hidden="true"></i>
                            <span class="sidebar-text">Kelola Pesanan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" title="Master Kategori" aria-label="Master Kategori">
                            <i class="bi bi-tags fs-5" aria-hidden="true"></i>
                            <span class="sidebar-text">Master Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('motorcycles.index') }}" class="nav-link {{ request()->routeIs('motorcycles.*') ? 'active' : '' }}" title="Master Motor" aria-label="Master Motor">
                            <i class="bi bi-bicycle fs-5" aria-hidden="true"></i>
                            <span class="sidebar-text">Master Motor</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <hr class="border-secondary">

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('katalog.index') }}" class="nav-link text-info" title="Lihat Katalog Toko" aria-label="Lihat Katalog Toko">
                    <i class="bi bi-shop fs-5" aria-hidden="true"></i>
                    <span class="sidebar-text">Lihat Katalog Toko</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-logout-sidebar w-100 d-flex align-items-center gap-2 btn-logout" onclick="return confirm('Yakin ingin keluar dari sesi admin?');" title="Keluar dari sesi admin" aria-label="Keluar dari sesi admin">
                        <i class="bi bi-box-arrow-right fs-5" aria-hidden="true"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-grow-1 d-flex flex-column min-vh-100" style="min-width: 0;">
            <!-- Top Header Bar -->
            <header class="bg-white border-bottom px-3 px-md-4 py-3 d-flex justify-content-between align-items-center shadow-sm sticky-top">
                <div class="d-flex align-items-center gap-2">
                    <!-- Mobile Offcanvas Trigger -->
                    <button class="btn btn-outline-secondary btn-sm admin-icon-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Buka menu navigasi">
                        <i class="bi bi-list fs-5" aria-hidden="true"></i>
                    </button>
                    <!-- Desktop Minimize Sidebar Trigger -->
                    <button class="btn btn-outline-secondary btn-sm d-none d-lg-inline-flex align-items-center justify-content-center" type="button" id="toggleSidebarBtn" title="Perkecil atau perluas sidebar" aria-label="Perkecil atau perluas sidebar" aria-expanded="true">
                        <i class="bi bi-layout-sidebar-inset fs-6" aria-hidden="true"></i>
                    </button>
                    <span class="fs-5 mb-0 fw-bold text-truncate">@yield('title', 'Dashboard Admin')</span>
                </div>
                <span class="badge bg-dark px-3 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge-fill text-crimson" aria-hidden="true"></i>
                    <span class="d-none d-sm-inline">{{ auth()->user()->name ?? 'Administrator' }}</span>
                </span>
            </header>

            <!-- Content Area -->
            <main class="flex-grow-1 p-3 p-md-4">
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
            </main>

            <footer class="bg-white border-top text-center py-3 small text-muted mt-auto px-2">
                &copy; 2026 MotoPart Store
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const toggleBtn = document.getElementById('toggleSidebarBtn');
            const isCollapsed = localStorage.getItem('sidebar_collapsed') === '1';
            if (isCollapsed) {
                document.body.classList.add('sidebar-collapsed');
                toggleBtn?.setAttribute('aria-expanded', 'false');
            }
            toggleBtn?.addEventListener('click', () => {
                const collapsed = document.body.classList.toggle('sidebar-collapsed');
                toggleBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                localStorage.setItem('sidebar_collapsed', collapsed ? '1' : '0');
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
