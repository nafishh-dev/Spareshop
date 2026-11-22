<?php

namespace Tests\Feature;

use App\Mail\OrderStatusUpdatedMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sparepart;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Symfony\Component\Mailer\Bridge\Mailgun\Transport\MailgunHttpTransport;
use Tests\TestCase;

class MailNotificationAndPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_mailgun_http_api_mailer_is_configured_and_resolves_api_transport(): void
    {
        $this->assertSame('mailgun', config('mail.mailers.mailgun.transport'));
        $this->assertSame('https', config('services.mailgun.scheme'));

        config()->set('services.mailgun.domain', 'mg.spareshop.web.id');
        config()->set('services.mailgun.secret', 'test-mailgun-secret-key');
        config()->set('services.mailgun.endpoint', 'api.mailgun.net');

        Mail::purge('mailgun');
        $mailer = Mail::mailer('mailgun');
        $transport = $mailer->getSymfonyTransport();

        $this->assertInstanceOf(MailgunHttpTransport::class, $transport);
        $this->assertSame('mailgun+https://api.mailgun.net?domain=mg.spareshop.web.id', (string) $transport);
    }

    public function test_admin_order_status_updates_queue_email_and_render_expected_content(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create([
            'role' => 'customer',
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
        ]);
        $category = Category::create(['name' => 'Oli', 'slug' => 'oli']);
        $sparepart = Sparepart::create([
            'category_id' => $category->id,
            'part_code' => 'OLI-MAIL-01',
            'name' => 'Oli AHM MPX2',
            'brand' => 'AHM',
            'price' => 55000,
            'stock' => 10,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-MAIL-2026',
            'order_type' => 'pasang_bengkel',
            'booking_date' => '2026-12-01',
            'booking_time' => Order::BOOKING_SLOTS[0],
            'service_fee' => 25000,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
            'unit_price' => 55000,
        ]);

        // Unchanged status must NOT queue any email
        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $order), ['status' => 'pending'])
            ->assertRedirect(route('admin.orders.index'));

        Mail::assertNothingQueued();

        // Update to diproses queues email with booking schedule
        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $order), ['status' => 'diproses'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('success');

        Mail::assertQueued(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($customer, $order) {
            $html = $mail->render();

            return $mail->hasTo($customer->email)
                && $mail->order->is($order)
                && $mail->previousStatus === 'pending'
                && str_contains($html, 'INV-MAIL-2026')
                && str_contains($html, 'Pengingat Jadwal Pasang di Bengkel')
                && str_contains($html, Order::BOOKING_SLOTS[0])
                && ! str_contains($html, "\u{2014}");
        });

        // Update to selesai queues email with review CTA
        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $order), ['status' => 'selesai'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('success');

        Mail::assertQueued(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($order) {
            $html = $mail->render();

            return $mail->order->status === 'selesai'
                && $mail->previousStatus === 'diproses'
                && str_contains($html, 'Beri Ulasan Produk')
                && str_contains($html, route('orders.review', $order));
        });

        // Locked order resubmission must NOT queue a third email
        Mail::fake();
        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $order), ['status' => 'batal'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('error');

        Mail::assertNothingQueued();

        // Cancellation on another order queues cancellation email
        $cancelOrder = Order::create([
            'user_id' => $customer->id,
            'invoice_number' => 'INV-MAIL-BATAL',
            'order_type' => 'beli_saja',
            'service_fee' => 0,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $cancelOrder->id,
            'sparepart_id' => $sparepart->id,
            'qty' => 1,
            'unit_price' => 55000,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.orders.index'))
            ->patch(route('admin.orders.updateStatus', $cancelOrder), ['status' => 'batal'])
            ->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('success');

        Mail::assertQueued(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) {
            $html = $mail->render();

            return $mail->order->status === 'batal'
                && str_contains($html, 'INV-MAIL-BATAL')
                && str_contains($html, 'dibatalkan');
        });
    }

    public function test_forgot_and_reset_password_flow_queues_notification_and_updates_password(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'name' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('oldpass123'),
            'role' => 'customer',
        ]);

        // Login page links to forgot password
        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('password.request'), false)
            ->assertSee('Lupa password?');

        // Forgot password form renders cleanly
        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Lupa Password')
            ->assertSee('for="forgot-email"', false);

        $genericSuccess = 'Jika email tersebut terdaftar di sistem kami, link reset password telah dikirimkan. Silakan cek kotak masuk atau folder spam.';

        // Unregistered email must return the exact same generic success response and send nothing
        $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => 'tidak-terdaftar@example.com'])
            ->assertRedirect(route('password.request'))
            ->assertSessionHas('success', $genericSuccess)
            ->assertSessionHasNoErrors();

        Notification::assertNothingSent();

        // Request reset link queues ResetPasswordNotification for registered email
        $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => 'siti@example.com'])
            ->assertRedirect(route('password.request'))
            ->assertSessionHas('success', $genericSuccess)
            ->assertSessionHasNoErrors();

        $capturedToken = null;
        Notification::assertSentTo($user, ResetPasswordNotification::class, function (ResetPasswordNotification $notification) use ($user, &$capturedToken) {
            $capturedToken = $notification->token;
            $mailMessage = $notification->toMail($user);
            $rendered = view($mailMessage->view, $mailMessage->viewData)->render();

            return $mailMessage->subject === 'Reset Password Akun - MotoPart Store'
                && str_contains($rendered, 'Atur Ulang Password')
                && str_contains($rendered, route('password.reset', ['token' => $capturedToken, 'email' => $user->email]))
                && ! str_contains($rendered, "\u{2014}");
        });

        // Immediate retry (broker throttled) also returns generic success without leaking registered email existence
        $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => 'siti@example.com'])
            ->assertRedirect(route('password.request'))
            ->assertSessionHas('success', $genericSuccess)
            ->assertSessionHasNoErrors();

        Notification::assertSentToTimes($user, ResetPasswordNotification::class, 1);

        // Create a verified broker token and exercise reset form + submission
        $token = Password::createToken($user);

        $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ->assertOk()
            ->assertSee('Buat Password Baru')
            ->assertSee('for="reset-email"', false)
            ->assertSee('for="reset-password"', false)
            ->assertSee('for="reset-password-confirmation"', false)
            ->assertSee('value="siti@example.com"', false);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'siti@example.com',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ])->assertRedirect(route('login'))
          ->assertSessionHas('success');

        // Old password must fail, new password must succeed
        $this->post('/login', [
            'email' => 'siti@example.com',
            'password' => 'oldpass123',
        ])->assertSessionHas('error');
        $this->assertGuest();

        $this->post('/login', [
            'email' => 'siti@example.com',
            'password' => 'newpass123',
        ])->assertRedirect('/katalog');
        $this->assertAuthenticatedAs($user);
    }
}
