@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Riwayat Pesanan</h1>
            <p class="text-secondary mb-0">Lihat status dan nota transaksi Anda.</p>
        </div>
        <a href="{{ route('katalog.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Katalog
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-receipt text-crimson display-4"></i>
                <h2 class="h5 fw-bold mt-3">Belum ada pesanan</h2>
                <p class="text-secondary">Pesanan yang sudah checkout akan muncul di sini.</p>
                <a href="{{ route('katalog.index') }}" class="btn btn-crimson">Mulai Belanja</a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Nomor Pesanan</th>
                            <th scope="col">Tanggal Transaksi</th>
                            <th scope="col">Layanan</th>
                            <th scope="col">Jadwal Pasang</th>
                            <th scope="col">Total Bayar</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end pe-3">Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $statusLabels = [
                                    'pending' => 'Menunggu',
                                    'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'batal' => 'Dibatalkan',
                                ];
                                $statusClasses = [
                                    'pending' => 'text-bg-warning',
                                    'diproses' => 'text-bg-primary',
                                    'selesai' => 'text-bg-success',
                                    'batal' => 'text-bg-danger',
                                ];
                                $orderTotal = $order->items->sum(fn ($item) => $item->qty * $item->unit_price) + $order->service_fee;
                            @endphp
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $order->invoice_number }}</td>
                                <td class="text-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $order->order_type === 'pasang_bengkel' ? 'Pasang di bengkel' : 'Beli saja' }}</td>
                                <td class="text-nowrap">{{ $order->booking_date ? $order->booking_date->format('d/m/Y') : '-' }}</td>
                                <td class="text-nowrap fw-semibold">Rp {{ number_format($orderTotal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-crimson">
                                        <i class="bi bi-receipt me-1"></i>Lihat &amp; Cetak Nota
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
