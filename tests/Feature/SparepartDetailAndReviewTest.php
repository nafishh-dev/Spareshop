<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Motorcycle;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sparepart;
use App\Models\SparepartReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SparepartDetailAndReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_sparepart_detail_compatibility_and_reviews(): void
    {
        $category = Category::create(['name' => 'Oli', 'slug' => 'oli']);
        $motorcycle = Motorcycle::create([
            'brand' => 'Honda',
            'model_name' => 'Vario Review Test',
            'production_year' => '2024',
        ]);
        $sparepart = $this->createSparepart($category);
        $sparepart->motorcycles()->attach($motorcycle);
        $reviewer = User::factory()->create(['name' => 'Pembeli Terverifikasi']);
        $order = $this->createOrder($reviewer, 'INV-REVIEW-GUEST-001', 'selesai');
        SparepartReview::create([
            'user_id' => $reviewer->id,
            'sparepart_id' => $sparepart->id,
            'order_id' => $order->id,
            'rating' => 5,
            'comment' => 'Produk sesuai dan berkualitas.',
        ]);

        $this->get(route('catalog.show', $sparepart))
            ->assertOk()
            ->assertSee('Vario Review Test')
            ->assertSee('Pembeli Terverifikasi')
            ->assertSee('Produk sesuai dan berkualitas.')
            ->assertSee('Pembeli Terverifikasi', false)
            ->assertSee('5.0')
            ->assertDontSee('Tulis Ulasan');
    }

    public function test_completed_order_review_button_and_page_are_available_to_its_owner(): void
    {
        $category = Category::create(['name' => 'Rem', 'slug' => 'rem']);
        $sparepart = $this->createSparepart($category);
        $user = User::factory()->create();
        $order = $this->createOrder($user, 'INV-REVIEW-ACCESS-001', 'selesai');
        $this->addItem($order, $sparepart);

        $this->actingAs($user)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Ulas')
            ->assertSee(route('orders.review', $order));

        $this->get(route('orders.review', $order))
            ->assertOk()
            ->assertSee($order->invoice_number)
            ->assertSee($sparepart->name)
            ->assertSee('Kirim Ulasan');

        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)
            ->get(route('orders.review', $order))
            ->assertForbidden();
    }

    public function test_customer_without_completed_order_is_forbidden_from_reviewing(): void
    {
        $category = Category::create(['name' => 'Filter', 'slug' => 'filter']);
        $sparepart = $this->createSparepart($category);
        $user = User::factory()->create();
        $order = $this->createOrder($user, 'INV-REVIEW-PENDING-001', 'pending');
        $this->addItem($order, $sparepart);

        $this->actingAs($user)
            ->post(route('orders.items.review', [$order, $sparepart]), [
                'rating' => 4,
                'comment' => 'Pesanan belum selesai.',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('sparepart_reviews', 0);
    }

    public function test_customer_can_review_order_item_once_and_review_appears_on_sparepart_detail(): void
    {
        $category = Category::create(['name' => 'CVT', 'slug' => 'cvt']);
        $sparepart = $this->createSparepart($category);
        $user = User::factory()->create();
        $order = $this->createOrder($user, 'INV-REVIEW-CUSTOMER-001', 'selesai');
        $this->addItem($order, $sparepart);

        $response = $this->actingAs($user)
            ->post(route('orders.items.review', [$order, $sparepart]), [
                'rating' => 4,
                'comment' => 'Sudah terpasang dan berfungsi baik.',
            ]);

        $response->assertRedirect(route('orders.review', $order));
        $this->assertDatabaseHas('sparepart_reviews', [
            'user_id' => $user->id,
            'sparepart_id' => $sparepart->id,
            'order_id' => $order->id,
            'rating' => 4,
            'comment' => 'Sudah terpasang dan berfungsi baik.',
        ]);

        $this->get(route('catalog.show', $sparepart))
            ->assertOk()
            ->assertSee('Sudah terpasang dan berfungsi baik.')
            ->assertSee('Pembeli Terverifikasi');

        $this->get(route('orders.index'))
            ->assertOk()
            ->assertSee('Diulas');

        $this->post(route('orders.items.review', [$order, $sparepart]), [
            'rating' => 5,
            'comment' => 'Mencoba mengubah ulasan.',
        ])->assertForbidden();

        $this->assertDatabaseCount('sparepart_reviews', 1);
    }

    public function test_same_sparepart_can_be_reviewed_separately_for_two_completed_orders(): void
    {
        $category = Category::create(['name' => 'Kelistrikan', 'slug' => 'kelistrikan']);
        $sparepart = $this->createSparepart($category);
        $user = User::factory()->create();
        $firstOrder = $this->createOrder($user, 'INV-REVIEW-ORDER-001', 'selesai');
        $secondOrder = $this->createOrder($user, 'INV-REVIEW-ORDER-002', 'selesai');
        $this->addItem($firstOrder, $sparepart);
        $this->addItem($secondOrder, $sparepart);

        $this->actingAs($user)
            ->post(route('orders.items.review', [$firstOrder, $sparepart]), [
                'rating' => 4,
                'comment' => 'Ulasan pesanan pertama.',
            ])
            ->assertRedirect(route('orders.review', $firstOrder));
        $this->post(route('orders.items.review', [$secondOrder, $sparepart]), [
            'rating' => 5,
            'comment' => 'Ulasan pesanan kedua.',
        ])->assertRedirect(route('orders.review', $secondOrder));

        $this->assertDatabaseCount('sparepart_reviews', 2);
        $this->assertDatabaseHas('sparepart_reviews', [
            'order_id' => $firstOrder->id,
            'sparepart_id' => $sparepart->id,
            'comment' => 'Ulasan pesanan pertama.',
        ]);
        $this->assertDatabaseHas('sparepart_reviews', [
            'order_id' => $secondOrder->id,
            'sparepart_id' => $sparepart->id,
            'comment' => 'Ulasan pesanan kedua.',
        ]);
    }

    private function createOrder(User $user, string $invoiceNumber, string $status): Order
    {
        return Order::create([
            'user_id' => $user->id,
            'invoice_number' => $invoiceNumber,
            'order_type' => 'beli_saja',
            'status' => $status,
        ]);
    }

    private function addItem(Order $order, Sparepart $sparepart): void
    {
        OrderItem::create([
            'order_id' => $order->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
            'unit_price' => $sparepart->price,
        ]);
    }

    private function createSparepart(Category $category): Sparepart
    {
        return Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'PART-'.$category->slug,
            'name' => 'Sparepart Ulasan '.$category->name,
            'brand' => 'MotoPart',
            'price' => 75000,
            'stock' => 5,
        ]);
    }
}
