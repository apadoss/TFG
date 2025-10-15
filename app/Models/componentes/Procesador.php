<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriceHistory;

class Procesador extends Model
{
    protected $table = 'cpus';

    protected $fillable = ["name",
    "vendor",
    "brand",
    "price",
    "in_stock",
    "url",
    "clock_speed",
    "n_cores",
    "n_threads",
    "socket",
    "tdp"];

    public function priceHistory()
    {
        return $this->morphMany(PriceHistory::class, 'component');
    }
}
