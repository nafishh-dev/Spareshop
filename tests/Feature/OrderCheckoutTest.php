<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_order_reduces_stock_and_clears_cart(): void
    {
        $user = User::factory()->create(['phone' => '081234567890']);
        $category = Category::create(['name' => 'Oli', 'slug' => 'oli']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'OLI-TEST',
            'name' => 'Oli Uji',
            'brand' => 'MotoPart',
            'price' => 50000,
            'stock' => 4,
        ]);
        Cart::create([
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('orders.checkout'), [
            'order_type' => 'pasang_bengkel',
            'customer_note' => 'Pasang dengan hati-hati',
        ]);

        $order = Order::firstOrFail();

        $response->assertRedirect(route('orders.show', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'service_fee' => 50000,
            'customer_note' => 'Pasang dengan hati-hati',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 2,
            'unit_price' => 50000,
        ]);
        $this->assertDatabaseHas('spareparts', [
            'id' => $sparepart->id,
            'stock' => 2,
        ]);
        $this->assertDatabaseCount('carts', 0);

        $this->actingAs($user)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('Pasang dengan hati-hati')
            ->assertSee('Cetak Nota');

        $this->actingAs($user)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee($order->invoice_number)
            ->assertSee('Lihat & Cetak Nota');
    }

    public function test_checkout_rejects_insufficient_stock_without_creating_an_order(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Rem', 'slug' => 'rem']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'REM-TEST',
            'name' => 'Kampas Rem Uji',
            'brand' => 'MotoPart',
            'price' => 45000,
            'stock' => 1,
        ]);
        Cart::create([
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 2,
        ]);

        $response = $this->actingAs($user)
            ->from(route('cart.index'))
            ->post(route('orders.checkout'), [
                'order_type' => 'beli_saja',
            ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHasErrors('stock');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('spareparts', [
            'id' => $sparepart->id,
            'stock' => 1,
        ]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 2,
        ]);
    }
}
