<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    protected $table = 'price_history';
    protected $fillable = ['component_type', 'component_id', 'vendor', 'price'];
    
    public function component()
    {
        return $this->morphTo();
    }
}
