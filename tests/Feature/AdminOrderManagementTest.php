<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cancelling_order_restores_stock_and_releases_booking_slot(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Oli', 'slug' => 'oli']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'OLI-ADM-01',
            'name' => 'Oli Admin Cancel',
            'brand' => 'AHM',
            'price' => 55000,
            'stock' => 3,
        ]);

        $bookingDate = now()->addDays(2)->toDateString();
        $order = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-ADM-CANCEL-01',
            'order_type' => 'pasang_bengkel',
            'booking_date' => $bookingDate,
            'booking_time' => Order::BOOKING_SLOTS[0],
            'service_fee' => 50000,
            'customer_note' => 'Datang tepat jam 9 pagi',
            'status' => 'diproses',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 2,
            'unit_price' => 55000,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $order), ['status' => 'batal'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'batal',
        ]);
        $this->assertDatabaseHas('spareparts', [
            'id' => $sparepart->id,
            'stock' => 5,
        ]);

        $this->actingAs($customer)
            ->getJson(route('booking.slots', ['date' => $bookingDate]))
            ->assertOk()
            ->assertJsonPath('slots.0.booked', 0)
            ->assertJsonPath('slots.0.remaining', Order::MAX_SLOT_QUOTA);
    }

    public function test_completed_or_cancelled_orders_are_locked_from_further_status_changes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Rem', 'slug' => 'rem']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'REM-LOCK-01',
            'name' => 'Kampas Rem Lock',
            'brand' => 'Nissin',
            'price' => 45000,
            'stock' => 5,
        ]);

        $cancelledOrder = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-LOCKED-BATAL',
            'order_type' => 'beli_saja',
            'status' => 'batal',
        ]);
        OrderItem::create([
            'order_id' => $cancelledOrder->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 2,
            'unit_price' => 45000,
        ]);

        $completedOrder = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-LOCKED-SELESAI',
            'order_type' => 'beli_saja',
            'status' => 'selesai',
        ]);
        OrderItem::create([
            'order_id' => $completedOrder->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
            'unit_price' => 45000,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $cancelledOrder), ['status' => 'batal'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('error');

        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $cancelledOrder), ['status' => 'diproses'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('error');

        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $completedOrder), ['status' => 'batal'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('orders', ['id' => $cancelledOrder->id, 'status' => 'batal']);
        $this->assertDatabaseHas('orders', ['id' => $completedOrder->id, 'status' => 'selesai']);
        $this->assertDatabaseHas('spareparts', ['id' => $sparepart->id, 'stock' => 5]);
    }

    public function test_admin_orders_modal_displays_booking_time_customer_note_and_locked_state(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create([
            'role' => 'customer',
            'phone' => '081122334455',
            'address' => 'Jl. Merdeka No. 10',
        ]);

        Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-MODAL-VIEW',
            'order_type' => 'pasang_bengkel',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => Order::BOOKING_SLOTS[1],
            'service_fee' => 25000,
            'customer_note' => 'Catatan khusus dari pembeli',
            'status' => 'selesai',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('INV-MODAL-VIEW')
            ->assertSee(Order::BOOKING_SLOTS[1])
            ->assertSee('Catatan khusus dari pembeli')
            ->assertSee('Status pesanan sudah final')
            ->assertDontSee('Ubah Status Pesanan');
    }

    public function test_unchanged_status_reports_no_change_and_orders_index_renders_accessible_labels_and_empty_states(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);

        // Empty table state + empty query params should not show Reset button
        $this->actingAs($admin)
            ->get(route('admin.orders.index', ['search' => '', 'status' => '', 'order_type' => '']))
            ->assertOk()
            ->assertSee('Belum ada pesanan masuk')
            ->assertDontSee('>Reset</a>', false)
            ->assertSee('for="filter-order-search"', false)
            ->assertSee('for="filter-order-status"', false)
            ->assertSee('for="filter-order-type"', false);

        $order = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-ADM-UNCHANGED',
            'order_type' => 'beli_saja',
            'service_fee' => 0,
            'status' => 'pending',
        ]);

        // Filtered empty state
        $this->actingAs($admin)
            ->get(route('admin.orders.index', ['status' => 'selesai']))
            ->assertOk()
            ->assertSee('Tidak ada pesanan yang cocok')
            ->assertSee('Reset Filter');

        // Unchanged status submission gives honest feedback instead of claiming update succeeded
        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $order), ['status' => 'pending'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('error');

        // Modal accessibility bindings on active order
        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk()
            ->assertSee('for="order-status-' . $order->id . '"', false)
            ->assertSee('aria-labelledby="orderDetailLabel' . $order->id . '"', false)
            ->assertSee('aria-label="Lihat detail pesanan INV-ADM-UNCHANGED"', false);
    }
}
