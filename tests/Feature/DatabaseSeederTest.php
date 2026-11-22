<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\Order;
use App\Models\Sparepart;
use App\Models\SparepartReview;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_is_idempotent_and_populates_product_images_and_reviews(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(4, Category::count());
        $this->assertSame(8, Motorcycle::count());
        $this->assertSame(7, User::count());
        $this->assertSame(7, User::where('email', 'like', '%@spareshop.web.id')->count());
        $this->assertSame(16, Sparepart::count());
        $this->assertSame(14, Order::count());
        $this->assertSame(18, SparepartReview::count());

        $this->assertSame(1, Sparepart::where('stock', 0)->count());
        $this->assertSame(5, Sparepart::where('stock', '<', 5)->count());

        $fullSlotBooked = Order::where('order_type', 'pasang_bengkel')
            ->whereDate('booking_date', now()->addDay()->toDateString())
            ->where('booking_time', Order::BOOKING_SLOTS[1])
            ->where('status', '!=', 'batal')
            ->count();
        $this->assertSame(Order::MAX_SLOT_QUOTA, $fullSlotBooked);

        $spareparts = Sparepart::all();
        foreach ($spareparts as $part) {
            $this->assertNotNull($part->image);
            Storage::disk('public')->assertExists($part->image);
        }

        $featuredParts = Sparepart::latest()->take(6)->get();
        $this->assertSame(6, $featuredParts->pluck('image')->unique()->count());

        $response = $this->get('/');
        $response->assertOk();

        foreach ($featuredParts as $part) {
            $response->assertSee($part->name);
            $response->assertSee('storage/'.$part->image);
        }
    }
}
