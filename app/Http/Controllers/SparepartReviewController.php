<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sparepart;
use App\Models\SparepartReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SparepartReviewController extends Controller
{
    public function store(Request $request, Order $order, Sparepart $sparepart)
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        abort_unless($order->status === 'selesai', 403);
        abort_unless($order->items()->where('sparepart_id', $sparepart->id)->exists(), 403);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $order, $sparepart, $validated) {
            $lockedOrder = Order::query()
                ->whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_if($lockedOrder->user_id !== $request->user()->id, 403);
            abort_unless($lockedOrder->status === 'selesai', 403);
            abort_unless($lockedOrder->items()->where('sparepart_id', $sparepart->id)->exists(), 403);
            abort_unless(
                ! $lockedOrder->reviews()->where('sparepart_id', $sparepart->id)->exists(),
                403
            );

            SparepartReview::create([
                'user_id' => $request->user()->id,
                'sparepart_id' => $sparepart->id,
                'order_id' => $lockedOrder->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]);
        });

        return redirect()
            ->route('orders.review', $order)
            ->with('success', 'Ulasan Anda berhasil disimpan.');
    }
}
