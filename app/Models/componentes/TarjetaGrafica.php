<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriceHistory;

class TarjetaGrafica extends Model
{
    protected $table = 'graphic_cards';

    protected $fillable = ["name",
    "vendor",
    "brand",
    "price",
    "in_stock",
    "url",
    "image",
    "manufacturer",
    "vram",
    "mem_type",
    "tdp"];

    public function priceHistory()
    {
        return $this->morphMany(PriceHistory::class, 'component');
    }
}
