<?php

namespace App\Models;

use App\Models\componentes\Almacenamiento;
use App\Models\componentes\FuenteAlimentacion;
use App\Models\componentes\MemoriaRam;
use App\Models\componentes\PlacasBase;
use App\Models\componentes\Procesador;
use App\Models\componentes\TarjetaGrafica;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configurations';

    public function cpu() {
        return $this->belongsTo(Procesador::class);
    }

    public function graphic_card() {
        return $this->belongsTo(TarjetaGrafica::class, 'gpu_id');
    }

    public function motherboard() {
        return $this->belongsTo(PlacasBase::class);
    }
    
    public function ram() {
        return $this->belongsTo(MemoriaRam::class);
    }

    public function storage() {
        return $this->belongsTo(Almacenamiento::class);
    }

    public function power_supply() {
        return $this->belongsTo(FuenteAlimentacion::class);
    }
}
