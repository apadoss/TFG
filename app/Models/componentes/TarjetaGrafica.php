<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;

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
}
