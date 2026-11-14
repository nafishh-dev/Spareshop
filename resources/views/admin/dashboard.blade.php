@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    $totalSpareparts = \App\Models\Sparepart::count();
    $totalOrders     = \App\Models\Order::count();
    $totalCustomers  = \App\Models\User::where('role', 'customer')->count();
    $pendingOrders   = \App\Models\Order::where('status', 'pending')->count();
    $lowStock        = \App\Models\Sparepart::where('stock', '<', 5)->get();
    $recentOrders    = \App\Models\Order::with('user')->latest()->take(5)->get();
@endphp

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="rounded-3 p-3 fs-4 bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Sparepart</div>
                    <div class="fs-3 fw-bold">{{ $totalSpareparts }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="rounded-3 p-3 fs-4 bg-success bg-opacity-10 text-success">
                    <i class="bi bi-bag-check"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Pesanan</div>
                    <div class="fs-3 fw-bold">{{ $totalOrders }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="rounded-3 p-3 fs-4 bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div>
                    <div class="text-muted small">Pesanan Pending</div>
                    <div class="fs-3 fw-bold">{{ $pendingOrders }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="rounded-3 p-3 fs-4 bg-info bg-opacity-10 text-info">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Customer</div>
                    <div class="fs-3 fw-bold">{{ $totalCustomers }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-receipt text-crimson me-2"></i>Pesanan Terbaru</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="card-body p-4">
                @if($recentOrders->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>Belum ada pesanan masuk.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold">Invoice</th>
                                    <th class="fw-semibold">Customer</th>
                                    <th class="fw-semibold">Tipe</th>
                                    <th class="fw-semibold">Status</th>
                                    <th class="fw-semibold">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="font-monospace small">{{ $order->invoice_number }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>
                                            @if($order->order_type === 'pasang_bengkel')
                                                <span class="badge bg-info text-dark"><i class="bi bi-tools me-1"></i>Pasang</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="bi bi-box me-1"></i>Beli Saja</span>
                                            @endif
                                        </td>
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
                                        <td class="small text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"></i>Stok Menipis</h6>
                <span class="badge bg-warning text-dark">{{ $lowStock->count() }} item</span>
            </div>
            <div class="card-body p-4">
                @if($lowStock->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle fs-1 d-block mb-2 text-success opacity-50"></i>Stok aman.
                    </div>
                @else
                    <div class="d-flex flex-column gap-2">
                        @foreach($lowStock as $sp)
                            <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                <div>
                                    <div class="fw-semibold small">{{ $sp->name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem">{{ $sp->part_code }}</div>
                                </div>
                                <span class="badge bg-warning text-dark ms-2">Sisa {{ $sp->stock }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
