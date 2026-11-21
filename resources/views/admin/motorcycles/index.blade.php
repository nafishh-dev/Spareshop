@extends('layouts.admin')

@section('title', 'Master Motor')

@section('content')
@php
    $editingMotorcycleId = old('editing_motorcycle_id');
    $isCreateContext = $editingMotorcycleId === null;
@endphp

<h1 class="visually-hidden">Master Motor</h1>

<datalist id="brandList">
    <option value="Honda"></option>
    <option value="Yamaha"></option>
    <option value="Suzuki"></option>
    <option value="Kawasaki"></option>
    <option value="Vespa"></option>
</datalist>

<div class="row g-4">
    <!-- Form Tambah -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">
                    <i class="bi bi-plus-circle text-crimson me-2" aria-hidden="true"></i>Tambah Tipe Motor
                </h2>
                <form action="{{ route('motorcycles.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="create-motor-brand" class="form-label small fw-semibold">
                            Pabrikan / Brand <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="create-motor-brand"
                               name="brand"
                               class="form-control {{ $isCreateContext && $errors->has('brand') ? 'is-invalid' : '' }}"
                               placeholder="cth. Honda, Yamaha, Suzuki"
                               value="{{ $isCreateContext ? old('brand') : '' }}"
                               list="brandList"
                               required>
                        @if($isCreateContext && $errors->has('brand'))
                            <div class="invalid-feedback">{{ $errors->first('brand') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="create-motor-model" class="form-label small fw-semibold">
                            Nama Model <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="create-motor-model"
                               name="model_name"
                               class="form-control {{ $isCreateContext && $errors->has('model_name') ? 'is-invalid' : '' }}"
                               placeholder="cth. Honda Vario 160"
                               value="{{ $isCreateContext ? old('model_name') : '' }}"
                               required>
                        @if($isCreateContext && $errors->has('model_name'))
                            <div class="invalid-feedback">{{ $errors->first('model_name') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="create-motor-year" class="form-label small fw-semibold">
                            Tahun Produksi <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="create-motor-year"
                               name="production_year"
                               class="form-control {{ $isCreateContext && $errors->has('production_year') ? 'is-invalid' : '' }}"
                               placeholder="cth. 2022-2024"
                               value="{{ $isCreateContext ? old('production_year') : '' }}"
                               required>
                        @if($isCreateContext && $errors->has('production_year'))
                            <div class="invalid-feedback">{{ $errors->first('production_year') }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-crimson w-100 fw-semibold d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-check-lg me-1" aria-hidden="true"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Motor -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h2 class="h6 fw-bold mb-0">Daftar Tipe Motor</h2>
                <span class="badge bg-dark">{{ $motorcycles->total() }} tipe</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 mt-3">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4" style="width: 60px;">#</th>
                                <th scope="col">Brand</th>
                                <th scope="col">Model Motor</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Sparepart Cocok</th>
                                <th scope="col" class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($motorcycles as $m)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $loop->iteration + ($motorcycles->currentPage() - 1) * $motorcycles->perPage() }}</td>
                                    <td><span class="badge bg-dark">{{ $m->brand }}</span></td>
                                    <td class="fw-semibold">{{ $m->model_name }}</td>
                                    <td class="small text-muted text-nowrap">{{ $m->production_year }}</td>
                                    <td class="text-nowrap">
                                        <span class="badge bg-secondary">{{ $m->spareparts_count }} item</span>
                                    </td>
                                    <td class="text-center pe-4 text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary admin-icon-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $m->id }}"
                                                    title="Edit tipe motor {{ $m->model_name }}"
                                                    aria-label="Edit tipe motor {{ $m->model_name }}">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </button>

                                            @if($m->spareparts_count > 0)
                                                <span class="d-inline-block"
                                                      title="Tidak dapat dihapus karena masih terhubung dengan {{ $m->spareparts_count }} sparepart">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-secondary admin-icon-btn"
                                                            disabled
                                                            aria-label="Tipe motor {{ $m->model_name }} tidak dapat dihapus karena masih terhubung dengan {{ $m->spareparts_count }} sparepart">
                                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            @else
                                                <form action="{{ route('motorcycles.destroy', $m) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus tipe motor {{ addslashes($m->model_name) }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger admin-icon-btn"
                                                            title="Hapus tipe motor {{ $m->model_name }}"
                                                            aria-label="Hapus tipe motor {{ $m->model_name }}">
                                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-muted" aria-hidden="true"></i>
                                        <p class="fw-semibold mb-1">Belum ada data tipe motor</p>
                                        <p class="small text-muted mb-3">Tambahkan tipe motor pertama melalui formulir Tambah Tipe Motor agar muncul di pilihan garasi pelanggan.</p>
                                        <a href="#create-motor-brand"
                                           class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center px-3"
                                           onclick="document.getElementById('create-motor-brand')?.focus()">
                                            Isi Tipe Motor
                                        </a>
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

<!-- Modal Edit (ditempatkan di luar <table> agar DOM tabel valid) -->
@foreach($motorcycles as $m)
    @php
        $isCurrentModal = (string) $editingMotorcycleId === (string) $m->id;
    @endphp
    <div class="modal fade" id="editModal{{ $m->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $m->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('motorcycles.update', $m) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="editing_motorcycle_id" value="{{ $m->id }}">
                    <div class="modal-header border-0 pb-0">
                        <h2 class="h6 modal-title fw-bold" id="editModalLabel{{ $m->id }}">Edit Tipe Motor</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-motor-brand-{{ $m->id }}" class="form-label small fw-semibold">
                                Pabrikan / Brand <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="edit-motor-brand-{{ $m->id }}"
                                   name="brand"
                                   class="form-control {{ $isCurrentModal && $errors->has('brand') ? 'is-invalid' : '' }}"
                                   value="{{ $isCurrentModal ? old('brand') : $m->brand }}"
                                   list="brandList"
                                   required>
                            @if($isCurrentModal && $errors->has('brand'))
                                <div class="invalid-feedback">{{ $errors->first('brand') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="edit-motor-model-{{ $m->id }}" class="form-label small fw-semibold">
                                Nama Model <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="edit-motor-model-{{ $m->id }}"
                                   name="model_name"
                                   class="form-control {{ $isCurrentModal && $errors->has('model_name') ? 'is-invalid' : '' }}"
                                   value="{{ $isCurrentModal ? old('model_name') : $m->model_name }}"
                                   required>
                            @if($isCurrentModal && $errors->has('model_name'))
                                <div class="invalid-feedback">{{ $errors->first('model_name') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="edit-motor-year-{{ $m->id }}" class="form-label small fw-semibold">
                                Tahun Produksi <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="edit-motor-year-{{ $m->id }}"
                                   name="production_year"
                                   class="form-control {{ $isCurrentModal && $errors->has('production_year') ? 'is-invalid' : '' }}"
                                   value="{{ $isCurrentModal ? old('production_year') : $m->production_year }}"
                                   required>
                            @if($isCurrentModal && $errors->has('production_year'))
                                <div class="invalid-feedback">{{ $errors->first('production_year') }}</div>
                            @endif
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
@endforeach
@endsection

@if($editingMotorcycleId !== null && $errors->any())
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const failedModalEl = document.getElementById('editModal{{ (int) $editingMotorcycleId }}');
        if (failedModalEl && window.bootstrap) {
            new window.bootstrap.Modal(failedModalEl).show();
        }
    });
</script>
@endpush
@endif
