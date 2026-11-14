<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('sparepart')
            ->where('user_id', auth()->id())
            ->get();

        $subtotal = $cartItems->sum(
            fn (Cart $item) => $item->qty * $item->sparepart->price
        );

        return view('customer.cart', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sparepart_id' => ['required', 'integer', 'exists:spareparts,id'],
            'qty' => ['sometimes', 'integer', 'min:1'],
        ]);
        $qty = $validated['qty'] ?? 1;

        $result = DB::transaction(function () use ($validated, $qty) {
            $sparepart = Sparepart::whereKey($validated['sparepart_id'])
                ->lockForUpdate()
                ->firstOrFail();
            $cart = Cart::where('user_id', auth()->id())
                ->where('sparepart_id', $sparepart->id)
                ->lockForUpdate()
                ->first();
            $totalQty = ($cart?->qty ?? 0) + $qty;

            if ($totalQty > $sparepart->stock) {
                return false;
            }

            if ($cart) {
                $cart->update(['qty' => $totalQty]);
            } else {
                Cart::create([
                    'user_id' => auth()->id(),
                    'sparepart_id' => $sparepart->id,
                    'qty' => $qty,
                ]);
            }

            return true;
        });

        if (! $result) {
            return back()->with('error', 'Jumlah melebihi stok sparepart yang tersedia.');
        }

        return redirect()->route('cart.index')->with('success', 'Sparepart berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_unless((int) $cart->user_id === (int) auth()->id(), 403);

        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $inStock = DB::transaction(function () use ($cart, $validated) {
            $sparepart = Sparepart::whereKey($cart->sparepart_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['qty'] > $sparepart->stock) {
                return false;
            }

            $cart->update(['qty' => $validated['qty']]);

            return true;
        });

        if (! $inStock) {
            return back()->with('error', 'Jumlah melebihi stok sparepart yang tersedia.');
        }

        return redirect()->route('cart.index')->with('success', 'Jumlah item keranjang berhasil diperbarui.');
    }

    public function destroy(Cart $cart)
    {
        abort_unless((int) $cart->user_id === (int) auth()->id(), 403);

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
