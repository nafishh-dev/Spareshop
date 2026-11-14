@extends('layouts.admin')

@section('title', 'Tambah Sparepart')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('spareparts.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0">Tambah Sparepart</h5>
        <small class="text-muted">Isi form berikut untuk menambah produk baru</small>
    </div>
</div>

<form action="{{ route('spareparts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Informasi Produk</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Sparepart <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="cth. Oli AHM MPX2 0.8L" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kode Part <span class="text-danger">*</span></label>
                            <input type="text" name="part_code" class="form-control @error('part_code') is-invalid @enderror"
                                   value="{{ old('part_code') }}" placeholder="cth. OLI-AHM-MPX2" required>
                            @error('part_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Merk <span class="text-danger">*</span></label>
                            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                                   value="{{ old('brand') }}" placeholder="cth. AHM" required>
                            @error('brand')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Harga (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}" min="0" placeholder="45000" required>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kompatibilitas Motor -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-1">Kompatibilitas Motor</h6>
                    <p class="small text-muted mb-3">Centang tipe motor yang cocok untuk sparepart ini.</p>
                    <div class="row g-2">
                        @foreach($motorcycles as $m)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="motorcycles[]"
                                           value="{{ $m->id }}" id="motor_{{ $m->id }}"
                                           {{ in_array($m->id, old('motorcycles', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="motor_{{ $m->id }}">
                                        {{ $m->model_name }} <span class="text-muted">({{ $m->production_year }})</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Gambar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Foto Produk</h6>
                    <div id="imagePreviewWrap" class="bg-light rounded-3 d-flex align-items-center justify-content-center mb-3 border border-dashed" style="height:200px;overflow:hidden;">
                        <div id="imagePlaceholder" class="text-center text-secondary opacity-50">
                            <i class="bi bi-image fs-1 d-block"></i>
                            <small>Belum ada foto</small>
                        </div>
                        <img id="imagePreview" src="#" class="w-100 h-100 d-none" style="object-fit:cover;">
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    <small class="text-muted d-block mt-1">Maks. 2MB. Format: JPG, PNG, WEBP.</small>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-crimson px-5 fw-semibold">
            <i class="bi bi-check-lg me-1"></i> Simpan Sparepart
        </button>
        <a href="{{ route('spareparts.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.getElementById('imageInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imagePlaceholder').classList.add('d-none');
            const img = document.getElementById('imagePreview');
            img.src = e.target.result;
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
