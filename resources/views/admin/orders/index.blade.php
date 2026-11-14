@extends('layouts.admin')

@section('title', 'Pesanan Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Pesanan Masuk</h5>
        <small class="text-muted">Total {{ $orders->total() }} pesanan tercatat</small>
    </div>
</div>

<!-- Filter & Search -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0"
                           placeholder="Cari invoice atau nama customer..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="order_type" class="form-select">
                    <option value="">Semua Tipe Pesanan</option>
                    <option value="beli_saja" {{ request('order_type') === 'beli_saja' ? 'selected' : '' }}>Beli Saja</option>
                    <option value="pasang_bengkel" {{ request('order_type') === 'pasang_bengkel' ? 'selected' : '' }}>Pasang Bengkel</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100">Filter</button>
                @if(request()->hasAny(['search', 'status', 'order_type']))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th class="ps-4">Invoice</th>
                        <th>Customer</th>
                        <th>Tipe</th>
                        <th>Jadwal Pasang</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 font-monospace fw-semibold">{{ $order->invoice_number }}</td>
                            <td>
                                <div class="fw-semibold">{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->user->phone ?? $order->user->email }}</small>
                            </td>
                            <td>
                                @if($order->order_type === 'pasang_bengkel')
                                    <span class="badge bg-info text-dark"><i class="bi bi-tools me-1"></i>Pasang Bengkel</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-box me-1"></i>Beli Saja</span>
                                @endif
                            </td>
                            <td>
                                @if($order->booking_date)
                                    <span class="small fw-semibold"><i class="bi bi-calendar-event text-crimson me-1"></i>{{ \Carbon\Carbon::parse($order->booking_date)->translatedFormat('d M Y') }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="fw-bold text-crimson">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
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
                            <td class="text-center pe-4">
                                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#orderDetail{{ $order->id }}">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Belum ada data pesanan.
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
    <div class="modal fade" id="orderDetail{{ $order->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom px-4">
                    <div>
                        <h6 class="modal-title fw-bold mb-0">Detail Pesanan #{{ $order->invoice_number }}</h6>
                        <small class="text-muted">{{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Info Customer & Booking -->
                    <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Nama Customer</small>
                            <strong>{{ $order->user->name }}</strong>
                            <div class="small text-muted">{{ $order->user->email }} | {{ $order->user->phone ?? 'Tanpa nomor' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Tipe Pesanan</small>
                            <strong>{{ $order->order_type === 'pasang_bengkel' ? 'Pasang di Bengkel' : 'Beli Saja' }}</strong>
                            @if($order->booking_date)
                                <div class="small text-danger fw-semibold">
                                    <i class="bi bi-clock me-1"></i>Booking: {{ \Carbon\Carbon::parse($order->booking_date)->translatedFormat('l, d F Y') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Item Pesanan -->
                    <h6 class="fw-bold mb-2">Item Sparepart</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end pe-3">Subtotal</th>
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
                                        <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="text-end fw-semibold pe-3">Rp {{ number_format($item->qty * $item->unit_price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @if($order->service_fee > 0)
                                    <tr>
                                        <td colspan="3" class="text-end text-muted">Biaya Jasa Pasang Bengkel</td>
                                        <td class="text-end fw-semibold pe-3">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold">Total Pembayaran</td>
                                    <td class="text-end fw-bold text-crimson fs-6 pe-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Update Status Form (Responsif & Rata Kanan) -->
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-6 col-lg-6">
                            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="p-3 border rounded-3 bg-white mb-0">
                                @csrf @method('PATCH')
                                <label class="form-label fw-semibold small mb-2">Ubah Status Pesanan</label>
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="batal" {{ $order->status === 'batal' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                    <button type="submit" class="btn btn-crimson px-3 fw-semibold">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection