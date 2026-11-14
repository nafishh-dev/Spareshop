@extends('layouts.app')

@section('title', 'Nota ' . $order->invoice_number)

@push('styles')
    <style>
        @media print {
            nav,
            footer,
            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
                color: #000 !important;
            }

            main {
                padding: 0 !important;
            }

            .container {
                width: 100% !important;
                max-width: none !important;
            }

            .card {
                border: 1px solid #dee2e6 !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush

@section('content')
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
    @endphp

    <div class="d-flex justify-content-end gap-2 mb-3 no-print">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Riwayat Pesanan
        </a>
        <button type="button" class="btn btn-crimson" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>Cetak Nota
        </button>
    </div>

    <article class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <header class="d-flex flex-wrap justify-content-between gap-4 border-bottom pb-4 mb-4">
                <div>
                    <h1 class="h3 fw-bold mb-1">MotoPart <span class="text-crimson">Store</span></h1>
                    <p class="text-secondary mb-1">Suku Cadang &amp; Booking Bengkel</p>
                    <p class="small text-secondary mb-0">Jl. Otomotif Raya No. 88, Jakarta</p>
                    <p class="small text-secondary mb-0">+62 812-3456-7890 &middot; support@motopartstore.id</p>
                </div>
                <div class="text-md-end">
                    <h2 class="h4 fw-bold text-crimson">NOTA TRANSAKSI</h2>
                    <div class="fw-semibold">{{ $order->invoice_number }}</div>
                    <div class="text-secondary">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }} mt-2">
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </div>
            </header>

            <section class="row g-4 mb-4">
                <div class="col-md-6">
                    <h3 class="h6 text-secondary text-uppercase">Pelanggan</h3>
                    <div class="fw-semibold">{{ $order->user->name }}</div>
                    <div>{{ $order->user->email }}</div>
                    <div>{{ $order->user->phone ?: 'Nomor telepon belum tersedia' }}</div>
                </div>
                <div class="col-md-6">
                    <h3 class="h6 text-secondary text-uppercase">Layanan</h3>
                    <div class="fw-semibold">
                        {{ $order->order_type === 'pasang_bengkel' ? 'Pasang di bengkel' : 'Beli sparepart saja' }}
                    </div>
                    @if($order->booking_date)
                        <div>Tanggal booking: {{ $order->booking_date->format('d/m/Y') }}</div>
                    @endif
                    <div class="mt-2">
                        <span class="text-secondary">Catatan pelanggan:</span>
                        <p class="mb-0">{{ $order->customer_note ?: 'Tidak ada catatan.' }}</p>
                    </div>
                </div>
            </section>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Sparepart</th>
                            <th scope="col" class="text-end">Harga Satuan</th>
                            <th scope="col" class="text-center">Jumlah</th>
                            <th scope="col" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item->sparepart->name }}</div>
                                    <div class="small text-secondary">{{ $item->sparepart->part_code }} &middot; {{ $item->sparepart->brand }}</div>
                                </td>
                                <td class="text-end text-nowrap">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->qty }}</td>
                                <td class="text-end text-nowrap">Rp {{ number_format($item->unit_price * $item->qty, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-6 col-lg-5">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal sparepart</span>
                        <span>Rp {{ number_format($total - $order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya jasa pasang</span>
                        <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total Bayar</span>
                        <span class="text-crimson">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <p class="small text-center text-secondary border-top pt-3 mt-4 mb-0">
                Terima kasih telah berbelanja di MotoPart Store.
            </p>
        </div>
    </article>
@endsection
