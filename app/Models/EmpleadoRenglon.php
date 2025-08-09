<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmpleadoRenglon extends Model
{
    protected $table = 'empleados_renglon';

    protected $fillable = [
        'empleado_id',
        'numero_renglon',
        'tipo_renglon',
        'descripcion_renglon',
        'salario_renglon',
        'fecha_asignacion',
        'fecha_vigencia_inicio',
        'fecha_vigencia_fin',
        'estado',
        'dependencia',
        'unidad_ejecutora',
        'observaciones',
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_vigencia_inicio' => 'date',
        'fecha_vigencia_fin' => 'date',
        'salario_renglon' => 'decimal:2',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function contratosRenglon(): HasMany
    {
        return $this->hasMany(ContratoRenglon::class);
    }
}
