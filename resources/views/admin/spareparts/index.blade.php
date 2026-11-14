@extends('layouts.admin')

@section('title', 'Data Sparepart')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Data Sparepart</h5>
        <small class="text-muted">Total {{ $spareparts->total() }} produk terdaftar</small>
    </div>
    <a href="{{ route('spareparts.create') }}" class="btn btn-crimson px-4">
        <i class="bi bi-plus-lg me-1"></i> Tambah Sparepart
    </a>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('spareparts.index') }}" class="row g-2 align-items-center">
            <div class="col-sm-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau kode part..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-sm-4">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-auto d-flex gap-2">
                <button type="submit" class="btn btn-dark px-4">Filter</button>
                @if(request()->hasAny(['search','category_id']))
                    <a href="{{ route('spareparts.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th class="ps-4">#</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th class="text-center pe-4">Aksi</th>
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
                                            <img src="{{ asset('storage/' . $sp->image) }}" class="w-100 h-100" style="object-fit:cover;">
                                        @else
                                            <i class="bi bi-gear text-secondary fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $sp->name }}</div>
                                        <small class="text-muted font-monospace">{{ $sp->part_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-dark">{{ $sp->category->name ?? '-' }}</span></td>
                            <td class="fw-semibold text-crimson">Rp {{ number_format($sp->price, 0, ',', '.') }}</td>
                            <td>
                                @if($sp->stock < 5)
                                    <span class="badge bg-warning text-dark">{{ $sp->stock }}</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">{{ $sp->stock }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <a href="{{ route('spareparts.edit', $sp) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('spareparts.destroy', $sp) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus sparepart {{ addslashes($sp->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Belum ada data sparepart.
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
