<?php

namespace Tests\Feature;

use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerProfileAndCancellationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_update_profile_and_primary_motor_session(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'phone' => null,
        ]);
        $motorcycle = Motorcycle::create([
            'brand' => 'Honda',
            'model_name' => 'Vario Test',
            'production_year' => '2024',
        ]);

        $response = $this->actingAs($user)
            ->withSession(['motor_filter_cleared' => true])
            ->put(route('profile.update'), [
                'name' => 'Nama Baru',
                'phone' => '081234567890',
                'address' => 'Jl. Contoh No. 1',
                'motorcycle_id' => $motorcycle->id,
            ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh No. 1',
            'motorcycle_id' => $motorcycle->id,
        ]);
        $response->assertSessionHas('selected_motorcycle_id', $motorcycle->id);
        $response->assertSessionMissing('motor_filter_cleared');

        $this->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Nama Baru')
            ->assertSee('Vario Test');
    }
}
