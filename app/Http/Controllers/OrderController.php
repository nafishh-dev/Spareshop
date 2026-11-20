<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    private const INSTALLATION_FEE_PER_UNIT = 25000;

    public function availableSlots(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        $bookedSlots = Order::query()
            ->whereDate('booking_date', $validated['date'])
            ->where('status', '!=', 'batal')
            ->selectRaw('booking_time, COUNT(*) as booked')
            ->groupBy('booking_time')
            ->pluck('booked', 'booking_time');

        $slots = collect(Order::BOOKING_SLOTS)
            ->map(function (string $slot) use ($bookedSlots) {
                $booked = (int) ($bookedSlots[$slot] ?? 0);
                $remaining = max(0, Order::MAX_SLOT_QUOTA - $booked);

                return [
                    'slot' => $slot,
                    'booked' => $booked,
                    'remaining' => $remaining,
                    'available' => $remaining > 0,
                ];
            })
            ->values();

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => ['required', 'in:beli_saja,pasang_bengkel'],
            'booking_date' => [
                'exclude_unless:service_type,pasang_bengkel',
                'required',
                'date',
                'after_or_equal:today',
            ],
            'booking_time' => [
                'exclude_unless:service_type,pasang_bengkel',
                'required',
                Rule::in(Order::BOOKING_SLOTS),
            ],
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

            if ($validated['service_type'] === 'pasang_bengkel') {
                $bookedCount = Order::query()
                    ->whereDate('booking_date', $validated['booking_date'])
                    ->where('booking_time', $validated['booking_time'])
                    ->where('status', '!=', 'batal')
                    ->count();

                if ($bookedCount >= Order::MAX_SLOT_QUOTA) {
                    throw ValidationException::withMessages([
                        'booking_time' => 'Kuota sesi jam tersebut sudah penuh. Silakan pilih sesi lain.',
                    ]);
                }
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
                'booking_time' => $validated['service_type'] === 'pasang_bengkel'
                    ? $validated['booking_time']
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
        $orders = Order::with(['items', 'reviews'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.orders.index', compact('orders'));
    }

    public function review(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        abort_unless($order->status === 'selesai', 403);

        $order->load(['items.sparepart', 'reviews']);

        return view('customer.orders.review', compact('order'));
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

    public function cancel(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        $cancelled = DB::transaction(function () use ($order) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->user_id !== auth()->id()) {
                abort(403);
            }

            if ($lockedOrder->status !== 'pending') {
                return false;
            }

            foreach ($lockedOrder->items as $item) {
                Sparepart::whereKey($item->sparepart_id)
                    ->lockForUpdate()
                    ->increment('stock', $item->qty);
            }

            $lockedOrder->update(['status' => 'batal']);

            return true;
        }, 5);

        if (! $cancelled) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan dan stok sparepart telah dikembalikan.');
    }
}
