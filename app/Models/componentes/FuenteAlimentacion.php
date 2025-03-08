<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;

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
}
