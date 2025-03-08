<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;

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
}
