@extends('layouts.admin')

@section('title', 'Master Kategori')

@section('content')
<div class="row g-4">
    <!-- Form Tambah -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle text-crimson me-2"></i>Tambah Kategori</h6>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="cth. Ban & Velg" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-crimson w-100 fw-semibold">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Daftar Kategori</h6>
                <span class="badge bg-dark">{{ $categories->total() }} kategori</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 mt-3">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 60px;">#</th>
                                <th>Kategori</th>
                                <th>Slug</th>
                                <th>Jumlah Part</th>
                                <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                    <td class="fw-semibold">{{ $cat->name }}</td>
                                    <td><code class="small text-muted">{{ $cat->slug }}</code></td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $cat->spareparts_count }} item</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $cat->id }}"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus kategori {{ addslashes($cat->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('categories.update', $cat->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-header border-0 pb-0">
                                                    <h6 class="modal-title fw-bold">Edit Kategori</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label class="form-label small fw-semibold">Nama Kategori</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-sm btn-crimson fw-semibold">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Belum ada data kategori.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($categories->hasPages())
                <div class="card-footer bg-transparent border-0 px-4 py-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
