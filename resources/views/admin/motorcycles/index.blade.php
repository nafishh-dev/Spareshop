@extends('layouts.admin')

@section('title', 'Master Motor')

@section('content')
<div class="row g-4">
    <!-- Form Tambah -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-plus-circle text-crimson me-2"></i>Tambah Tipe Motor</h6>
                <form action="{{ route('motorcycles.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pabrikan / Brand <span class="text-danger">*</span></label>
                        <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                               placeholder="cth. Honda, Yamaha, Suzuki" value="{{ old('brand') }}" list="brandList" required>
                        <datalist id="brandList">
                            <option value="Honda">
                            <option value="Yamaha">
                            <option value="Suzuki">
                            <option value="Kawasaki">
                            <option value="Vespa">
                        </datalist>
                        @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Model <span class="text-danger">*</span></label>
                        <input type="text" name="model_name" class="form-control @error('model_name') is-invalid @enderror"
                               placeholder="cth. Honda Vario 160" value="{{ old('model_name') }}" required>
                        @error('model_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tahun Produksi <span class="text-danger">*</span></label>
                        <input type="text" name="production_year" class="form-control @error('production_year') is-invalid @enderror"
                               placeholder="cth. 2022-2024" value="{{ old('production_year') }}" required>
                        @error('production_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-crimson w-100 fw-semibold">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Motot -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Daftar Tipe Motor</h6>
                <span class="badge bg-dark">{{ $motorcycles->total() }} tipe</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 mt-3">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 60px;">#</th>
                                <th>Brand</th>
                                <th>Model Motor</th>
                                <th>Tahun</th>
                                <th>Sparepart Cocok</th>
                                <th class="text-center pe-4" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($motorcycles as $m)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $loop->iteration + ($motorcycles->currentPage() - 1) * $motorcycles->perPage() }}</td>
                                    <td><span class="badge bg-dark">{{ $m->brand }}</span></td>
                                    <td class="fw-semibold">{{ $m->model_name }}</td>
                                    <td class="small text-muted">{{ $m->production_year }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $m->spareparts_count }} item</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-sm btn-outline-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $m->id }}"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('motorcycles.destroy', $m) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus tipe motor {{ addslashes($m->model_name) }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ route('motorcycles.update', $m) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-header border-0 pb-0">
                                                    <h6 class="modal-title fw-bold">Edit Tipe Motor</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Pabrikan / Brand</label>
                                                        <input type="text" name="brand" class="form-control" value="{{ $m->brand }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Nama Model</label>
                                                        <input type="text" name="model_name" class="form-control" value="{{ $m->model_name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-semibold">Tahun Produksi</label>
                                                        <input type="text" name="production_year" class="form-control" value="{{ $m->production_year }}" required>
                                                    </div>
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
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Belum ada data tipe motor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($motorcycles->hasPages())
                <div class="card-footer bg-transparent border-0 px-4 py-3">
                    {{ $motorcycles->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
