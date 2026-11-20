<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparepartReview extends Model
{
    protected $fillable = [
        'user_id',
        'sparepart_id',
        'order_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sparepart()
    {
        return $this->belongsTo(Sparepart::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
