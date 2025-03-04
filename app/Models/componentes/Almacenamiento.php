<?php

namespace App\Models\componentes;

use Illuminate\Database\Eloquent\Model;

class Almacenamiento extends Model
{
    protected $table = 'storage_devices';

    protected $fillable = ["name",
    "vendor",
    "brand",
    "price",
    "in_stock",
    "url",
    "image",
    "type",
    "storage"];
}
