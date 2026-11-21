<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sparepart;
use App\Models\SparepartReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories (4 categories for balanced landing grid)
        $catOli = Category::updateOrCreate(['slug' => 'oli-pelumas'], ['name' => 'Oli & Pelumas']);
        $catRem = Category::updateOrCreate(['slug' => 'sistem-pengereman'], ['name' => 'Sistem Pengereman']);
        $catCvt = Category::updateOrCreate(['slug' => 'komponen-cvt'], ['name' => 'Komponen CVT']);
        $catListrik = Category::updateOrCreate(['slug' => 'kelistrikan-aki'], ['name' => 'Kelistrikan & Aki']);

        // 2. Motorcycles
        $vario = Motorcycle::firstOrCreate(['brand' => 'Honda', 'model_name' => 'Honda Vario 150', 'production_year' => '2018-2022']);
        $beat = Motorcycle::firstOrCreate(['brand' => 'Honda', 'model_name' => 'Honda Beat FI / Street', 'production_year' => '2020-2024']);
        $pcx = Motorcycle::firstOrCreate(['brand' => 'Honda', 'model_name' => 'Honda PCX 160', 'production_year' => '2021-2024']);
        $nmax = Motorcycle::firstOrCreate(['brand' => 'Yamaha', 'model_name' => 'Yamaha NMAX 155', 'production_year' => '2020-2024']);
        $aerox = Motorcycle::firstOrCreate(['brand' => 'Yamaha', 'model_name' => 'Yamaha Aerox 155', 'production_year' => '2021-2024']);

        // 3. Users
        User::updateOrCreate(
            ['email' => 'admin@toko.com'],
            [
                'name' => 'Admin MotoPart',
                'password' => 'password',
                'phone' => '081234567890',
                'role' => 'admin',
            ]
        );

        $budi = User::updateOrCreate(
            ['email' => 'budi@mail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => 'password',
                'phone' => '089876543210',
                'address' => 'Jl. Kebon Jeruk Raya No. 14, Jakarta Barat',
                'motorcycle_id' => $vario->id,
                'role' => 'customer',
            ]
        );

        $siti = User::updateOrCreate(
            ['email' => 'siti@mail.com'],
            [
                'name' => 'Siti Rahmawati',
                'password' => 'password',
                'phone' => '081355667788',
                'address' => 'Jl. Kemang Timur No. 22, Jakarta Selatan',
                'motorcycle_id' => $nmax->id,
                'role' => 'customer',
            ]
        );

        // 4. Spareparts & Pivot Sync
        $sparepartRows = [
            'sp1' => [
                'category_id' => $catOli->id,
                'part_code' => 'OLI-AHM-MPX2',
                'name' => 'Oli AHM MPX2 0.8L',
                'brand' => 'AHM',
                'price' => 55000,
                'stock' => 25,
                'image' => $this->seedImage('oli-ahm-mpx2.png'),
                'motors' => [$vario->id, $beat->id, $pcx->id],
            ],
            'sp2' => [
                'category_id' => $catOli->id,
                'part_code' => 'OLI-YML-SM1L',
                'name' => 'Yamalube Super Matic 1L',
                'brand' => 'Yamalube',
                'price' => 72000,
                'stock' => 20,
                'image' => $this->seedImage('oli-yamalube-matic.png'),
                'motors' => [$nmax->id, $aerox->id],
            ],
            'sp3' => [
                'category_id' => $catRem->id,
                'part_code' => 'REM-NSN-DPN',
                'name' => 'Kampas Rem Depan Cakram Nissin',
                'brand' => 'Nissin',
                'price' => 45000,
                'stock' => 15,
                'image' => $this->seedImage('kampas-rem-nissin.png'),
                'motors' => [$vario->id, $beat->id, $pcx->id],
            ],
            'sp4' => [
                'category_id' => $catRem->id,
                'part_code' => 'REM-AHM-BLK',
                'name' => 'Kampas Rem Belakang Tromol AHM',
                'brand' => 'AHM',
                'price' => 38000,
                'stock' => 18,
                'image' => $this->seedImage('kampas-rem-belakang-ahm.png'),
                'motors' => [$vario->id, $beat->id],
            ],
            'sp5' => [
                'category_id' => $catCvt->id,
                'part_code' => 'CVT-TDR-12G',
                'name' => 'Roller CVT TDR 12gr',
                'brand' => 'TDR Racing',
                'price' => 85000,
                'stock' => 3,
                'image' => $this->seedImage('roller-cvt-tdr.png'),
                'motors' => [$vario->id, $pcx->id, $nmax->id, $aerox->id],
            ],
            'sp6' => [
                'category_id' => $catCvt->id,
                'part_code' => 'CVT-AHM-VBELT',
                'name' => 'V-Belt Kit AHM',
                'brand' => 'AHM',
                'price' => 145000,
                'stock' => 10,
                'image' => $this->seedImage('vbelt-kit-ahm.png'),
                'motors' => [$vario->id, $beat->id],
            ],
            'sp7' => [
                'category_id' => $catListrik->id,
                'part_code' => 'AKI-GSA-GTZ6V',
                'name' => 'Aki GS Astra GTZ6V',
                'brand' => 'GS Astra',
                'price' => 285000,
                'stock' => 8,
                'image' => $this->seedImage('aki-gs-astra.png'),
                'motors' => [$vario->id, $pcx->id, $nmax->id, $aerox->id],
            ],
            'sp8' => [
                'category_id' => $catListrik->id,
                'part_code' => 'LIS-NGK-CR7H',
                'name' => 'Busi NGK Iridium CR7HIX',
                'brand' => 'NGK',
                'price' => 95000,
                'stock' => 4,
                'image' => $this->seedImage('busi-ngk-iridium.png'),
                'motors' => [$vario->id, $beat->id, $pcx->id, $nmax->id, $aerox->id],
            ],
        ];

        $parts = [];
        foreach ($sparepartRows as $key => $row) {
            $motors = $row['motors'];
            $partCode = $row['part_code'];
            unset($row['motors'], $row['part_code']);

            $part = Sparepart::updateOrCreate(['part_code' => $partCode], $row);
            $part->motorcycles()->sync($motors);
            $parts[$key] = $part;
        }

        // 5. Sample Orders covering all statuses
        $orderPending = Order::updateOrCreate(
            ['invoice_number' => 'INV-20261109-ABCD'],
            [
                'user_id' => $budi->id,
                'order_type' => 'pasang_bengkel',
                'booking_date' => now()->addDays(2)->toDateString(),
                'booking_time' => Order::BOOKING_SLOTS[0],
                'service_fee' => 50000,
                'customer_note' => 'Tolong sekalian cek tekanan ban depan.',
                'status' => 'pending',
            ]
        );
        $this->seedOrderItems($orderPending, [$parts['sp1'], $parts['sp3']]);

        $orderDiproses = Order::updateOrCreate(
            ['invoice_number' => 'INV-20261115-PRCS'],
            [
                'user_id' => $siti->id,
                'order_type' => 'pasang_bengkel',
                'booking_date' => now()->addDay()->toDateString(),
                'booking_time' => Order::BOOKING_SLOTS[1],
                'service_fee' => 50000,
                'customer_note' => 'Motor NMAX warna hitam, mohon cek kelistrikan sekalian.',
                'status' => 'diproses',
            ]
        );
        $this->seedOrderItems($orderDiproses, [$parts['sp2'], $parts['sp7']]);

        $orderDoneBudi = Order::updateOrCreate(
            ['invoice_number' => 'INV-20261101-DONE'],
            [
                'user_id' => $budi->id,
                'order_type' => 'pasang_bengkel',
                'booking_date' => now()->subDays(5)->toDateString(),
                'booking_time' => Order::BOOKING_SLOTS[0],
                'service_fee' => 100000,
                'customer_note' => 'Servis rutin CVT dan ganti oli.',
                'status' => 'selesai',
            ]
        );
        $this->seedOrderItems($orderDoneBudi, [$parts['sp1'], $parts['sp3'], $parts['sp5'], $parts['sp6']]);

        $orderDoneSiti = Order::updateOrCreate(
            ['invoice_number' => 'INV-20261105-DONE'],
            [
                'user_id' => $siti->id,
                'order_type' => 'beli_saja',
                'booking_date' => null,
                'booking_time' => null,
                'service_fee' => 0,
                'customer_note' => 'Packing rapi ya min.',
                'status' => 'selesai',
            ]
        );
        $this->seedOrderItems($orderDoneSiti, [$parts['sp2'], $parts['sp4'], $parts['sp7'], $parts['sp8']]);

        $orderBatal = Order::updateOrCreate(
            ['invoice_number' => 'INV-20261110-BTL1'],
            [
                'user_id' => $siti->id,
                'order_type' => 'pasang_bengkel',
                'booking_date' => now()->addDays(3)->toDateString(),
                'booking_time' => Order::BOOKING_SLOTS[2],
                'service_fee' => 25000,
                'customer_note' => 'Batal karena ada jadwal mendadak.',
                'status' => 'batal',
            ]
        );
        $this->seedOrderItems($orderBatal, [$parts['sp5']]);

        // 6. Verified Reviews for all 8 products from completed orders
        $reviews = [
            [$orderDoneBudi, $budi, $parts['sp1'], 5, 'Oli MPX2 asli segel AHM, tarikan mesin langsung halus.'],
            [$orderDoneBudi, $budi, $parts['sp3'], 5, 'Kampas rem Nissin pakem dan tidak bunyi berdecit.'],
            [$orderDoneBudi, $budi, $parts['sp5'], 4, 'Akselerasi bawah Vario jadi lebih responsif setelah pasang roller 12gr.'],
            [$orderDoneBudi, $budi, $parts['sp6'], 5, 'V-Belt kit original lengkap dengan roller, mekanik pasang rapi tanpa antre.'],
            [$orderDoneSiti, $siti, $parts['sp2'], 5, 'Yamalube Super Matic cocok banget untuk NMAX harian, pengiriman cepat.'],
            [$orderDoneSiti, $siti, $parts['sp4'], 4, 'Kualitas kampas rem tromol bagus, pemasangan presisi.'],
            [$orderDoneSiti, $siti, $parts['sp7'], 5, 'Aki GS Astra langsung tokcer saat starter, garansi toko jelas.'],
            [$orderDoneSiti, $siti, $parts['sp8'], 5, 'Busi NGK Iridium bikin pembakaran lebih stabil dan irit BBM.'],
        ];

        foreach ($reviews as [$order, $user, $sparepart, $rating, $comment]) {
            SparepartReview::updateOrCreate(
                [
                    'order_id' => $order->id,
                    'sparepart_id' => $sparepart->id,
                ],
                [
                    'user_id' => $user->id,
                    'rating' => $rating,
                    'comment' => $comment,
                ]
            );
        }

        cache()->forget('footer_categories');
    }

    private function seedImage(string $filename): ?string
    {
        $sourcePath = database_path("seeders/images/{$filename}");
        if (! File::exists($sourcePath)) {
            return null;
        }

        $targetPath = "spareparts/{$filename}";
        Storage::disk('public')->put($targetPath, File::get($sourcePath));

        return $targetPath;
    }

    /**
     * @param  array<int, Sparepart>  $spareparts
     */
    private function seedOrderItems(Order $order, array $spareparts): void
    {
        foreach ($spareparts as $sparepart) {
            OrderItem::updateOrCreate(
                [
                    'order_id' => $order->id,
                    'sparepart_id' => $sparepart->id,
                ],
                [
                    'qty' => 1,
                    'unit_price' => $sparepart->price,
                ]
            );
        }
    }
}
