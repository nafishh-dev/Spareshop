@extends('layouts.admin')

@section('title', 'Master Kategori')

@section('content')
@php
    $editingCategoryId = old('editing_category_id');
    $isCreateError = $editingCategoryId === null && $errors->has('name');
@endphp

<h1 class="visually-hidden">Master Kategori</h1>

<div class="row g-4">
    <!-- Form Tambah -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h2 class="h6 fw-bold mb-3">
                    <i class="bi bi-plus-circle text-crimson me-2" aria-hidden="true"></i>Tambah Kategori
                </h2>
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="create-category-name" class="form-label small fw-semibold">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="create-category-name"
                               name="name"
                               class="form-control {{ $isCreateError ? 'is-invalid' : '' }}"
                               placeholder="cth. Ban &amp; Velg"
                               value="{{ $editingCategoryId === null ? old('name') : '' }}"
                               required>
                        @if($isCreateError)
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-crimson w-100 fw-semibold d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-check-lg me-1" aria-hidden="true"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h2 class="h6 fw-bold mb-0">Daftar Kategori</h2>
                <span class="badge bg-dark">{{ $categories->total() }} kategori</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 mt-3">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4" style="width: 60px;">#</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Jumlah Part</th>
                                <th scope="col" class="text-center pe-4">Aksi</th>
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
                                    <td class="text-center pe-4 text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary admin-icon-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $cat->id }}"
                                                    title="Edit kategori {{ $cat->name }}"
                                                    aria-label="Edit kategori {{ $cat->name }}">
                                                <i class="bi bi-pencil" aria-hidden="true"></i>
                                            </button>

                                            @if($cat->spareparts_count > 0)
                                                <span class="d-inline-block"
                                                      title="Tidak dapat dihapus karena masih memuat {{ $cat->spareparts_count }} sparepart">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-secondary admin-icon-btn"
                                                            disabled
                                                            aria-label="Kategori {{ $cat->name }} tidak dapat dihapus karena masih memuat {{ $cat->spareparts_count }} sparepart">
                                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            @else
                                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Hapus kategori {{ addslashes($cat->name) }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger admin-icon-btn"
                                                            title="Hapus kategori {{ $cat->name }}"
                                                            aria-label="Hapus kategori {{ $cat->name }}">
                                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-muted" aria-hidden="true"></i>
                                        <p class="fw-semibold mb-1">Belum ada data kategori</p>
                                        <p class="small text-muted mb-3">Tambahkan kategori pertama melalui formulir Tambah Kategori untuk mulai mengelompokkan produk.</p>
                                        <a href="#create-category-name"
                                           class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center px-3"
                                           onclick="document.getElementById('create-category-name')?.focus()">
                                            Isi Nama Kategori
                                        </a>
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

<!-- Modal Edit (ditempatkan di luar <table> agar DOM tabel valid) -->
@foreach($categories as $cat)
    @php
        $isCurrentEditError = (string) $editingCategoryId === (string) $cat->id && $errors->has('name');
    @endphp
    <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $cat->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('categories.update', $cat->id) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="editing_category_id" value="{{ $cat->id }}">
                    <div class="modal-header border-0 pb-0">
                        <h2 class="h6 modal-title fw-bold" id="editModalLabel{{ $cat->id }}">Edit Kategori</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="edit-category-name-{{ $cat->id }}" class="form-label small fw-semibold">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="edit-category-name-{{ $cat->id }}"
                               name="name"
                               class="form-control {{ $isCurrentEditError ? 'is-invalid' : '' }}"
                               value="{{ (string) $editingCategoryId === (string) $cat->id ? old('name') : $cat->name }}"
                               required>
                        @if($isCurrentEditError)
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
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

@if($editingCategoryId !== null && $errors->any())
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const failedModalEl = document.getElementById('editModal{{ (int) $editingCategoryId }}');
        if (failedModalEl && window.bootstrap) {
            new window.bootstrap.Modal(failedModalEl).show();
        }
    });
</script>
@endpush
@endif
