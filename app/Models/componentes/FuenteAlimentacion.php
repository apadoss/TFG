<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriceHistory;

class FuenteAlimentacion extends Model
{
    protected $table = 'power_supplies';

    protected $fillable = ["name",
    "vendor",
    "brand",
    "price",
    "in_stock",
    "url",
    "image",
    "certification",
    "power"];

    public function priceHistory()
    {
        return $this->morphMany(PriceHistory::class, 'component');
    }
}
