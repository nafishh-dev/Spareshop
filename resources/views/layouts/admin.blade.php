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

        .sidebar-admin {
            width: 260px;
            background-color: #1E293B !important;
            --bs-offcanvas-bg: #1E293B;
            --bs-offcanvas-width: 260px;
            transition: width 0.2s ease, margin-left 0.2s ease;
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

            /* Memberi jarak kiri pada konten utama selebar sidebar (260px) agar tidak tertimpa */
            .main-wrapper {
                margin-left: 260px;
            }

            body.sidebar-collapsed .sidebar-admin {
                width: 76px;
            }

            /* Menyesuaikan jarak kiri konten utama saat sidebar di-minimize (76px) */
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
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            white-space: nowrap;
            transition: all 0.15s ease;
        }

        .sidebar-admin .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .sidebar-admin .nav-link.active {
            background-color: #DC2626;
            color: #fff;
            font-weight: 600;
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
    </style>
    @stack('styles')
</head>

<body>
    <!-- Sidebar maybe -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100 main-wrapper" style="min-width: 0; transition: margin-left 0.2s ease;"> 
        <aside class="offcanvas-lg offcanvas-start sidebar-admin d-flex flex-column flex-shrink-0 p-3 text-white" tabindex="-1" id="adminSidebar">
            <div class="d-flex align-items-center justify-content-between px-2 py-2 mb-3 border-bottom border-secondary border-opacity-50">
                <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex align-items-center gap-2 text-white text-decoration-none overflow-hidden">
                    <i class="bi bi-wrench-adjustable-circle-fill text-crimson fs-3 flex-shrink-0"></i>
                    <span class="fs-5 fw-bold sidebar-text text-nowrap">MotoPart <span class="text-crimson">Admin</span></span>
                </a>
                <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar"></button>
            </div>

            <ul class="nav nav-pills flex-column gap-1 mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                        <i class="bi bi-speedometer2 fs-5"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('spareparts.index') }}" class="nav-link {{ request()->routeIs('spareparts.*') ? 'active' : '' }}" title="Data Sparepart">
                        <i class="bi bi-gear-wide-connected fs-5"></i>
                        <span class="sidebar-text">Data Sparepart</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" title="Kelola Pesanan">
                        <i class="bi bi-bag-check fs-5"></i>
                        <span class="sidebar-text">Kelola Pesanan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" title="Master Kategori">
                        <i class="bi bi-tags fs-5"></i>
                        <span class="sidebar-text">Master Kategori</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('motorcycles.index') }}" class="nav-link {{ request()->routeIs('motorcycles.*') ? 'active' : '' }}" title="Master Motor">
                        <i class="bi bi-bicycle fs-5"></i>
                        <span class="sidebar-text">Master Motor</span>
                    </a>
                </li>
            </ul>

            <hr class="border-secondary">

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('katalog.index') }}" class="nav-link text-info" title="Lihat Katalog Toko">
                    <i class="bi bi-shop fs-5"></i>
                    <span class="sidebar-text">Lihat Katalog Toko</span>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center gap-2 btn-logout" onclick="return confirm('Are you sure want to logout?');" title="Logout">
                        <i class="bi bi-box-arrow-right fs-5"></i>
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
                    <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <!-- Desktop Minimize Sidebar Trigger -->
                    <button class="btn btn-outline-secondary btn-sm d-none d-lg-inline-flex" type="button" id="toggleSidebarBtn" title="Minimize/Expand Sidebar">
                        <i class="bi bi-layout-sidebar-inset fs-6"></i>
                    </button>
                    <h5 class="mb-0 fw-bold text-truncate">@yield('title', 'Dashboard Admin')</h5>
                </div>
                <span class="badge bg-dark px-3 py-2 d-flex align-items-center gap-2">
                    <i class="bi bi-person-badge-fill text-crimson"></i>
                    <span class="d-none d-sm-inline">{{ auth()->user()->name ?? 'Administrator' }}</span>
                </span>
            </header>

            <!-- Content Area -->
            <main class="flex-grow-1 p-3 p-md-4">
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
            </main>

            <footer class="bg-white border-top text-center py-3 small text-muted mt-auto px-2">
                &copy; 2026 MotoPart Store - Panel Administrasi Bengkel &amp; Ritel
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if (localStorage.getItem('sidebar_collapsed') === '1') document.body.classList.add('sidebar-collapsed');
        document.getElementById('toggleSidebarBtn')?.addEventListener('click', () => {
            const collapsed = document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebar_collapsed', collapsed ? '1' : '0');
        });
    </script>
    @stack('scripts')
</body>

</html>