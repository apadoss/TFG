<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;
use App\Models\PriceHistory;

class PlacasBase extends Model
{
    protected $table = 'motherboards';

    protected $fillable = ["name",
    "vendor",
    "brand",
    "price",
    "in_stock",
    "url",
    "image",
    "socket",
    "chipset",
    "size_format"];

    public function priceHistory()
    {
        return $this->morphMany(PriceHistory::class, 'component');
    }
}
