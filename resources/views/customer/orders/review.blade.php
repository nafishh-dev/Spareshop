@extends('layouts.app')

@section('title', 'Ulasan Pesanan')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Ulasan Pesanan</h1>
            <p class="text-secondary mb-1">Nomor invoice: <span class="fw-semibold text-dark">{{ $order->invoice_number }}</span></p>
            <p class="text-secondary mb-0">Tanggal transaksi: {{ $order->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Riwayat Pesanan
        </a>
    </div>

    <div class="d-flex flex-column gap-3">
        @foreach($order->items as $item)
            @php
                $review = $order->reviews->firstWhere('sparepart_id', $item->sparepart_id);
            @endphp
            <article class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                        <div>
                            <h2 class="h5 fw-bold mb-1">{{ $item->sparepart->name }}</h2>
                            <p class="small text-secondary mb-0">
                                SKU {{ $item->sparepart->part_code }} &middot;
                                {{ $item->qty }} unit
                            </p>
                        </div>
                        @if($review)
                            <span class="badge text-bg-success">
                                <i class="bi bi-check-lg me-1"></i>Sudah Diulas
                            </span>
                        @endif
                    </div>

                    @if($review)
                        <div class="mt-3">
                            <div class="text-warning" aria-label="Rating {{ $review->rating }} dari 5">
                                @for($star = 1; $star <= 5; $star++)
                                    <i class="bi {{ $star <= $review->rating ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
                                @endfor
                                <span class="small text-secondary ms-1">{{ $review->rating }} dari 5</span>
                            </div>
                            @if($review->comment)
                                <p class="mb-0 mt-2">{{ $review->comment }}</p>
                            @else
                                <p class="small text-secondary mb-0 mt-2">Tidak ada komentar.</p>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('orders.items.review', [$order, $item->sparepart]) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="mb-3">
                                <label for="rating-{{ $item->id }}" class="form-label fw-semibold">Rating</label>
                                <select id="rating-{{ $item->id }}" name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                    <option value="">Pilih rating</option>
                                    @for($rating = 5; $rating >= 1; $rating--)
                                        <option value="{{ $rating }}" @selected(old('rating') == $rating)>
                                            {{ $rating }} bintang
                                        </option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="comment-{{ $item->id }}" class="form-label fw-semibold">Komentar (opsional)</label>
                                <textarea id="comment-{{ $item->id }}" name="comment" rows="3" maxlength="1000" class="form-control @error('comment') is-invalid @enderror">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-star-fill me-1"></i>Kirim Ulasan
                            </button>
                        </form>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@endsection
