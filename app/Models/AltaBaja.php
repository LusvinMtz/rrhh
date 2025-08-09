<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AltaBaja extends Model
{
    protected $table = 'altas_bajas';

    protected $fillable = [
        'empleado_id',
        'tipo_movimiento',
        'fecha_movimiento',
        'motivo',
        'descripcion',
        'documento_soporte',
        'autorizado_por',
        'fecha_efectiva',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_movimiento' => 'date',
        'fecha_efectiva' => 'date',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
}
