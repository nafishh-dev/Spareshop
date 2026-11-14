<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private const INSTALLATION_FEE_PER_UNIT = 25000;

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'service_type' => ['required', 'in:beli_saja,pasang_bengkel'],
            'booking_date' => ['required_if:service_type,pasang_bengkel', 'nullable', 'date', 'after_or_equal:today'],
            'customer_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $userId = auth()->id();
            $cartItems = Cart::where('user_id', $userId)
                ->orderBy('sparepart_id')
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang Anda kosong. Tambahkan sparepart sebelum checkout.',
                ]);
            }

            $spareparts = [];
            foreach ($cartItems as $cartItem) {
                $sparepart = Sparepart::whereKey($cartItem->sparepart_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($cartItem->qty > $sparepart->stock) {
                    throw ValidationException::withMessages([
                        'stock' => "Stok {$sparepart->name} tidak mencukupi. Tersedia {$sparepart->stock} unit.",
                    ]);
                }

                $spareparts[$cartItem->id] = $sparepart;
            }

            $serviceFee = $validated['service_type'] === 'pasang_bengkel'
                ? $cartItems->sum('qty') * self::INSTALLATION_FEE_PER_UNIT
                : 0;

            $order = Order::create([
                'user_id' => $userId,
                'invoice_number' => 'INV-'.date('Ymd').'-'.strtoupper(Str::random(4)),
                'order_type' => $validated['service_type'],
                'booking_date' => $validated['service_type'] === 'pasang_bengkel'
                    ? $validated['booking_date']
                    : null,
                'service_fee' => $serviceFee,
                'status' => 'pending',
                'customer_note' => $validated['customer_note'] ?? null,
            ]);

            foreach ($cartItems as $cartItem) {
                $sparepart = $spareparts[$cartItem->id];

                $order->items()->create([
                    'sparepart_id' => $sparepart->id,
                    'qty' => $cartItem->qty,
                    'unit_price' => $sparepart->price,
                ]);

                $sparepart->decrement('stock', $cartItem->qty);
            }

            Cart::where('user_id', $userId)->delete();

            return $order;
        }, 5);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Checkout berhasil. Nota pesanan Anda sudah tersedia.');
    }

    public function index()
    {
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load(['user', 'items.sparepart']);
        $total = $order->items->sum(
            fn ($item) => $item->qty * $item->unit_price
        ) + $order->service_fee;

        return view('customer.orders.invoice', compact('order', 'total'));
    }
}
