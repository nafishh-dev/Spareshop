<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
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

        $bookingDate = now()->addDay()->toDateString();
        $this->actingAs($user)
            ->getJson(route('booking.slots', ['date' => $bookingDate]))
            ->assertOk()
            ->assertJsonPath('slots.0.booked', 0)
            ->assertJsonPath('slots.0.remaining', 2)
            ->assertJsonPath('slots.0.available', true);

        $response = $this->actingAs($user)->post(route('orders.checkout'), [
            'service_type' => 'pasang_bengkel',
            'booking_date' => $bookingDate,
            'booking_time' => Order::BOOKING_SLOTS[0],
            'customer_note' => 'Pasang dengan hati-hati',
        ]);

        $order = Order::firstOrFail();

        $response->assertRedirect(route('orders.show', $order));
        $this->assertMatchesRegularExpression('/^INV-\d{8}-[A-Z0-9]{4}$/', $order->invoice_number);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'service_fee' => 50000,
            'customer_note' => 'Pasang dengan hati-hati',
        ]);
        $this->assertSame(now()->addDay()->toDateString(), $order->booking_date->toDateString());
        $this->assertSame(Order::BOOKING_SLOTS[0], $order->booking_time);
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
            ->assertSee($order->booking_date->format('d/m/Y'))
            ->assertSee(Order::BOOKING_SLOTS[0])
            ->assertSee('Cetak Nota');

        $this->actingAs($user)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee($order->invoice_number)
            ->assertSee(Order::BOOKING_SLOTS[0])
            ->assertSee(route('orders.show', $order), false)
            ->assertSee('Nota');

        $this->actingAs($user)
            ->getJson(route('booking.slots', ['date' => $bookingDate]))
            ->assertOk()
            ->assertJsonPath('slots.0.booked', 1)
            ->assertJsonPath('slots.0.remaining', 1)
            ->assertJsonPath('slots.0.available', true);
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
                'service_type' => 'beli_saja',
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

    public function test_installation_checkout_requires_a_booking_date(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Aki', 'slug' => 'aki']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'AKI-TEST',
            'name' => 'Aki Uji',
            'brand' => 'MotoPart',
            'price' => 100000,
            'stock' => 2,
        ]);
        Cart::create([
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
        ]);

        $response = $this->actingAs($user)
            ->from(route('cart.index'))
            ->post(route('orders.checkout'), [
                'service_type' => 'pasang_bengkel',
            ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHasErrors('booking_date');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('carts', 1);
    }

    public function test_purchase_checkout_does_not_store_booking_schedule(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Kelistrikan', 'slug' => 'kelistrikan']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'AKI-BELI',
            'name' => 'Aki tanpa pemasangan',
            'brand' => 'MotoPart',
            'price' => 100000,
            'stock' => 2,
        ]);
        Cart::create([
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('orders.checkout'), [
            'service_type' => 'beli_saja',
            'booking_date' => 'invalid-date',
            'booking_time' => '08:00 - 09:00',
        ]);

        $order = Order::firstOrFail();
        $response->assertRedirect(route('orders.show', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'booking_date' => null,
            'booking_time' => null,
            'service_fee' => 0,
        ]);
    }

    public function test_customer_cancels_pending_order_and_stock_is_restored(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Oli', 'slug' => 'oli']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'OLI-CANCEL',
            'name' => 'Oli untuk pembatalan',
            'brand' => 'MotoPart',
            'price' => 50000,
            'stock' => 3,
        ]);
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_number' => 'INV-CANCEL-001',
            'order_type' => 'beli_saja',
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 2,
            'unit_price' => 50000,
        ]);

        $response = $this->actingAs($user)
            ->from(route('orders.index'))
            ->patch(route('orders.cancel', $order));

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'batal',
        ]);
        $this->assertDatabaseHas('spareparts', [
            'id' => $sparepart->id,
            'stock' => 5,
        ]);

        $this->actingAs($user)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee('Dibatalkan')
            ->assertDontSee('Batalkan Pesanan');
    }

    public function test_checkout_is_rejected_when_booking_slot_quota_is_full(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Rem', 'slug' => 'rem-kuota']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'REM-QUOTA',
            'name' => 'Kampas Rem Kuota',
            'brand' => 'MotoPart',
            'price' => 45000,
            'stock' => 5,
        ]);
        $bookingDate = now()->addDays(2)->toDateString();

        foreach (range(1, Order::MAX_SLOT_QUOTA) as $number) {
            Order::create([
                'user_id' => $user->id,
                'invoice_number' => "INV-FULL-{$number}",
                'order_type' => 'pasang_bengkel',
                'booking_date' => $bookingDate,
                'booking_time' => Order::BOOKING_SLOTS[0],
                'status' => 'pending',
            ]);
        }

        Cart::create([
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
        ]);

        $this->actingAs($user)
            ->from(route('cart.index'))
            ->post(route('orders.checkout'), [
                'service_type' => 'pasang_bengkel',
                'booking_date' => $bookingDate,
                'booking_time' => Order::BOOKING_SLOTS[0],
            ])
            ->assertRedirect(route('cart.index'))
            ->assertSessionHasErrors('booking_time');

        $this->assertDatabaseCount('orders', Order::MAX_SLOT_QUOTA);
        $this->assertDatabaseHas('spareparts', [
            'id' => $sparepart->id,
            'stock' => 5,
        ]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
        ]);
    }

    public function test_cancelled_order_releases_booking_slot_quota(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Aki', 'slug' => 'aki-kuota']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'AKI-QUOTA',
            'name' => 'Aki Kuota',
            'brand' => 'MotoPart',
            'price' => 100000,
            'stock' => 5,
        ]);
        $bookingDate = now()->addDays(3)->toDateString();
        $orders = collect();

        foreach (range(1, Order::MAX_SLOT_QUOTA) as $number) {
            $orders->push(Order::create([
                'user_id' => $user->id,
                'invoice_number' => "INV-CANCELLED-{$number}",
                'order_type' => 'pasang_bengkel',
                'booking_date' => $bookingDate,
                'booking_time' => Order::BOOKING_SLOTS[1],
                'status' => 'pending',
            ]));
        }

        $orders->first()->update(['status' => 'batal']);
        Cart::create([
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
        ]);

        $this->actingAs($user)
            ->getJson(route('booking.slots', ['date' => $bookingDate]))
            ->assertOk()
            ->assertJsonPath('slots.1.booked', 1)
            ->assertJsonPath('slots.1.remaining', 1)
            ->assertJsonPath('slots.1.available', true);

        $response = $this->actingAs($user)->post(route('orders.checkout'), [
            'service_type' => 'pasang_bengkel',
            'booking_date' => $bookingDate,
            'booking_time' => Order::BOOKING_SLOTS[1],
        ]);

        $newOrder = Order::where('invoice_number', 'not like', 'INV-CANCELLED-%')->firstOrFail();
        $response->assertRedirect(route('orders.show', $newOrder));
        $this->assertDatabaseHas('orders', [
            'id' => $newOrder->id,
            'booking_time' => Order::BOOKING_SLOTS[1],
            'status' => 'pending',
        ]);
        $this->assertSame($bookingDate, $newOrder->fresh()->booking_date->toDateString());
    }
}
