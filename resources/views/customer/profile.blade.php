@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <h1 class="h3 fw-bold mb-1">Profil Saya</h1>
                <p class="text-secondary mb-0">Kelola informasi akun dan motor utama Anda.</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
                            <div class="form-text">Email akun tidak dapat diubah dari halaman ini.</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" maxlength="20">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" maxlength="500">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="motorcycle_id" class="form-label fw-semibold">Pilihan Motor Utama</label>
                            <select id="motorcycle_id" name="motorcycle_id" class="form-select @error('motorcycle_id') is-invalid @enderror">
                                <option value="">Pilih motor (opsional)</option>
                                @foreach($motorcycles as $motorcycle)
                                    <option value="{{ $motorcycle->id }}" @selected(old('motorcycle_id', $user->motorcycle_id) == $motorcycle->id)>
                                        {{ $motorcycle->brand }} {{ $motorcycle->model_name }} ({{ $motorcycle->production_year }})
                                    </option>
                                @endforeach
                            </select>
                            @error('motorcycle_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-crimson">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('katalog.index') }}" class="btn btn-outline-secondary">Kembali ke Katalog</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
