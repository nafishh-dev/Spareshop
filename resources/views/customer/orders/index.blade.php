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
                            <th scope="col" class="text-start" style="min-width: 195px;">Aksi</th>
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
                                    'batal' => 'bg-danger',
                                ];
                                $orderTotal = $order->items->sum(fn ($item) => $item->qty * $item->unit_price) + $order->service_fee;
                                $allItemsReviewed = $order->items->every(
                                    fn ($item) => $order->reviews->contains('sparepart_id', $item->sparepart_id)
                                );
                            @endphp
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $order->invoice_number }}</td>
                                <td class="text-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $order->order_type === 'pasang_bengkel' ? 'Pasang di bengkel' : 'Beli saja' }}</td>
                                <td class="text-nowrap">
                                    {{ $order->booking_date ? $order->booking_date->format('d/m/Y') : '-' }}
                                    @if($order->booking_time)
                                        <span class="text-secondary">({{ $order->booking_time }})</span>
                                    @endif
                                </td>
                                <td class="text-nowrap fw-semibold">Rp {{ number_format($orderTotal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start gap-2 text-nowrap">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-danger btn-sm">
                                            <i class="bi bi-receipt me-1"></i>Nota
                                        </a>
                                        @if($order->status === 'pending')
                                            <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini? Stok sparepart akan dikembalikan.')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle me-1"></i>Batalkan
                                                </button>
                                            </form>
                                        @endif
                                        @if($order->status === 'selesai')
                                            @if($allItemsReviewed)
                                                <a href="{{ route('orders.review', $order) }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="bi bi-check-circle me-1"></i>Diulas
                                                </a>
                                            @else
                                                <a href="{{ route('orders.review', $order) }}" class="btn btn-warning btn-sm fw-semibold">
                                                    <i class="bi bi-star-fill me-1"></i>Ulas
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
