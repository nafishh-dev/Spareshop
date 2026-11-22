<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembaruan Status Pesanan - MotoPart Store</title>
</head>
<body style="margin:0;padding:0;background-color:#F8FAFC;font-family:'Plus Jakarta Sans',Arial,sans-serif;color:#1E293B;line-height:1.6;">
    @php
        $statusMeta = match ($order->status) {
            'diproses' => [
                'label' => 'Diproses',
                'bg' => '#DBEAFE',
                'text' => '#1E40AF',
                'border' => '#93C5FD',
                'headline' => 'Pesanan Anda sedang disiapkan oleh tim kami.',
            ],
            'selesai' => [
                'label' => 'Selesai',
                'bg' => '#DCFCE7',
                'text' => '#166534',
                'border' => '#86EFAC',
                'headline' => 'Pesanan Anda telah selesai. Terima kasih telah berbelanja di MotoPart Store.',
            ],
            'batal' => [
                'label' => 'Dibatalkan',
                'bg' => '#FEE2E2',
                'text' => '#991B1B',
                'border' => '#FCA5A5',
                'headline' => 'Pesanan Anda telah dibatalkan. Stok sparepart telah dikembalikan ke gudang dan slot booking bengkel dilepas.',
            ],
            default => [
                'label' => ucfirst($order->status),
                'bg' => '#E2E8F0',
                'text' => '#1E293B',
                'border' => '#CBD5E1',
                'headline' => 'Status pesanan Anda telah diperbarui.',
            ],
        };
    @endphp
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F8FAFC;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background-color:#FFFFFF;border-radius:12px;overflow:hidden;border:1px solid #E2E8F0;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#1E293B;border-bottom:3px solid #DC2626;padding:20px 24px;">
                            <div style="font-size:20px;font-weight:700;color:#FFFFFF;letter-spacing:-0.01em;">
                                MotoPart <span style="color:#DC2626;">Store</span>
                            </div>
                            <div style="font-size:12px;color:#CBD5E1;margin-top:2px;">
                                Suku Cadang Motor Original &amp; Booking Bengkel
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 12px 0;font-size:16px;font-weight:600;color:#0F172A;">
                                Halo {{ $order->user->name ?? 'Pelanggan' }},
                            </p>

                            <div style="margin:0 0 16px 0;">
                                <span style="display:inline-block;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:700;background-color:{{ $statusMeta['bg'] }};color:{{ $statusMeta['text'] }};border:1px solid {{ $statusMeta['border'] }};">
                                    Status: {{ $statusMeta['label'] }}
                                </span>
                            </div>

                            <p style="margin:0 0 16px 0;font-size:14px;color:#334155;">
                                {{ $statusMeta['headline'] }}
                            </p>

                            @if($order->status === 'diproses' && $order->order_type === 'pasang_bengkel' && $order->booking_date)
                                <div style="background-color:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;margin:0 0 18px 0;">
                                    <div style="font-size:12px;font-weight:700;color:#991B1B;margin-bottom:4px;">
                                        Pengingat Jadwal Pasang di Bengkel
                                    </div>
                                    <div style="font-size:14px;font-weight:600;color:#0F172A;">
                                        {{ \Illuminate\Support\Carbon::parse($order->booking_date)->translatedFormat('l, d F Y') }}
                                        @if($order->booking_time)
                                            (Sesi {{ $order->booking_time }} WIB)
                                        @endif
                                    </div>
                                    <div style="font-size:12px;color:#475569;margin-top:4px;">
                                        Mohon hadir 10 menit sebelum sesi mekanik dimulai.
                                    </div>
                                </div>
                            @elseif($order->status === 'selesai')
                                <div style="background-color:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:12px 16px;margin:0 0 18px 0;">
                                    <div style="font-size:13px;color:#166534;">
                                        Bagikan pengalaman Anda dengan memberi rating dan ulasan untuk sparepart pada pesanan ini agar membantu pengendara lain.
                                    </div>
                                </div>
                            @endif

                            <!-- Meta Ringkasan -->
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;margin-bottom:18px;">
                                <tr>
                                    <td style="padding:12px 16px;font-size:13px;color:#475569;">
                                        <strong style="color:#0F172A;">No. Invoice:</strong> {{ $order->invoice_number }}<br>
                                        <strong style="color:#0F172A;">Layanan:</strong> {{ $order->order_type === 'pasang_bengkel' ? 'Pasang di Bengkel' : 'Beli Saja' }}<br>
                                        <strong style="color:#0F172A;">Status Sebelumnya:</strong> {{ ucfirst($previousStatus) }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Rincian Item -->
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin-bottom:20px;font-size:13px;">
                                <thead>
                                    <tr style="background-color:#F1F5F9;color:#0F172A;">
                                        <th align="left" style="padding:10px 12px;border-bottom:1px solid #CBD5E1;">Produk</th>
                                        <th align="center" style="padding:10px 8px;border-bottom:1px solid #CBD5E1;">Qty</th>
                                        <th align="right" style="padding:10px 12px;border-bottom:1px solid #CBD5E1;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td style="padding:10px 12px;border-bottom:1px solid #E2E8F0;color:#1E293B;">
                                                <div style="font-weight:600;">{{ $item->sparepart->name ?? 'Produk Dihapus' }}</div>
                                                <div style="font-size:11px;color:#64748B;">{{ $item->sparepart->part_code ?? '-' }}</div>
                                            </td>
                                            <td align="center" style="padding:10px 8px;border-bottom:1px solid #E2E8F0;color:#334155;">{{ $item->qty }}</td>
                                            <td align="right" style="padding:10px 12px;border-bottom:1px solid #E2E8F0;color:#0F172A;white-space:nowrap;">
                                                Rp {{ number_format($item->qty * $item->unit_price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($order->service_fee > 0)
                                        <tr>
                                            <td colspan="2" align="right" style="padding:10px 12px;border-bottom:1px solid #E2E8F0;color:#475569;">
                                                Biaya Jasa Pasang Bengkel
                                            </td>
                                            <td align="right" style="padding:10px 12px;border-bottom:1px solid #E2E8F0;color:#0F172A;white-space:nowrap;">
                                                Rp {{ number_format($order->service_fee, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" align="right" style="padding:12px;font-weight:700;color:#0F172A;">
                                            Total Pembayaran
                                        </td>
                                        <td align="right" style="padding:12px;font-weight:700;font-size:15px;color:#DC2626;white-space:nowrap;">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- CTA -->
                            <div style="text-align:center;margin:24px 0 8px 0;">
                                @if($order->status === 'selesai')
                                    <a href="{{ route('orders.review', $order) }}"
                                       style="display:inline-block;background-color:#DC2626;color:#FFFFFF;text-decoration:none;font-weight:700;font-size:14px;padding:12px 24px;border-radius:8px;">
                                        Beri Ulasan Produk
                                    </a>
                                    <div style="margin-top:12px;">
                                        <a href="{{ route('orders.show', $order) }}" style="font-size:13px;color:#B91C1C;font-weight:600;text-decoration:underline;">
                                            Lihat Detail Nota
                                        </a>
                                    </div>
                                @else
                                    <a href="{{ route('orders.show', $order) }}"
                                       style="display:inline-block;background-color:#DC2626;color:#FFFFFF;text-decoration:none;font-weight:700;font-size:14px;padding:12px 24px;border-radius:8px;">
                                        Lihat Detail Pesanan
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#0F172A;padding:16px 24px;text-align:center;font-size:12px;color:#94A3B8;">
                            MotoPart Store | Jl. Otomotif Raya No. 88, Jakarta | support@motopartstore.id
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
