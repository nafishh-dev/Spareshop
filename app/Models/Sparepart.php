<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [
        'category_id',
        'part_code',
        'name',
        'brand',
        'price',
        'stock',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function motorcycles()
    {
        return $this->belongsToMany(Motorcycle::class, 'sparepart_motorcycle');
    }
}
