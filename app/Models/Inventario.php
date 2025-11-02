<?php

namespace App\Models;

use App\Observers\InventarioObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([InventarioObserver::class])]
class Inventario extends Model
{
    protected $guarded = ['id'];

    protected $table = 'inventario';

    // *** CAMBIO 1: Se elimina el cast para 'fecha_vencimiento' ***
    protected $casts = [
        // 'fecha_vencimiento' => 'date', // LÍNEA ELIMINADA
    ];

    public function ubicacione(): BelongsTo
    {
        return $this->belongsTo(Ubicacione::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // *** CAMBIO 2: Se elimina el método accesor relacionado con la fecha de vencimiento ***
    /*
    public function getFechaVencimientoFormatAttribute(): string
    {
        return $this->fecha_vencimiento ? $this->fecha_vencimiento->format('d/m/Y') : '';
    }
    */
}