<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\Order;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_receive_403_on_all_admin_routes(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Oli', 'slug' => 'oli']);
        $motorcycle = Motorcycle::create([
            'brand' => 'Honda',
            'model_name' => 'Vario 150',
            'production_year' => '2022',
        ]);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'OLI-001',
            'name' => 'Oli MPX',
            'brand' => 'AHM',
            'price' => 50000,
            'stock' => 10,
        ]);
        $order = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-20261118-TEST',
            'order_type' => 'beli_saja',
            'service_fee' => 0,
            'status' => 'pending',
        ]);

        $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();

        $this->actingAs($customer)->get(route('categories.index'))->assertForbidden();
        $this->actingAs($customer)->post(route('categories.store'), ['name' => 'Baru'])->assertForbidden();
        $this->actingAs($customer)->put(route('categories.update', $category), ['name' => 'Ubah'])->assertForbidden();
        $this->actingAs($customer)->delete(route('categories.destroy', $category))->assertForbidden();

        $this->actingAs($customer)->get(route('motorcycles.index'))->assertForbidden();
        $this->actingAs($customer)->post(route('motorcycles.store'), [
            'brand' => 'Yamaha',
            'model_name' => 'NMAX',
            'production_year' => '2024',
        ])->assertForbidden();
        $this->actingAs($customer)->put(route('motorcycles.update', $motorcycle), [
            'brand' => 'Yamaha',
            'model_name' => 'Aerox',
            'production_year' => '2024',
        ])->assertForbidden();
        $this->actingAs($customer)->delete(route('motorcycles.destroy', $motorcycle))->assertForbidden();

        $this->actingAs($customer)->get(route('spareparts.index'))->assertForbidden();
        $this->actingAs($customer)->get(route('spareparts.create'))->assertForbidden();
        $this->actingAs($customer)->post(route('spareparts.store'), [
            'category_id' => $category->id,
            'part_code' => 'OLI-002',
            'name' => 'Oli Baru',
            'brand' => 'AHM',
            'price' => 0,
            'stock' => 5,
        ])->assertForbidden();
        $this->actingAs($customer)->get(route('spareparts.edit', $sparepart))->assertForbidden();
        $this->actingAs($customer)->put(route('spareparts.update', $sparepart), [
            'category_id' => $category->id,
            'part_code' => 'OLI-001',
            'name' => 'Oli Ubah',
            'brand' => 'AHM',
            'price' => 1,
            'stock' => 5,
        ])->assertForbidden();
        $this->actingAs($customer)->delete(route('spareparts.destroy', $sparepart))->assertForbidden();

        $this->actingAs($customer)->get(route('admin.orders.index'))->assertForbidden();
        $this->actingAs($customer)->patch(route('admin.orders.updateStatus', $order), [
            'status' => 'selesai',
        ])->assertForbidden();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'pending']);
        $this->assertDatabaseHas('spareparts', ['id' => $sparepart->id, 'price' => 50000]);
    }

    public function test_admins_can_access_protected_admin_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($admin)->get(route('categories.index'))->assertOk();
        $this->actingAs($admin)->get(route('motorcycles.index'))->assertOk();
        $this->actingAs($admin)->get(route('spareparts.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.orders.index'))->assertOk();
    }
}
