@extends('layouts.admin')

@section('title', 'Data Sparepart')

@section('content')
@php
    $isFiltered = request()->filled('search') || request()->filled('category_id');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h5 fw-bold mb-0">Data Sparepart</h1>
        <small class="text-muted">Total {{ $spareparts->total() }} produk terdaftar</small>
    </div>
    <a href="{{ route('spareparts.create') }}" class="btn btn-crimson px-4 d-inline-flex align-items-center justify-content-center">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah Sparepart
    </a>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('spareparts.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-sm-5">
                <label for="filter-search" class="visually-hidden">Cari nama atau kode part</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted" aria-hidden="true"></i>
                    </span>
                    <input type="text" id="filter-search" name="search" class="form-control border-start-0" placeholder="Cari nama atau kode part..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <label for="filter-category" class="visually-hidden">Filter berdasarkan kategori</label>
                <select id="filter-category" name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) request('category_id') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-auto d-flex gap-2">
                <button type="submit" class="btn btn-dark px-4">Filter</button>
                @if($isFiltered)
                    <a href="{{ route('spareparts.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">#</th>
                        <th scope="col">Produk</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Stok</th>
                        <th scope="col" class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spareparts as $sp)
                        <tr>
                            <td class="ps-4 text-muted small">{{ $loop->iteration + ($spareparts->currentPage() - 1) * $spareparts->perPage() }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;overflow:hidden;">
                                        @if($sp->image)
                                            <img src="{{ str_starts_with($sp->image, 'http') ? $sp->image : asset('storage/' . $sp->image) }}"
                                                 alt="{{ $sp->name }}"
                                                 width="48"
                                                 height="48"
                                                 loading="lazy"
                                                 class="w-100 h-100"
                                                 style="object-fit:cover;">
                                        @else
                                            <i class="bi bi-gear text-secondary fs-5" aria-hidden="true"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $sp->name }}</div>
                                        <small class="text-muted font-monospace">{{ $sp->part_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-dark">{{ $sp->category->name ?? 'Tanpa Kategori' }}</span></td>
                            <td class="fw-semibold text-crimson text-nowrap">Rp {{ number_format($sp->price, 0, ',', '.') }}</td>
                            <td class="text-nowrap">
                                @if($sp->stock <= 0)
                                    <span class="badge badge-stock-zero">Habis (0)</span>
                                @elseif($sp->stock < 5)
                                    <span class="badge bg-warning text-dark">Sisa {{ $sp->stock }}</span>
                                @else
                                    <span class="badge badge-stock-ok">{{ $sp->stock }} unit</span>
                                @endif
                            </td>
                            <td class="text-center pe-4 text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('spareparts.edit', $sp) }}"
                                       class="btn btn-sm btn-outline-primary admin-icon-btn"
                                       title="Edit {{ $sp->name }}"
                                       aria-label="Edit {{ $sp->name }}">
                                        <i class="bi bi-pencil" aria-hidden="true"></i>
                                    </a>
                                    <form action="{{ route('spareparts.destroy', $sp) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus sparepart {{ addslashes($sp->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger admin-icon-btn"
                                                title="Hapus {{ $sp->name }}"
                                                aria-label="Hapus {{ $sp->name }}">
                                            <i class="bi bi-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                @if($isFiltered)
                                    <i class="bi bi-search fs-2 d-block mb-2 text-muted" aria-hidden="true"></i>
                                    <p class="fw-semibold mb-1">Tidak ada sparepart yang cocok</p>
                                    <p class="small text-muted mb-3">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
                                    <a href="{{ route('spareparts.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center px-3">
                                        Reset Filter
                                    </a>
                                @else
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-muted" aria-hidden="true"></i>
                                    <p class="fw-semibold mb-1">Belum ada data sparepart</p>
                                    <p class="small text-muted mb-3">Tambahkan produk pertama agar tampil di katalog toko.</p>
                                    <a href="{{ route('spareparts.create') }}" class="btn btn-sm btn-crimson d-inline-flex align-items-center justify-content-center px-3">
                                        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Tambah Sparepart
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($spareparts->hasPages())
        <div class="card-footer bg-transparent border-0 px-4 py-3">
            {{ $spareparts->links() }}
        </div>
    @endif
</div>
@endsection
