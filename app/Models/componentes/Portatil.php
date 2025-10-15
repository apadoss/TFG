<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriceHistory;

class Portatil extends Model
{
    protected $table = 'laptops';

    protected $fillable = ["name",
    "vendor",
    "brand",
    "price",
    "in_stock",
    "url",
    "image",
    "cpu",
    "ram",
    "storage",
    "gpu",
    "screen_resolution",
    "battery_life",
    "weight"
    ];

    public function priceHistory()
    {
        return $this->morphMany(PriceHistory::class, 'component');
    }
}
