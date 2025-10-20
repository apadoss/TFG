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

    public function getTotalPriceAttribute()
    {
        $total = 0;
        
        if ($this->cpu) {
            $total += $this->cpu->price ?? 0;
        }
        if ($this->graphic_card) {
            $total += $this->graphic_card->price ?? 0;
        }
        if ($this->motherboard) {
            $total += $this->motherboard->price ?? 0;
        }
        if ($this->ram) {
            $total += $this->ram->price ?? 0;
        }
        if ($this->storage) {
            $total += $this->storage->price ?? 0;
        }
        if ($this->power_supply) {
            $total += $this->power_supply->price ?? 0;
        }
        
        return $total;
    }

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
