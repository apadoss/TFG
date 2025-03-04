<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;

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
}
