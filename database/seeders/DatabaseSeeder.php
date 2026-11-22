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
    /** @var array<string, ?string> */
    private array $seededImages = [];

    public function run(): void
    {
        $this->seededImages = [];

        // 1. Categories (4 categories for balanced landing grid)
        $catOli = Category::updateOrCreate(['slug' => 'oli-pelumas'], ['name' => 'Oli & Pelumas']);
        $catRem = Category::updateOrCreate(['slug' => 'sistem-pengereman'], ['name' => 'Sistem Pengereman']);
        $catCvt = Category::updateOrCreate(['slug' => 'komponen-cvt'], ['name' => 'Komponen CVT']);
        $catListrik = Category::updateOrCreate(['slug' => 'kelistrikan-aki'], ['name' => 'Kelistrikan & Aki']);

        // 2. Motorcycles (8 popular Indonesian scooter models)
        $vario = Motorcycle::firstOrCreate(['brand' => 'Honda', 'model_name' => 'Honda Vario 150', 'production_year' => '2018-2022']);
        $beat = Motorcycle::firstOrCreate(['brand' => 'Honda', 'model_name' => 'Honda Beat FI / Street', 'production_year' => '2020-2024']);
        $pcx = Motorcycle::firstOrCreate(['brand' => 'Honda', 'model_name' => 'Honda PCX 160', 'production_year' => '2021-2024']);
        $nmax = Motorcycle::firstOrCreate(['brand' => 'Yamaha', 'model_name' => 'Yamaha NMAX 155', 'production_year' => '2020-2024']);
        $aerox = Motorcycle::firstOrCreate(['brand' => 'Yamaha', 'model_name' => 'Yamaha Aerox 155', 'production_year' => '2021-2024']);
        $scoopy = Motorcycle::firstOrCreate(['brand' => 'Honda', 'model_name' => 'Honda Scoopy Prestige', 'production_year' => '2021-2024']);
        $fazzio = Motorcycle::firstOrCreate(['brand' => 'Yamaha', 'model_name' => 'Yamaha Fazzio Hybrid 125', 'production_year' => '2022-2024']);
        $nex = Motorcycle::firstOrCreate(['brand' => 'Suzuki', 'model_name' => 'Suzuki Nex II 115', 'production_year' => '2019-2024']);

        // 3. Users (1 admin + 6 customers on @spareshop.web.id)
        $legacyEmails = [
            'admin@toko.com' => 'admin@spareshop.web.id',
            'budi@mail.com' => 'budi@spareshop.web.id',
            'siti@mail.com' => 'siti@spareshop.web.id',
            'andi@mail.com' => 'andi@spareshop.web.id',
            'rina@mail.com' => 'rina@spareshop.web.id',
            'dimas@mail.com' => 'dimas@spareshop.web.id',
            'maya@mail.com' => 'maya@spareshop.web.id',
        ];
        foreach ($legacyEmails as $oldEmail => $newEmail) {
            if (! User::where('email', $newEmail)->exists()) {
                User::where('email', $oldEmail)->update(['email' => $newEmail]);
            } else {
                User::where('email', $oldEmail)->delete();
            }
        }

        User::updateOrCreate(
            ['email' => 'admin@spareshop.web.id'],
            [
                'name' => 'Admin MotoPart',
                'password' => 'password',
                'phone' => '081234567890',
                'role' => 'admin',
            ]
        );

        $budi = User::updateOrCreate(
            ['email' => 'budi@spareshop.web.id'],
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
            ['email' => 'siti@spareshop.web.id'],
            [
                'name' => 'Siti Rahmawati',
                'password' => 'password',
                'phone' => '081355667788',
                'address' => 'Jl. Kemang Timur No. 22, Jakarta Selatan',
                'motorcycle_id' => $nmax->id,
                'role' => 'customer',
            ]
        );

        $andi = User::updateOrCreate(
            ['email' => 'andi@spareshop.web.id'],
            [
                'name' => 'Andi Pratama',
                'password' => 'password',
                'phone' => '081288990011',
                'address' => 'Jl. Margonda Raya No. 88, Depok',
                'motorcycle_id' => $aerox->id,
                'role' => 'customer',
            ]
        );

        $rina = User::updateOrCreate(
            ['email' => 'rina@spareshop.web.id'],
            [
                'name' => 'Rina Kusuma',
                'password' => 'password',
                'phone' => '085711223344',
                'address' => 'Jl. Buah Batu No. 45, Bandung',
                'motorcycle_id' => $scoopy->id,
                'role' => 'customer',
            ]
        );

        $dimas = User::updateOrCreate(
            ['email' => 'dimas@spareshop.web.id'],
            [
                'name' => 'Dimas Saputra',
                'password' => 'password',
                'phone' => '082144556677',
                'address' => 'Jl. Kaliurang Km 7, Sleman',
                'motorcycle_id' => $pcx->id,
                'role' => 'customer',
            ]
        );

        $maya = User::updateOrCreate(
            ['email' => 'maya@spareshop.web.id'],
            [
                'name' => 'Maya Putri',
                'password' => 'password',
                'phone' => '087899001122',
                'address' => 'Jl. Darmo Permai III No. 10, Surabaya',
                'motorcycle_id' => $fazzio->id,
                'role' => 'customer',
            ]
        );

        // 4. Spareparts & Pivot Sync (16 items: 4 per category, triggers Admin pagination)
        // Timestamp ordering keeps sp9..sp16 older and sp1..sp8 newest so landing page featured 6 stays distinct.
        $baseTime = now();
        $sparepartRows = [
            'sp9' => [
                'category_id' => $catOli->id,
                'part_code' => 'OLI-SHL-AX7M',
                'name' => 'Shell Advance AX7 Scooter 10W-40 0.8L',
                'brand' => 'Shell',
                'price' => 64000,
                'stock' => 22,
                'image' => $this->seedImage('oli-ahm-mpx2.png'),
                'motors' => [$vario->id, $beat->id, $scoopy->id, $nex->id],
                'ts' => $baseTime->copy()->subMinutes(20),
            ],
            'sp10' => [
                'category_id' => $catOli->id,
                'part_code' => 'OLI-MTL-5W40',
                'name' => 'Motul Scooter Power LE 5W-40 1L',
                'brand' => 'Motul',
                'price' => 115000,
                'stock' => 14,
                'image' => $this->seedImage('oli-yamalube-matic.png'),
                'motors' => [$pcx->id, $nmax->id, $aerox->id, $fazzio->id],
                'ts' => $baseTime->copy()->subMinutes(19),
            ],
            'sp11' => [
                'category_id' => $catRem->id,
                'part_code' => 'REM-ELG-SC01',
                'name' => 'Kampas Rem Cakram Ceramic Elig',
                'brand' => 'Elig',
                'price' => 89000,
                'stock' => 12,
                'image' => $this->seedImage('kampas-rem-nissin.png'),
                'motors' => [$pcx->id, $nmax->id, $aerox->id],
                'ts' => $baseTime->copy()->subMinutes(18),
            ],
            'sp12' => [
                'category_id' => $catRem->id,
                'part_code' => 'REM-YGP-54P',
                'name' => 'Kampas Rem Belakang Original YGP',
                'brand' => 'Yamaha Genuine Parts',
                'price' => 42000,
                'stock' => 2,
                'image' => $this->seedImage('kampas-rem-belakang-ahm.png'),
                'motors' => [$fazzio->id, $aerox->id],
                'ts' => $baseTime->copy()->subMinutes(17),
            ],
            'sp13' => [
                'category_id' => $catCvt->id,
                'part_code' => 'CVT-BRT-10G',
                'name' => 'Super Roller BRT 10gr Set',
                'brand' => 'BRT',
                'price' => 98000,
                'stock' => 0,
                'image' => $this->seedImage('roller-cvt-tdr.png'),
                'motors' => [$beat->id, $scoopy->id, $fazzio->id, $nex->id],
                'ts' => $baseTime->copy()->subMinutes(16),
            ],
            'sp14' => [
                'category_id' => $catCvt->id,
                'part_code' => 'CVT-YGP-B65',
                'name' => 'V-Belt Kit Original Yamaha B65',
                'brand' => 'Yamaha Genuine Parts',
                'price' => 165000,
                'stock' => 9,
                'image' => $this->seedImage('vbelt-kit-ahm.png'),
                'motors' => [$nmax->id, $aerox->id],
                'ts' => $baseTime->copy()->subMinutes(15),
            ],
            'sp15' => [
                'category_id' => $catListrik->id,
                'part_code' => 'AKI-MTZ-MTZ6S',
                'name' => 'Aki Motobatt Gel MTZ6S',
                'brand' => 'Motobatt',
                'price' => 335000,
                'stock' => 3,
                'image' => $this->seedImage('aki-gs-astra.png'),
                'motors' => [$vario->id, $pcx->id, $scoopy->id, $nmax->id],
                'ts' => $baseTime->copy()->subMinutes(14),
            ],
            'sp16' => [
                'category_id' => $catListrik->id,
                'part_code' => 'LIS-DNS-IU22',
                'name' => 'Busi Denso Iridium Power IU22',
                'brand' => 'Denso',
                'price' => 92000,
                'stock' => 16,
                'image' => $this->seedImage('busi-ngk-iridium.png'),
                'motors' => [$vario->id, $pcx->id, $nmax->id, $aerox->id, $nex->id],
                'ts' => $baseTime->copy()->subMinutes(13),
            ],
            'sp1' => [
                'category_id' => $catOli->id,
                'part_code' => 'OLI-AHM-MPX2',
                'name' => 'Oli AHM MPX2 0.8L',
                'brand' => 'AHM',
                'price' => 55000,
                'stock' => 25,
                'image' => $this->seedImage('oli-ahm-mpx2.png'),
                'motors' => [$vario->id, $beat->id, $pcx->id, $scoopy->id],
                'ts' => $baseTime->copy()->subMinutes(8),
            ],
            'sp2' => [
                'category_id' => $catOli->id,
                'part_code' => 'OLI-YML-SM1L',
                'name' => 'Yamalube Super Matic 1L',
                'brand' => 'Yamalube',
                'price' => 72000,
                'stock' => 20,
                'image' => $this->seedImage('oli-yamalube-matic.png'),
                'motors' => [$nmax->id, $aerox->id, $fazzio->id],
                'ts' => $baseTime->copy()->subMinutes(7),
            ],
            'sp3' => [
                'category_id' => $catRem->id,
                'part_code' => 'REM-NSN-DPN',
                'name' => 'Kampas Rem Depan Cakram Nissin',
                'brand' => 'Nissin',
                'price' => 45000,
                'stock' => 15,
                'image' => $this->seedImage('kampas-rem-nissin.png'),
                'motors' => [$vario->id, $beat->id, $pcx->id, $scoopy->id],
                'ts' => $baseTime->copy()->subMinutes(6),
            ],
            'sp4' => [
                'category_id' => $catRem->id,
                'part_code' => 'REM-AHM-BLK',
                'name' => 'Kampas Rem Belakang Tromol AHM',
                'brand' => 'AHM',
                'price' => 38000,
                'stock' => 18,
                'image' => $this->seedImage('kampas-rem-belakang-ahm.png'),
                'motors' => [$vario->id, $beat->id, $scoopy->id],
                'ts' => $baseTime->copy()->subMinutes(5),
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
                'ts' => $baseTime->copy()->subMinutes(4),
            ],
            'sp6' => [
                'category_id' => $catCvt->id,
                'part_code' => 'CVT-AHM-VBELT',
                'name' => 'V-Belt Kit AHM',
                'brand' => 'AHM',
                'price' => 145000,
                'stock' => 10,
                'image' => $this->seedImage('vbelt-kit-ahm.png'),
                'motors' => [$vario->id, $beat->id, $scoopy->id],
                'ts' => $baseTime->copy()->subMinutes(3),
            ],
            'sp7' => [
                'category_id' => $catListrik->id,
                'part_code' => 'AKI-GSA-GTZ6V',
                'name' => 'Aki GS Astra GTZ6V',
                'brand' => 'GS Astra',
                'price' => 285000,
                'stock' => 8,
                'image' => $this->seedImage('aki-gs-astra.png'),
                'motors' => [$vario->id, $pcx->id, $nmax->id, $aerox->id, $scoopy->id],
                'ts' => $baseTime->copy()->subMinutes(2),
            ],
            'sp8' => [
                'category_id' => $catListrik->id,
                'part_code' => 'LIS-NGK-CR7H',
                'name' => 'Busi NGK Iridium CR7HIX',
                'brand' => 'NGK',
                'price' => 95000,
                'stock' => 4,
                'image' => $this->seedImage('busi-ngk-iridium.png'),
                'motors' => [$vario->id, $beat->id, $pcx->id, $nmax->id, $aerox->id, $scoopy->id, $fazzio->id, $nex->id],
                'ts' => $baseTime->copy()->subMinute(),
            ],
        ];

        $parts = [];
        foreach ($sparepartRows as $key => $row) {
            $motors = $row['motors'];
            $partCode = $row['part_code'];
            $timestamp = $row['ts'];
            unset($row['motors'], $row['part_code'], $row['ts']);

            $part = Sparepart::updateOrCreate(['part_code' => $partCode], $row);
            $part->forceFill(['created_at' => $timestamp, 'updated_at' => $timestamp])->saveQuietly();
            $part->motorcycles()->sync($motors);
            $parts[$key] = $part;
        }

        // 5. Sample Orders (14 orders across all statuses; triggers Admin pagination & full H+1 slot 13:00-15:00)
        $orderDefs = [
            'doneBudi' => [
                'invoice' => 'INV-20261101-DONE',
                'data' => [
                    'user_id' => $budi->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->subDays(5)->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[0],
                    'service_fee' => 100000,
                    'customer_note' => 'Servis rutin CVT dan ganti oli.',
                    'status' => 'selesai',
                ],
                'items' => [$parts['sp1'], $parts['sp3'], $parts['sp5'], $parts['sp6']],
                'ts' => $baseTime->copy()->subDays(14),
            ],
            'doneAndi' => [
                'invoice' => 'INV-20261102-DONE',
                'data' => [
                    'user_id' => $andi->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->subDays(12)->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[1],
                    'service_fee' => 75000,
                    'customer_note' => 'Sekalian bersihkan area rumah roller Aerox.',
                    'status' => 'selesai',
                ],
                'items' => [$parts['sp2'], $parts['sp10'], $parts['sp14']],
                'ts' => $baseTime->copy()->subDays(13),
            ],
            'doneRina' => [
                'invoice' => 'INV-20261103-DONE',
                'data' => [
                    'user_id' => $rina->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->subDays(10)->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[0],
                    'service_fee' => 50000,
                    'customer_note' => 'Ganti oli rutin Scoopy.',
                    'status' => 'selesai',
                ],
                'items' => [$parts['sp1'], $parts['sp9']],
                'ts' => $baseTime->copy()->subDays(11),
            ],
            'doneSiti' => [
                'invoice' => 'INV-20261105-DONE',
                'data' => [
                    'user_id' => $siti->id,
                    'order_type' => 'beli_saja',
                    'booking_date' => null,
                    'booking_time' => null,
                    'service_fee' => 0,
                    'customer_note' => 'Packing rapi ya min.',
                    'status' => 'selesai',
                ],
                'items' => [$parts['sp2'], $parts['sp4'], $parts['sp7'], $parts['sp8']],
                'ts' => $baseTime->copy()->subDays(9),
            ],
            'doneDimas' => [
                'invoice' => 'INV-20261106-DONE',
                'data' => [
                    'user_id' => $dimas->id,
                    'order_type' => 'beli_saja',
                    'booking_date' => null,
                    'booking_time' => null,
                    'service_fee' => 0,
                    'customer_note' => 'Kirim via ekspedisi reguler.',
                    'status' => 'selesai',
                ],
                'items' => [$parts['sp3'], $parts['sp11'], $parts['sp16']],
                'ts' => $baseTime->copy()->subDays(8),
            ],
            'doneMaya' => [
                'invoice' => 'INV-20261108-DONE',
                'data' => [
                    'user_id' => $maya->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->subDays(4)->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[2],
                    'service_fee' => 50000,
                    'customer_note' => 'Pasang jam sore ya.',
                    'status' => 'selesai',
                ],
                'items' => [$parts['sp10'], $parts['sp16']],
                'ts' => $baseTime->copy()->subDays(6),
            ],
            'pendingBudi' => [
                'invoice' => 'INV-20261109-ABCD',
                'data' => [
                    'user_id' => $budi->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->addDays(2)->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[0],
                    'service_fee' => 50000,
                    'customer_note' => 'Tolong sekalian cek tekanan ban depan.',
                    'status' => 'pending',
                ],
                'items' => [$parts['sp1'], $parts['sp3']],
                'ts' => $baseTime->copy()->subDays(5),
            ],
            'batalSiti' => [
                'invoice' => 'INV-20261110-BTL1',
                'data' => [
                    'user_id' => $siti->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->addDays(3)->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[2],
                    'service_fee' => 25000,
                    'customer_note' => 'Batal karena ada jadwal mendadak.',
                    'status' => 'batal',
                ],
                'items' => [$parts['sp5']],
                'ts' => $baseTime->copy()->subDays(4),
            ],
            'batalAndi' => [
                'invoice' => 'INV-20261112-BTL2',
                'data' => [
                    'user_id' => $andi->id,
                    'order_type' => 'beli_saja',
                    'booking_date' => null,
                    'booking_time' => null,
                    'service_fee' => 0,
                    'customer_note' => 'Salah pilih tipe kampas rem.',
                    'status' => 'batal',
                ],
                'items' => [$parts['sp11']],
                'ts' => $baseTime->copy()->subDays(3),
            ],
            'diprosesSiti' => [
                'invoice' => 'INV-20261115-PRCS',
                'data' => [
                    'user_id' => $siti->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->addDay()->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[1],
                    'service_fee' => 50000,
                    'customer_note' => 'Motor NMAX warna hitam, mohon cek kelistrikan sekalian.',
                    'status' => 'diproses',
                ],
                'items' => [$parts['sp2'], $parts['sp7']],
                'ts' => $baseTime->copy()->subDays(2),
            ],
            'diprosesDimas' => [
                'invoice' => 'INV-20261116-PRCS',
                'data' => [
                    'user_id' => $dimas->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->addDay()->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[1],
                    'service_fee' => 50000,
                    'customer_note' => 'Datang tepat jam 13.00 WIB.',
                    'status' => 'diproses',
                ],
                'items' => [$parts['sp10'], $parts['sp11']],
                'ts' => $baseTime->copy()->subHours(36),
            ],
            'diprosesRina' => [
                'invoice' => 'INV-20261117-PRCS',
                'data' => [
                    'user_id' => $rina->id,
                    'order_type' => 'beli_saja',
                    'booking_date' => null,
                    'booking_time' => null,
                    'service_fee' => 0,
                    'customer_note' => 'Tolong sertakan stiker garansi.',
                    'status' => 'diproses',
                ],
                'items' => [$parts['sp9'], $parts['sp4']],
                'ts' => $baseTime->copy()->subHours(20),
            ],
            'pendingMaya' => [
                'invoice' => 'INV-20261119-PEND',
                'data' => [
                    'user_id' => $maya->id,
                    'order_type' => 'pasang_bengkel',
                    'booking_date' => now()->addDay()->toDateString(),
                    'booking_time' => Order::BOOKING_SLOTS[0],
                    'service_fee' => 25000,
                    'customer_note' => 'Ganti oli Fazzio sesi pagi.',
                    'status' => 'pending',
                ],
                'items' => [$parts['sp10']],
                'ts' => $baseTime->copy()->subHours(8),
            ],
            'pendingAndi' => [
                'invoice' => 'INV-20261120-PEND',
                'data' => [
                    'user_id' => $andi->id,
                    'order_type' => 'beli_saja',
                    'booking_date' => null,
                    'booking_time' => null,
                    'service_fee' => 0,
                    'customer_note' => 'Bubble wrap tebal untuk busi.',
                    'status' => 'pending',
                ],
                'items' => [$parts['sp14'], $parts['sp8']],
                'ts' => $baseTime->copy()->subHours(2),
            ],
        ];

        $orders = [];
        foreach ($orderDefs as $key => $def) {
            $order = Order::updateOrCreate(
                ['invoice_number' => $def['invoice']],
                $def['data']
            );
            $order->forceFill(['created_at' => $def['ts'], 'updated_at' => $def['ts']])->saveQuietly();
            $this->seedOrderItems($order, $def['items']);
            $orders[$key] = $order;
        }

        // 6. Verified Reviews (18 reviews tied to completed orders; sp12, sp13, sp15 remain at 0 reviews)
        $reviews = [
            [$orders['doneBudi'], $budi, $parts['sp1'], 5, 'Oli MPX2 asli segel AHM, tarikan mesin langsung halus.'],
            [$orders['doneBudi'], $budi, $parts['sp3'], 5, 'Kampas rem Nissin pakem dan tidak bunyi berdecit.'],
            [$orders['doneBudi'], $budi, $parts['sp5'], 4, 'Akselerasi bawah Vario jadi lebih responsif setelah pasang roller 12gr.'],
            [$orders['doneBudi'], $budi, $parts['sp6'], 5, 'V-Belt kit original lengkap dengan roller, mekanik pasang rapi tanpa antre.'],
            [$orders['doneSiti'], $siti, $parts['sp2'], 5, 'Yamalube Super Matic cocok banget untuk NMAX harian, pengiriman cepat.'],
            [$orders['doneSiti'], $siti, $parts['sp4'], 4, 'Kualitas kampas rem tromol bagus, pemasangan presisi.'],
            [$orders['doneSiti'], $siti, $parts['sp7'], 5, 'Aki GS Astra langsung tokcer saat starter, garansi toko jelas.'],
            [$orders['doneSiti'], $siti, $parts['sp8'], 5, 'Busi NGK Iridium bikin pembakaran lebih stabil dan irit BBM.'],
            [$orders['doneAndi'], $andi, $parts['sp2'], 5, 'Oli Yamalube selalu aman dipakai turing jauh dengan Aerox.'],
            [$orders['doneAndi'], $andi, $parts['sp10'], 5, 'Motul 5W-40 bikin suhu mesin jauh lebih adem di kemacetan.'],
            [$orders['doneAndi'], $andi, $parts['sp14'], 4, 'V-Belt B65 original Yamaha, getaran tarikan awal hilang.'],
            [$orders['doneRina'], $rina, $parts['sp1'], 4, 'Oli MPX2 harga bersahabat dan pemasangan di bengkel cepat.'],
            [$orders['doneRina'], $rina, $parts['sp9'], 5, 'Shell AX7 cocok di Scoopy, mesin terasa enteng.'],
            [$orders['doneDimas'], $dimas, $parts['sp3'], 5, 'Kampas cakram Nissin PNP di PCX tanpa ubahan.'],
            [$orders['doneDimas'], $dimas, $parts['sp11'], 5, 'Kampas ceramic Elig minim debu di pelek dan pakem saat basah.'],
            [$orders['doneDimas'], $dimas, $parts['sp16'], 4, 'Busi Denso Iridium langsung nyala sekali tekan tombol starter.'],
            [$orders['doneMaya'], $maya, $parts['sp10'], 4, 'Motul terasa halus di Fazzio, botol tersegel rapi.'],
            [$orders['doneMaya'], $maya, $parts['sp16'], 5, 'Pemasangan busi cepat dan rapi oleh mekanik bengkel.'],
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
        if (array_key_exists($filename, $this->seededImages)) {
            return $this->seededImages[$filename];
        }

        $sourcePath = database_path("seeders/images/{$filename}");
        if (! File::exists($sourcePath)) {
            return $this->seededImages[$filename] = null;
        }

        $targetPath = "spareparts/{$filename}";
        Storage::disk('public')->put($targetPath, File::get($sourcePath));

        return $this->seededImages[$filename] = $targetPath;
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
