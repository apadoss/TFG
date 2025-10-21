<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PriceNotification extends Model
{
    protected $fillable = [
        'user_id',
        'component_type',
        'component_id',
        'target_price',
        'notify_any_drop',
        'is_active',
        'last_notified_at'
    ];

    protected $casts = [
        'target_price' => 'float',
        'notify_any_drop' => 'boolean',
        'is_active' => 'boolean',
        'last_notified_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function component(): MorphTo {
        return $this->morphTo();
    }

    public function shouldNotify(float $newPrice, float $oldPrice): bool {
        // No notificar si no está activo
        if (!$this->is_active) {
            return false;
        }

        // No notificar si el precio subió
        if ($newPrice >= $oldPrice) {
            return false;
        }

        // Si hay precio objetivo, verificar si llegó o bajó del objetivo
        if ($this->target_price && $newPrice <= $this->target_price) {
            return true;
        }

        // Si notify_any_drop está activo, notificar cualquier bajada
        if ($this->notify_any_drop) {
            return true;
        }

        return false;
    } 

}
