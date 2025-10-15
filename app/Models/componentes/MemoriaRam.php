<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriceHistory;

class MemoriaRam extends Model
{
    protected $table = 'rams';

    protected $fillable = ["name",
    "vendor",
    "brand",
    "price",
    "in_stock",
    "url",
    "image",
    "type",
    "n_modules",
    "module_capacity",
    "frequency",
    "latency"
    ];

    public function priceHistory()
    {
        return $this->morphMany(PriceHistory::class, 'component');
    }
}
