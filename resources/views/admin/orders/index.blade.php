@extends('layouts.admin')

@section('title', 'Pesanan Masuk')

@push('styles')
<style>
    .booking-schedule-highlight {
        color: #B91C1C;
    }
</style>
@endpush

@section('content')
@php
    $isFiltered = request()->filled('search') || request()->filled('status') || request()->filled('order_type');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h5 fw-bold mb-0">Pesanan Masuk</h1>
        <small class="text-muted">Total {{ $orders->total() }} pesanan tercatat</small>
    </div>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6 col-lg-4">
                <label for="filter-order-search" class="visually-hidden">Cari invoice atau nama customer</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted" aria-hidden="true"></i>
                    </span>
                    <input type="text"
                           id="filter-order-search"
                           name="search"
                           class="form-control border-start-0"
                           placeholder="Cari invoice atau nama customer..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                <label for="filter-order-status" class="visually-hidden">Filter status pesanan</label>
                <select id="filter-order-status" name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                <label for="filter-order-type" class="visually-hidden">Filter tipe pesanan</label>
                <select id="filter-order-type" name="order_type" class="form-select">
                    <option value="">Semua Tipe Pesanan</option>
                    <option value="beli_saja" {{ request('order_type') === 'beli_saja' ? 'selected' : '' }}>Beli Saja</option>
                    <option value="pasang_bengkel" {{ request('order_type') === 'pasang_bengkel' ? 'selected' : '' }}>Pasang Bengkel</option>
                </select>
            </div>
            <div class="col-12 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark flex-grow-1 px-3">Filter</button>
                @if($isFiltered)
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center px-3">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Pesanan -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">Invoice</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Jadwal Pasang</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 font-monospace fw-semibold text-nowrap">{{ $order->invoice_number }}</td>
                            <td>
                                <div class="fw-semibold">{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->user->phone ?? $order->user->email }}</small>
                            </td>
                            <td class="text-nowrap">
                                @if($order->order_type === 'pasang_bengkel')
                                    <span class="badge bg-info text-dark"><i class="bi bi-tools me-1" aria-hidden="true"></i>Pasang Bengkel</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-box me-1" aria-hidden="true"></i>Beli Saja</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if($order->booking_date)
                                    <div class="small fw-semibold"><i class="bi bi-calendar-event text-crimson me-1" aria-hidden="true"></i>{{ \Carbon\Carbon::parse($order->booking_date)->translatedFormat('d M Y') }}</div>
                                    @if($order->booking_time)
                                        <small class="text-muted"><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ $order->booking_time }}</small>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="fw-bold text-crimson text-nowrap">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="text-nowrap">
                                @php
                                    $statusClass = [
                                        'pending'  => 'warning text-dark',
                                        'diproses' => 'primary',
                                        'selesai'  => 'success',
                                        'batal'    => 'danger',
                                    ][$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="text-center pe-4 text-nowrap">
                                <button type="button"
                                        class="btn btn-sm btn-outline-dark d-inline-flex align-items-center justify-content-center"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderDetail{{ $order->id }}"
                                        aria-label="Lihat detail pesanan {{ $order->invoice_number }}">
                                    <i class="bi bi-eye me-1" aria-hidden="true"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                @if($isFiltered)
                                    <i class="bi bi-search fs-2 d-block mb-2 text-muted" aria-hidden="true"></i>
                                    <p class="fw-semibold mb-1">Tidak ada pesanan yang cocok</p>
                                    <p class="small text-muted mb-3">Coba ubah kata kunci pencarian atau pilih filter status/tipe pesanan lain.</p>
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center px-3">
                                        Reset Filter
                                    </a>
                                @else
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-muted" aria-hidden="true"></i>
                                    <p class="fw-semibold mb-1">Belum ada pesanan masuk</p>
                                    <p class="small text-muted mb-0">Pesanan baru dari pelanggan akan otomatis tercatat di halaman ini.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
        <div class="card-footer bg-transparent border-0 px-4 py-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>

<!-- Modal Detail Pesanan (Ditempatkan di luar <table> agar struktur HTML valid) -->
@foreach($orders as $order)
    <div class="modal fade" id="orderDetail{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom px-4">
                    <div>
                        <h2 class="h6 modal-title fw-bold mb-0" id="orderDetailLabel{{ $order->id }}">Detail Pesanan #{{ $order->invoice_number }}</h2>
                        <small class="text-muted">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup detail pesanan"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Info Customer & Booking -->
                    <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Nama Customer</small>
                            <strong>{{ $order->user->name }}</strong>
                            <div class="small text-muted">{{ $order->user->email }} | {{ $order->user->phone ?? 'Tanpa nomor' }}</div>
                            @if($order->user->address)
                                <div class="small text-muted mt-1"><i class="bi bi-geo-alt me-1" aria-hidden="true"></i>{{ $order->user->address }}</div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Tipe Pesanan</small>
                            <strong>{{ $order->order_type === 'pasang_bengkel' ? 'Pasang di Bengkel' : 'Beli Saja' }}</strong>
                            @if($order->booking_date)
                                <div class="small booking-schedule-highlight fw-semibold mt-1">
                                    <i class="bi bi-clock me-1" aria-hidden="true"></i>Booking: {{ \Carbon\Carbon::parse($order->booking_date)->translatedFormat('l, d F Y') }}{{ $order->booking_time ? ' (' . $order->booking_time . ')' : '' }}
                                </div>
                            @endif
                        </div>
                        <div class="col-12 border-top pt-2">
                            <small class="text-muted d-block">Catatan Pelanggan</small>
                            <div class="small">{{ $order->customer_note ?: 'Tidak ada catatan.' }}</div>
                        </div>
                    </div>

                    <!-- Item Pesanan -->
                    <h3 class="h6 fw-bold mb-2">Item Sparepart</h3>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3">Item</th>
                                    <th scope="col" class="text-center">Qty</th>
                                    <th scope="col" class="text-end">Harga Satuan</th>
                                    <th scope="col" class="text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold">{{ $item->sparepart->name ?? 'Produk Dihapus' }}</div>
                                            <small class="text-muted font-monospace">{{ $item->sparepart->part_code ?? '-' }}</small>
                                        </td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        <td class="text-end text-nowrap">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold pe-3 text-nowrap">Rp {{ number_format($item->qty * $item->unit_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @if($order->service_fee > 0)
                                    <tr>
                                        <td colspan="3" class="text-end text-muted">Biaya Jasa Pasang Bengkel</td>
                                        <td class="text-end fw-semibold pe-3 text-nowrap">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold">Total Pembayaran</td>
                                    <td class="text-end fw-bold text-crimson fs-6 pe-3 text-nowrap">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Update Status Form (Responsif & Rata Kanan) -->
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-7 col-lg-6">
                            @if(in_array($order->status, ['selesai', 'batal'], true))
                                <div class="p-3 border rounded-3 bg-light text-muted small d-flex align-items-center gap-2 mb-0">
                                    <i class="bi bi-lock-fill text-secondary fs-6" aria-hidden="true"></i>
                                    <span>Status pesanan sudah final (<strong>{{ ucfirst($order->status) }}</strong>)</span>
                                </div>
                            @else
                                <form action="{{ route('admin.orders.updateStatus', $order) }}"
                                      method="POST"
                                      class="p-3 border rounded-3 bg-white mb-0 js-order-status-form"
                                      data-invoice="{{ $order->invoice_number }}">
                                    @csrf @method('PATCH')
                                    <label for="order-status-{{ $order->id }}" class="form-label fw-semibold small mb-2">
                                        Ubah Status Pesanan
                                    </label>
                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                        <select id="order-status-{{ $order->id }}" name="status" class="form-select flex-grow-1">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="batal">Batal</option>
                                        </select>
                                        <button type="submit" class="btn btn-crimson px-3 fw-semibold text-nowrap">Update Status</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.js-order-status-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const select = form.querySelector('select[name="status"]');
            const invoice = form.dataset.invoice || '';
            if (!select) return;
            if (select.value === 'batal') {
                if (!window.confirm('Ubah status pesanan ' + invoice + ' ke Batal? Stok sparepart akan dikembalikan dan status tidak dapat diubah lagi.')) {
                    e.preventDefault();
                }
            } else if (select.value === 'selesai') {
                if (!window.confirm('Tandai pesanan ' + invoice + ' sebagai Selesai? Status pesanan akan dikunci permanen.')) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
@endpush


@push('scripts')
<script>
    document.querySelectorAll('.js-order-status-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const select = form.querySelector('select[name="status"]');
            const invoice = form.dataset.invoice || '';
            if (!select) return;
            if (select.value === 'batal') {
                if (!window.confirm('Ubah status pesanan ' + invoice + ' ke Batal? Stok sparepart akan dikembalikan dan status tidak dapat diubah lagi.')) {
                    e.preventDefault();
                }
            } else if (select.value === 'selesai') {
                if (!window.confirm('Tandai pesanan ' + invoice + ' sebagai Selesai? Status pesanan akan dikunci permanen.')) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
@endpush