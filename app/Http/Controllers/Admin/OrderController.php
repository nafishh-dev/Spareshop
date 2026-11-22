<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.sparepart']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,diproses,selesai,batal'],
        ]);

        $result = DB::transaction(function () use ($validated, $order) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($lockedOrder->status, ['selesai', 'batal'], true)) {
                return ['outcome' => 'locked', 'previousStatus' => $lockedOrder->status];
            }

            $newStatus = $validated['status'];
            if ($newStatus === $lockedOrder->status) {
                return ['outcome' => 'unchanged', 'previousStatus' => $lockedOrder->status];
            }

            $previousStatus = $lockedOrder->status;

            if ($newStatus === 'batal') {
                $lockedOrder->loadMissing('items');

                foreach ($lockedOrder->items as $item) {
                    Sparepart::whereKey($item->sparepart_id)
                        ->lockForUpdate()
                        ->increment('stock', $item->qty);
                }
            }

            $lockedOrder->update(['status' => $newStatus]);

            return [
                'outcome' => $newStatus === 'batal' ? 'cancelled' : 'updated',
                'previousStatus' => $previousStatus,
            ];
        }, 5);

        if ($result['outcome'] === 'locked') {
            return back()->with('error', "Status pesanan {$order->invoice_number} sudah final dan tidak dapat diubah lagi.");
        }

        if ($result['outcome'] === 'unchanged') {
            return back()->with('error', "Status pesanan {$order->invoice_number} tidak berubah karena sudah berstatus " . ucfirst($validated['status']) . '.');
        }

        if (
            in_array($result['outcome'], ['updated', 'cancelled'], true)
            && in_array($validated['status'], ['diproses', 'selesai', 'batal'], true)
        ) {
            $order->refresh()->loadMissing(['user', 'items.sparepart']);
            if ($order->user && filled($order->user->email)) {
                try {
                    Mail::to($order->user->email)->queue(
                        new OrderStatusUpdatedMail($order, $result['previousStatus'])
                    );
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }

        if ($result['outcome'] === 'cancelled') {
            return back()->with('success', "Status pesanan {$order->invoice_number} berhasil diubah ke Batal dan stok sparepart telah dikembalikan.");
        }

        return back()->with('success', "Status pesanan {$order->invoice_number} berhasil diubah ke " . ucfirst($validated['status']) . '.');
    }
}
