<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sparepart;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        User::create([
            'name' => 'Admin MotoPart',
            'email' => 'admin@toko.com',
            'password' => 'password',
            'phone' => '081234567890',
            'role' => 'admin',
        ]);

        $customer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@mail.com',
            'password' => 'password',
            'phone' => '089876543210',
            'role' => 'customer',
        ]);

        // 2. Categories
        $catOli = Category::create(['name' => 'Oli & Pelumas', 'slug' => 'oli-pelumas']);
        $catRem = Category::create(['name' => 'Sistem Pengereman', 'slug' => 'sistem-pengereman']);
        $catCvt = Category::create(['name' => 'Komponen CVT', 'slug' => 'komponen-cvt']);
        $catListrik = Category::create(['name' => 'Kelistrikan & Aki', 'slug' => 'kelistrikan-aki']);

        // 3. Motorcycles
        $vario = Motorcycle::create(['brand' => 'Honda', 'model_name' => 'Honda Vario 150', 'production_year' => '2018-2022']);
        $beat = Motorcycle::create(['brand' => 'Honda', 'model_name' => 'Honda Beat FI / Street', 'production_year' => '2020-2024']);
        $pcx = Motorcycle::create(['brand' => 'Honda', 'model_name' => 'Honda PCX 160', 'production_year' => '2021-2024']);
        $nmax = Motorcycle::create(['brand' => 'Yamaha', 'model_name' => 'Yamaha NMAX 155', 'production_year' => '2020-2024']);
        $aerox = Motorcycle::create(['brand' => 'Yamaha', 'model_name' => 'Yamaha Aerox 155', 'production_year' => '2021-2024']);

        // 4. Spareparts & Pivot Sync
        $sp1 = Sparepart::create([
            'category_id' => $catOli->id,
            'part_code' => 'OLI-AHM-MPX2',
            'name' => 'Oli AHM MPX2 0.8L',
            'brand' => 'AHM',
            'price' => 55000,
            'stock' => 25,
            'image' => null,
        ]);
        $sp1->motorcycles()->attach([$vario->id, $beat->id, $pcx->id]);

        $sp2 = Sparepart::create([
            'category_id' => $catRem->id,
            'part_code' => 'REM-NSN-DPN',
            'name' => 'Kampas Rem Depan Cakram Nissin',
            'brand' => 'Nissin',
            'price' => 45000,
            'stock' => 15,
            'image' => null,
        ]);
        $sp2->motorcycles()->attach([$vario->id, $beat->id, $pcx->id]);

        $sp3 = Sparepart::create([
            'category_id' => $catCvt->id,
            'part_code' => 'CVT-TDR-12G',
            'name' => 'Roller CVT TDR 12gr',
            'brand' => 'TDR Racing',
            'price' => 85000,
            'stock' => 3, // Low-stock alert (< 5)
            'image' => null,
        ]);
        $sp3->motorcycles()->attach([$vario->id, $pcx->id, $nmax->id, $aerox->id]);

        $sp4 = Sparepart::create([
            'category_id' => $catCvt->id,
            'part_code' => 'CVT-AHM-VBELT',
            'name' => 'V-Belt Kit AHM',
            'brand' => 'AHM',
            'price' => 145000,
            'stock' => 10,
            'image' => null,
        ]);
        $sp4->motorcycles()->attach([$vario->id, $beat->id]);

        $sp5 = Sparepart::create([
            'category_id' => $catListrik->id,
            'part_code' => 'AKI-GSA-GTZ6V',
            'name' => 'Aki GS Astra GTZ6V',
            'brand' => 'GS Astra',
            'price' => 285000,
            'stock' => 8,
            'image' => null,
        ]);
        $sp5->motorcycles()->attach([$vario->id, $pcx->id, $nmax->id, $aerox->id]);

        $sp6 = Sparepart::create([
            'category_id' => $catOli->id,
            'part_code' => 'OLI-YML-SM1L',
            'name' => 'Yamalube Super Matic 1L',
            'brand' => 'Yamalube',
            'price' => 72000,
            'stock' => 20,
            'image' => null,
        ]);
        $sp6->motorcycles()->attach([$nmax->id, $aerox->id]);

        // 5. Sample Order
        $order = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-20261109-001',
            'order_type' => 'pasang_bengkel',
            'booking_date' => now()->addDays(2)->toDateString(),
            'service_fee' => 25000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'sparepart_id' => $sp1->id,
            'qty' => 1,
            'unit_price' => $sp1->price,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'sparepart_id' => $sp2->id,
            'qty' => 1,
            'unit_price' => $sp2->price,
        ]);
    }
}
