@extends('layouts.app')

@section('title', 'Katalog Suku Cadang')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Katalog Suku Cadang</h1>
            <p class="text-secondary mb-0">Temukan suku cadang untuk motor Anda.</p>
        </div>
    </div>

    @if($selectedMotor)
        <div class="alert bg-crimson bg-opacity-10 border-0 d-flex flex-wrap justify-content-between align-items-center gap-2" role="status">
            <div>
                <i class="bi bi-bicycle text-crimson me-2"></i>
                Menampilkan sparepart yang kompatibel dengan
                <strong>{{ $selectedMotor->brand }} {{ $selectedMotor->model_name }} ({{ $selectedMotor->production_year }})</strong>.
            </div>
            <a href="{{ route('katalog.resetMotor') }}" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-x-lg me-1"></i>Reset motor
            </a>
        </div>
    @endif

    <form action="{{ route('katalog.index') }}" method="GET" class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            @if(session('selected_motorcycle_id'))
                <input type="hidden" name="motorcycle_id" value="{{ session('selected_motorcycle_id') }}">
            @endif
            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            <label for="catalog-search" class="form-label fw-semibold">Cari sparepart</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-search text-crimson"></i></span>
                <input type="search" id="catalog-search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nama, kode part, atau merek">
                <button type="submit" class="btn btn-crimson">Cari</button>
            </div>
        </div>
    </form>

    <div class="d-flex flex-wrap gap-2 mb-4" aria-label="Filter kategori">
        <a href="{{ route('katalog.index', ['search' => request('search'), 'motorcycle_id' => session('selected_motorcycle_id')]) }}"
            class="btn btn-sm rounded-pill {{ request()->filled('category_id') ? 'btn-outline-secondary' : 'btn-crimson' }}">
            Semua kategori
        </a>
        @foreach($categories as $category)
            <a href="{{ route('katalog.index', ['category_id' => $category->id, 'search' => request('search'), 'motorcycle_id' => session('selected_motorcycle_id')]) }}"
                class="btn btn-sm rounded-pill {{ request('category_id') == $category->id ? 'btn-crimson' : 'btn-outline-secondary' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <div class="row g-4">
        @forelse($spareparts as $part)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <article class="card h-100 border-0 shadow-sm">
                    @if($part->image)
                        <img src="{{ asset('storage/' . $part->image) }}" class="card-img-top" alt="{{ $part->name }}" style="height: 190px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-secondary" style="height: 190px;">
                            <i class="bi bi-gear-wide-connected fs-1" aria-hidden="true"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <p class="small text-secondary mb-1">{{ $part->brand }} &middot; {{ $part->part_code }}</p>
                        <h2 class="h6 fw-bold">{{ $part->name }}</h2>
                        @if($part->category)
                            <span class="badge rounded-pill bg-light text-dark border align-self-start mb-2">{{ $part->category->name }}</span>
                        @endif
                        <p class="text-crimson fw-bold mb-1">Rp {{ number_format($part->price, 0, ',', '.') }}</p>
                        <p class="small text-secondary mb-2">Stok: {{ $part->stock }}</p>
                        <div class="mb-3">
                            <span class="small fw-semibold d-block mb-1">Kompatibel dengan:</span>
                            @forelse($part->motorcycles as $motorcycle)
                                <span class="badge rounded-pill bg-light text-secondary border mb-1">
                                    {{ $motorcycle->brand }} {{ $motorcycle->model_name }}
                                </span>
                            @empty
                                <span class="small text-secondary">Belum ada data kompatibilitas.</span>
                            @endforelse
                        </div>
                        <form action="{{ route('cart.store') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="sparepart_id" value="{{ $part->id }}">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="btn btn-crimson w-100" @disabled($part->stock < 1)>
                                <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                            </button>
                        </form>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0">Belum ada sparepart yang tersedia.</div>
            </div>
        @endforelse
    </div>
@endsection
