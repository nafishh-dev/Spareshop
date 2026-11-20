@extends('layouts.app')

@section('title', $sparepart->name)

@section('content')
    <div class="mb-3">
        <a href="{{ route('katalog.index') }}" class="text-decoration-none text-crimson">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
        </a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-5">
            @if($sparepart->image)
                <img src="{{ asset('storage/' . $sparepart->image) }}" alt="{{ $sparepart->name }}" class="img-fluid rounded-3 shadow-sm w-100" style="max-height: 480px; object-fit: cover;">
            @else
                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-secondary" style="height: 360px;">
                    <i class="bi bi-gear-wide-connected display-1" aria-hidden="true"></i>
                </div>
            @endif
        </div>
        <div class="col-lg-7">
            <p class="small text-secondary mb-1">{{ $sparepart->brand }} &middot; SKU {{ $sparepart->part_code }}</p>
            <h1 class="h2 fw-bold">{{ $sparepart->name }}</h1>
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge text-bg-warning">
                    <i class="bi bi-star-fill me-1"></i>{{ number_format((float) $sparepart->reviews_avg_rating, 1) }}
                </span>
                <span class="text-secondary">{{ $sparepart->reviews_count }} ulasan</span>
            </div>
            <p class="h3 fw-bold text-crimson mb-3">Rp {{ number_format($sparepart->price, 0, ',', '.') }}</p>

            <div class="card border-0 bg-light mb-3">
                <div class="card-body">
                    <h2 class="h6 fw-bold">Deskripsi Produk</h2>
                    <p class="mb-0">
                        {{ $sparepart->name }} dari {{ $sparepart->brand }} merupakan suku cadang kategori
                        {{ $sparepart->category?->name ?? 'lainnya' }} dengan kode SKU {{ $sparepart->part_code }}.
                        Tersedia {{ $sparepart->stock }} unit untuk memenuhi kebutuhan perawatan dan penggantian komponen motor Anda.
                    </p>
                </div>
            </div>

            <dl class="row mb-3">
                <dt class="col-sm-4">Kategori</dt>
                <dd class="col-sm-8">{{ $sparepart->category?->name ?? '-' }}</dd>
                <dt class="col-sm-4">Kode SKU</dt>
                <dd class="col-sm-8">{{ $sparepart->part_code }}</dd>
                <dt class="col-sm-4">Stok</dt>
                <dd class="col-sm-8">{{ $sparepart->stock }} unit</dd>
            </dl>

            <div class="mb-4">
                <h2 class="h6 fw-bold">Motor yang kompatibel</h2>
                @forelse($sparepart->motorcycles as $motorcycle)
                    <span class="badge rounded-pill bg-light text-dark border me-1 mb-1">
                        <i class="bi bi-bicycle text-crimson me-1"></i>{{ $motorcycle->brand }} {{ $motorcycle->model_name }} ({{ $motorcycle->production_year }})
                    </span>
                @empty
                    <span class="text-secondary small">Belum ada data kompatibilitas.</span>
                @endforelse
            </div>

            <form action="{{ route('cart.store') }}" method="POST" class="row g-2 align-items-end">
                @csrf
                <input type="hidden" name="sparepart_id" value="{{ $sparepart->id }}">
                <div class="col-auto">
                    <label for="qty" class="form-label fw-semibold">Jumlah</label>
                    <input type="number" id="qty" name="qty" class="form-control" min="1" max="{{ $sparepart->stock }}" value="1" @disabled($sparepart->stock < 1)>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-crimson" @disabled($sparepart->stock < 1)>
                        <i class="bi bi-cart-plus me-1"></i>{{ $sparepart->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <section id="reviews">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
            <div>
                <h2 class="h4 fw-bold mb-1">Ulasan Pembeli Terverifikasi</h2>
                <p class="text-secondary mb-0">
                    <i class="bi bi-star-fill text-warning me-1"></i>
                    {{ number_format((float) $sparepart->reviews_avg_rating, 1) }} dari 5
                    &middot; {{ $sparepart->reviews_count }} ulasan
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                @forelse($sparepart->reviews as $review)
                    <article class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between gap-2">
                                <div>
                                    <h3 class="h6 fw-bold mb-1">{{ $review->user->name }}</h3>
                                    <div class="text-warning" aria-label="Rating {{ $review->rating }} dari 5">
                                        @for($star = 1; $star <= 5; $star++)
                                            <i class="bi {{ $star <= $review->rating ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge text-bg-success"><i class="bi bi-patch-check-fill me-1"></i>Pembeli Terverifikasi</span>
                                    <div class="small text-secondary mt-1">{{ $review->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="mb-0 mt-3">{{ $review->comment }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="alert alert-light border">Belum ada ulasan untuk sparepart ini.</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
