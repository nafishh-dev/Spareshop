@extends('layouts.app')

@section('title', 'MotoPart Store - Suku Cadang Motor Original & Booking Bengkel')

@section('content')
@php
    $categories = \App\Models\Category::withCount('spareparts')->get();
    $featuredParts = \App\Models\Sparepart::with(['category', 'motorcycles'])->latest()->take(6)->get();
@endphp

<!-- Hero Section -->
<div class="rounded-4 p-4 p-md-5 mb-5 text-white shadow" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.90) 0%, rgba(30, 41, 59, 0.82) 60%, rgba(127, 29, 29, 0.85) 100%), url('https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;">
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

<!-- Keunggulan Layanan -->
<div class="row g-3 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body d-flex align-items-start gap-3 p-4">
                <div class="rounded-3 bg-danger bg-opacity-10 text-crimson p-3 fs-4">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Jaminan 100% Original</h6>
                    <p class="small text-muted mb-0">Produk resmi AHM, Yamalube, Nissin, TDR, dan GS Astra dengan kode part terverifikasi.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body d-flex align-items-start gap-3 p-4">
                <div class="rounded-3 bg-danger bg-opacity-10 text-crimson p-3 fs-4">
                    <i class="bi bi-calendar2-check"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Booking Pasang Bengkel</h6>
                    <p class="small text-muted mb-0">Pilih tanggal kedatangan saat checkout, mekanik kami langsung siap tanpa antre panjang.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 rounded-4">
            <div class="card-body d-flex align-items-start gap-3 p-4">
                <div class="rounded-3 bg-danger bg-opacity-10 text-crimson p-3 fs-4">
                    <i class="bi bi-cpu"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Filter Kompatibilitas Akurat</h6>
                    <p class="small text-muted mb-0">Tak perlu takut salah beli ukuran roller, v-belt, atau kampas rem untuk motor Anda.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kategori Suku Cadang -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-tags-fill text-crimson me-2"></i>Kategori Suku Cadang</h4>
    <a href="{{ route('katalog.index') }}" class="text-crimson text-decoration-none fw-semibold small">
        Lihat Semua <i class="bi bi-arrow-right"></i>
    </a>
</div>
<div class="row g-3 mb-5">
    @forelse($categories as $cat)
        <div class="col-6 col-md-3">
            <a href="{{ route('katalog.index', ['category_id' => $cat->id]) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-3">
                    <div class="card-body">
                        <i class="bi bi-gear-wide-connected fs-2 text-crimson mb-2 d-block"></i>
                        <h6 class="fw-bold text-dark mb-1">{{ $cat->name }}</h6>
                        <span class="badge bg-light text-secondary border">{{ $cat->spareparts_count }} Produk</span>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center mb-0">Belum ada kategori tersedia.</div>
        </div>
    @endforelse
</div>

<!-- Produk Unggulan -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-fire text-crimson me-2"></i>Suku Cadang Terpopuler</h4>
    <a href="{{ route('katalog.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
        Buka Katalog Lengkap
    </a>
</div>
<div class="row g-4 mb-4">
    @forelse($featuredParts as $part)
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column overflow-hidden">
                <div class="position-relative bg-light d-flex align-items-center justify-content-center border-bottom" style="height: 190px;">
                    @if($part->image)
                        <img src="{{ str_starts_with($part->image, 'http') ? $part->image : asset('storage/' . $part->image) }}"
                             alt="{{ $part->name }}"
                             class="w-100 h-100"
                             style="object-fit: cover;">
                    @else
                        <div class="text-center text-secondary opacity-50">
                            <i class="bi bi-gear-wide-connected display-3 d-block"></i>
                            <small class="fw-semibold">{{ $part->brand }}</small>
                        </div>
                    @endif

                    @if($part->stock < 5)
                        <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark shadow-sm">
                            <i class="bi bi-exclamation-circle-fill me-1"></i>Sisa {{ $part->stock }}
                        </span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-dark">{{ $part->category->name ?? 'Umum' }}</span>
                        <small class="text-muted font-monospace">{{ $part->part_code }}</small>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $part->name }}</h5>
                    <p class="small text-muted mb-2">Merk: <strong>{{ $part->brand }}</strong></p>

                    <div class="mb-3">
                        <small class="text-secondary d-block mb-1">Cocok untuk:</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($part->motorcycles as $motor)
                                <span class="badge bg-light text-dark border fw-normal">{{ $motor->model_name }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Harga</small>
                            <span class="fs-5 fw-bold text-crimson">Rp {{ number_format($part->price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('katalog.index', ['search' => $part->name]) }}" class="btn btn-crimson btn-sm px-3 rounded-3">
                            <i class="bi bi-cart-plus me-1"></i> Beli
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center">Belum ada produk suku cadang.</div>
        </div>
    @endforelse
</div>
@endsection
