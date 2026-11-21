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
        $this->assertSame(5, Motorcycle::count());
        $this->assertSame(3, User::count());
        $this->assertSame(8, Sparepart::count());
        $this->assertSame(5, Order::count());
        $this->assertSame(8, SparepartReview::count());

        $spareparts = Sparepart::all();
        foreach ($spareparts as $part) {
            $this->assertNotNull($part->image);
            Storage::disk('public')->assertExists($part->image);
        }

        $featuredParts = Sparepart::latest()->take(6)->get();
        $response = $this->get('/');
        $response->assertOk();

        foreach ($featuredParts as $part) {
            $response->assertSee($part->name);
            $response->assertSee('storage/'.$part->image);
        }
    }
}
