<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    /**
     * @var list<int>
     */
    public array $backoff = [10, 30, 60];

    public function __construct(public Order $order, public string $previousStatus)
    {
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->order->status) {
            'diproses' => "Pesanan #{$this->order->invoice_number} Sedang Diproses - MotoPart Store",
            'selesai' => "Pesanan #{$this->order->invoice_number} Selesai - MotoPart Store",
            'batal' => "Pesanan #{$this->order->invoice_number} Dibatalkan - MotoPart Store",
            default => "Pembaruan Status Pesanan #{$this->order->invoice_number} - MotoPart Store",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $this->order->loadMissing(['user', 'items.sparepart']);

        return new Content(
            view: 'emails.orders.status-updated',
            with: [
                'order' => $this->order,
                'previousStatus' => $this->previousStatus,
            ],
        );
    }
}
