<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permiso extends Model
{
    protected $fillable = [
        'empleado_id',
        'tipo_permiso',
        'fecha_inicio',
        'fecha_fin',
        'dias_solicitados',
        'motivo',
        'estado',
        'fecha_solicitud',
        'fecha_aprobacion',
        'aprobado_por',
        'observaciones_empleado',
        'observaciones_supervisor',
        'con_goce_salario',
        'documento_adjunto',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_solicitud' => 'date',
        'fecha_aprobacion' => 'date',
        'con_goce_salario' => 'boolean',
        'dias_solicitados' => 'integer',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
}
