@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <h1 class="h3 fw-bold mb-4"><i class="bi bi-cart3 text-crimson me-2"></i>Keranjang Belanja</h1>

    @if($cartItems->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x text-crimson display-4"></i>
                <h2 class="h5 fw-bold mt-3">Keranjang Anda masih kosong</h2>
                <p class="text-secondary">Silakan pilih sparepart dari katalog.</p>
                <a href="{{ route('katalog.index') }}" class="btn btn-crimson">
                    <i class="bi bi-grid me-1"></i>Lihat Katalog
                </a>
            </div>
        </div>
    @else
        @php
            $installationFee = $cartItems->sum('qty') * 25000;
        @endphp
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3">Sparepart</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col"><span class="visually-hidden">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($item->sparepart->image)
                                                    <img src="{{ asset('storage/' . $item->sparepart->image) }}" alt="{{ $item->sparepart->name }}" class="rounded" style="width: 64px; height: 64px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-secondary" style="width: 64px; height: 64px;">
                                                        <i class="bi bi-gear-wide-connected fs-4" aria-hidden="true"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $item->sparepart->name }}</div>
                                                    <div class="small text-secondary">{{ $item->sparepart->brand }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">Rp {{ number_format($item->sparepart->price, 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->sparepart->stock }}" class="form-control form-control-sm" style="width: 76px;" aria-label="Jumlah {{ $item->sparepart->name }}">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" aria-label="Perbarui jumlah">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-nowrap fw-semibold">Rp {{ number_format($item->qty * $item->sparepart->price, 0, ',', '.') }}</td>
                                        <td class="text-end pe-3">
                                            <form action="{{ route('cart.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Hapus {{ $item->sparepart->name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ route('katalog.index') }}" class="btn btn-link text-crimson px-0 mt-3">
                    <i class="bi bi-arrow-left me-1"></i>Lanjut belanja
                </a>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">Ringkasan Belanja</h2>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Subtotal sparepart</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary">Biaya pasang</span>
                            <span id="installation-fee">Rp {{ number_format($installationFee, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total sementara</span>
                            <span id="cart-total" class="text-crimson">Rp {{ number_format($subtotal + $installationFee, 0, ',', '.') }}</span>
                        </div>
                        <p class="small text-secondary mt-2 mb-0">Biaya jasa pemasangan dihitung Rp 25.000 per unit sparepart.</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">Persiapan Checkout</h2>
                        <form action="{{ route('orders.checkout') }}" method="POST">
                            @csrf
                            <fieldset>
                                <legend class="form-label fw-semibold">Pilih layanan</legend>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="service_type" id="order-installation" value="pasang_bengkel" data-fee="{{ $installationFee }}" @checked(old('service_type', 'pasang_bengkel') === 'pasang_bengkel')>
                                    <label class="form-check-label" for="order-installation">
                                        Pasang di bengkel <span class="small text-secondary">(Rp 25.000/item)</span>
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="service_type" id="order-purchase" value="beli_saja" data-fee="0" @checked(old('service_type') === 'beli_saja')>
                                    <label class="form-check-label" for="order-purchase">Beli sparepart saja</label>
                                </div>
                            </fieldset>
                            <div id="booking-date-container" class="mb-3">
                                <label for="booking_date" class="form-label fw-semibold">Tanggal Booking Pasang</label>
                                <input type="date" name="booking_date" id="booking_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('booking_date') }}">
                                @error('booking_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="customer-note" class="form-label fw-semibold">Catatan pelanggan</label>
                                <textarea class="form-control" id="customer-note" name="customer_note" rows="3" placeholder="Tambahkan catatan untuk pesanan Anda">{{ old('customer_note') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-crimson w-100">
                                <i class="bi bi-credit-card me-1"></i>Checkout
                            </button>
                            <p class="small text-secondary mt-2 mb-0">Pastikan pilihan layanan dan catatan pesanan Anda sudah benar.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        const bookingDateContainer = document.getElementById('booking-date-container');
        const bookingDateInput = document.getElementById('booking_date');

        const updateCheckoutOptions = () => {
            if (!bookingDateContainer || !bookingDateInput) {
                return;
            }

            const selectedService = document.querySelector('input[name="service_type"]:checked');
            const installationSelected = selectedService && selectedService.value === 'pasang_bengkel';

            bookingDateContainer.classList.toggle('d-none', !installationSelected);
            bookingDateInput.required = installationSelected;
            if (!installationSelected) {
                bookingDateInput.value = '';
            }
        };

        document.querySelectorAll('input[name="service_type"]').forEach((option) => {
            option.addEventListener('change', () => {
                const fee = Number(option.dataset.fee);
                const subtotal = {{ $subtotal }};
                const formatRupiah = (amount) => 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);

                document.getElementById('installation-fee').textContent = formatRupiah(fee);
                document.getElementById('cart-total').textContent = formatRupiah(subtotal + fee);
                updateCheckoutOptions();
            });
        });

        updateCheckoutOptions();
    </script>
@endpush
