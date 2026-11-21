@extends('layouts.app')

@section('title', 'MotoPart Store - Suku Cadang Motor Original & Booking Bengkel')

@push('styles')
<style>
    .hero-card-subtext {
        color: #CBD5E1;
    }
    .booking-flagship-card {
        background-color: #1E293B;
        color: #F8FAFC;
    }
    .booking-slot-chip {
        background-color: #0F172A;
        border: 1px solid #475569;
        color: #E2E8F0;
    }
    .section-action-link {
        min-height: 44px;
        padding-inline: 0.75rem;
        display: inline-flex;
        align-items: center;
        color: #B91C1C;
        font-weight: 600;
        text-decoration: none;
        border-radius: 0.5rem;
    }
    .section-action-link:hover {
        color: #991B1B;
        background-color: #FEF2F2;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .section-action-link:focus-visible,
    .category-tile:focus-visible,
    .product-thumb-link:focus-visible,
    .product-title-link:focus-visible {
        outline: 3px solid #B91C1C;
        outline-offset: 2px;
    }
    .category-tile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.875rem;
        min-height: 84px;
        padding: 1rem 1.25rem;
        background-color: #FFFFFF;
        border: 1px solid #CBD5E1;
        border-radius: 1rem;
        text-decoration: none;
        color: #1E293B;
    }
    .category-tile:hover {
        border-color: #DC2626;
        background-color: #FFFBFB;
    }
    .category-tile:active {
        background-color: #FEF2F2;
    }
    .category-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        background-color: #FEF2F2;
        color: #DC2626;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .product-thumb-wrap {
        height: 200px;
        aspect-ratio: 4 / 3;
    }
    .product-title-link {
        color: #0F172A;
        text-decoration: none;
    }
    .product-title-link:hover {
        color: #B91C1C;
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .card-meta-muted {
        color: #475569;
    }
    @media (prefers-reduced-motion: no-preference) {
        .category-tile {
            transition: border-color 0.15s ease, background-color 0.15s ease, transform 0.15s ease;
        }
        .category-tile:hover {
            transform: translateY(-2px);
        }
        .category-tile:active {
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
@php
    $categories = \App\Models\Category::withCount('spareparts')->get();
    $featuredParts = \App\Models\Sparepart::with(['category', 'motorcycles'])
        ->withAvg('reviews', 'rating')
        ->withCount('reviews')
        ->latest()
        ->take(6)
        ->get();
    $categoryIcons = [
        'oli-pelumas' => 'bi-droplet-half',
        'sistem-pengereman' => 'bi-disc',
        'komponen-cvt' => 'bi-gear-wide-connected',
        'kelistrikan-aki' => 'bi-battery-charging',
    ];
@endphp

<!-- Hero Section -->
<div class="rounded-4 p-4 p-md-5 mb-5 text-white shadow" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.90) 0%, rgba(30, 41, 59, 0.82) 60%, rgba(127, 29, 29, 0.85) 100%), url('{{ asset('images/hero-motorcycle.jpg') }}') center/cover no-repeat;">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <h1 class="display-5 fw-bold mb-3">
                Sparepart Motor <span class="text-crimson">Pasti Cocok</span>, Bisa Langsung Pasang di Bengkel!
            </h1>
            <p class="lead text-light opacity-75 mb-4">
                Beli suku cadang original sesuai tipe motor Anda atau sekalian <strong>booking jadwal pasang di bengkel</strong> hanya dengan biaya jasa Rp 25.000/item.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('katalog.index') }}" class="btn btn-crimson btn-lg px-4 fw-semibold">
                    <i class="bi bi-cart-plus me-2"></i>Belanja Sekarang
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-person-plus me-2"></i>Daftar Akun
                    </a>
                @endguest
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card bg-dark bg-opacity-75 border-secondary text-white rounded-4 shadow-sm p-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">
                        <i class="bi bi-bicycle text-crimson me-2"></i>Cek Suku Cadang Motor Anda
                    </h5>
                    <p class="small text-secondary mb-3">Pilih motor di garasi untuk menyaring sparepart yang 100% cocok.</p>
                    <form action="{{ route('katalog.index') }}" method="GET">
                        <div class="mb-3">
                            <select name="motorcycle_id" class="form-select form-select-lg bg-dark text-white border-secondary" required>
                                <option value="">-- Pilih Tipe Motor --</option>
                                @foreach(\App\Models\Motorcycle::all() as $m)
                                    <option value="{{ $m->id }}" {{ session('selected_motorcycle_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->model_name }} ({{ $m->production_year }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-crimson w-100 fw-semibold py-2">
                            <i class="bi bi-search me-1"></i> Cari Sparepart Cocok
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Layanan Unggulan: Asymmetric Primary Workshop Highlight + Supporting Guarantees -->
<section class="mb-4 mb-lg-5" aria-labelledby="layanan-heading">
    <h2 id="layanan-heading" class="visually-hidden">Keunggulan Layanan MotoPart Store</h2>
    <div class="row g-3 align-items-stretch">
        <div class="col-lg-7">
            <div class="card booking-flagship-card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="rounded-3 bg-crimson text-white p-2 px-3 fs-5">
                                <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                            </span>
                            <h3 class="h5 fw-bold mb-0 text-white">Booking Pasang di Bengkel Tanpa Antre</h3>
                        </div>
                        <p class="small hero-card-subtext mb-3">
                            Pilih tanggal kedatangan dan sesi jam mekanik langsung saat checkout. Kuota tiap sesi dibatasi maksimal {{ \App\Models\Order::MAX_SLOT_QUOTA }} motor agar pengerjaan tepat waktu dengan biaya jasa tetap <strong>Rp 25.000/unit</strong>.
                        </p>
                    </div>
                    <div>
                        <span class="small fw-semibold text-white d-block mb-2">Sesi Jam Pemasangan Tersedia:</span>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(\App\Models\Order::BOOKING_SLOTS as $slot)
                                <span class="badge rounded-3 booking-slot-chip py-2 px-3 fw-medium">
                                    <i class="bi bi-clock text-crimson me-1" aria-hidden="true"></i>{{ $slot }} WIB
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4 d-flex flex-column justify-content-center gap-3">
                    <div class="d-flex align-items-start gap-3 pb-3 border-bottom">
                        <div class="rounded-3 bg-danger bg-opacity-10 text-crimson p-2 px-3 fs-5 flex-shrink-0">
                            <i class="bi bi-shield-check" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold mb-1">Jaminan 100% Original</h3>
                            <p class="small card-meta-muted mb-0">Produk resmi AHM, Yamalube, Nissin, TDR, NGK, dan GS Astra dengan kode SKU terverifikasi.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 bg-danger bg-opacity-10 text-crimson p-2 px-3 fs-5 flex-shrink-0">
                            <i class="bi bi-sliders2" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold mb-1">Filter Kompatibilitas Garasi</h3>
                            <p class="small card-meta-muted mb-0">Simpan Motor Utama di profil agar ukuran roller, v-belt, dan kampas rem tersaring otomatis sesuai motor Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Suku Cadang -->
<section class="mb-4 mb-lg-5" aria-labelledby="kategori-heading">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h2 id="kategori-heading" class="h4 fw-bold mb-0">
            <i class="bi bi-tags-fill text-crimson me-2" aria-hidden="true"></i>Kategori Suku Cadang
        </h2>
        <a href="{{ route('katalog.index') }}" class="section-action-link small">
            Lihat Semua <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
        </a>
    </div>
    <div class="row g-3">
        @forelse($categories as $cat)
            @php $icon = $categoryIcons[$cat->slug] ?? 'bi-tools'; @endphp
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="{{ route('katalog.index', ['category_id' => $cat->id]) }}" class="category-tile shadow-sm h-100">
                    <div class="d-flex align-items-center gap-3 min-w-0">
                        <span class="category-icon-box fs-4">
                            <i class="bi {{ $icon }}" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h3 class="h6 fw-bold mb-1">{{ $cat->name }}</h3>
                            <span class="small card-meta-muted">{{ $cat->spareparts_count }} Produk</span>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right card-meta-muted flex-shrink-0" aria-hidden="true"></i>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center mb-0">
                    Belum ada kategori suku cadang yang terdaftar di katalog.
                </div>
            </div>
        @endforelse
    </div>
</section>

<!-- Produk Unggulan -->
<section class="mb-4" aria-labelledby="populer-heading">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h2 id="populer-heading" class="h4 fw-bold mb-0">
            <i class="bi bi-fire text-crimson me-2" aria-hidden="true"></i>Suku Cadang Terpopuler
        </h2>
        <a href="{{ route('katalog.index') }}" class="btn btn-outline-dark tap-target rounded-3 px-3 fw-semibold small">
            Buka Katalog Lengkap
        </a>
    </div>
    <div class="row g-4">
        @forelse($featuredParts as $part)
            <div class="col-12 col-md-6 col-lg-4">
                <article class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column overflow-hidden">
                    <a href="{{ route('catalog.show', $part) }}" class="product-thumb-link position-relative bg-light d-flex align-items-center justify-content-center border-bottom product-thumb-wrap text-decoration-none">
                        @if($part->image)
                            <img src="{{ str_starts_with($part->image, 'http') ? $part->image : asset('storage/' . $part->image) }}"
                                 alt="{{ $part->name }}"
                                 width="800"
                                 height="600"
                                 loading="lazy"
                                 class="w-100 h-100"
                                 style="object-fit: cover;">
                        @else
                            <div class="text-center card-meta-muted">
                                <i class="bi bi-gear-wide-connected display-4 d-block" aria-hidden="true"></i>
                                <small class="fw-semibold">{{ $part->brand }}</small>
                            </div>
                        @endif

                        @if($part->stock < 5)
                            <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark shadow-sm">
                                <i class="bi bi-exclamation-circle-fill me-1" aria-hidden="true"></i>Sisa {{ $part->stock }}
                            </span>
                        @endif
                    </a>

                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                            <span class="badge bg-dark">{{ $part->category->name ?? 'Umum' }}</span>
                            <small class="card-meta-muted font-monospace">{{ $part->part_code }}</small>
                        </div>

                        <h3 class="h5 fw-bold mb-1">
                            <a href="{{ route('catalog.show', $part) }}" class="product-title-link">{{ $part->name }}</a>
                        </h3>

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <span class="small card-meta-muted">Merk: <strong>{{ $part->brand }}</strong></span>
                            @if($part->reviews_count > 0)
                                <span class="small card-meta-muted">&middot;</span>
                                <span class="small fw-semibold text-dark">
                                    <i class="bi bi-star-fill text-warning me-1" aria-hidden="true"></i>{{ number_format((float) $part->reviews_avg_rating, 1) }}
                                    <span class="card-meta-muted fw-normal">({{ $part->reviews_count }})</span>
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="card-meta-muted d-block mb-1">Cocok untuk:</small>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($part->motorcycles as $motor)
                                    <span class="badge bg-light text-dark border fw-normal">{{ $motor->model_name }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center gap-2">
                            <div>
                                <small class="card-meta-muted d-block">Harga</small>
                                <span class="fs-5 fw-bold text-crimson">Rp {{ number_format($part->price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('catalog.show', $part) }}" class="btn btn-crimson tap-target px-3 rounded-3 fw-semibold">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center mb-0">
                    Belum ada produk suku cadang tersedia saat ini. Silakan kembali lagi nanti.
                </div>
            </div>
        @endforelse
    </div>
</section>
@endsection
