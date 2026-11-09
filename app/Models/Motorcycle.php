<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motorcycle extends Model
{
    protected $fillable = ['brand', 'model_name', 'production_year'];

    public function spareparts()
    {
        return $this->belongsToMany(Sparepart::class, 'sparepart_motorcycle');
    }
}
