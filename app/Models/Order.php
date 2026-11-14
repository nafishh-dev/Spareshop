<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $casts = [
        'booking_date' => 'date',
    ];

    protected $fillable = [
        'user_id',
        'invoice_number',
        'order_type',
        'booking_date',
        'service_fee',
        'customer_note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function getTotalPriceAttribute(): int
    {
        return (int) $this->items->sum(fn ($i) => $i->qty * $i->unit_price) + $this->service_fee;
    }
}
