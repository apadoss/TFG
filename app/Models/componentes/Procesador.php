<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;

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
}
