<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Renovacion extends Model
{
    protected $table = 'renovaciones';

    protected $fillable = [
        'contrato_id',
        'numero_renovacion',
        'fecha_renovacion',
        'nueva_fecha_fin',
        'nuevo_salario',
        'nuevo_puesto',
        'nueva_area_trabajo',
        'cambios_realizados',
        'justificacion',
        'estado',
        'fecha_aprobacion',
        'aprobado_por',
        'observaciones',
    ];

    protected $casts = [
        'fecha_renovacion' => 'date',
        'nueva_fecha_fin' => 'date',
        'fecha_aprobacion' => 'date',
        'nuevo_salario' => 'decimal:2',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }
}
