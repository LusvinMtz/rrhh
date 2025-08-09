<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ContratoRenglon extends Model
{
    protected $table = 'contratos_renglon';

    protected $fillable = [
        'empleado_renglon_id',
        'numero_contrato_renglon',
        'tipo_contrato',
        'fecha_inicio',
        'fecha_fin',
        'monto_total',
        'monto_mensual',
        'objeto_contrato',
        'productos_entregables',
        'fuente_financiamiento',
        'programa_presupuestario',
        'estado',
        'fecha_firma',
        'supervisor_contrato',
        'clausulas_especiales',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_firma' => 'date',
        'monto_total' => 'decimal:2',
        'monto_mensual' => 'decimal:2',
    ];

    public function empleadoRenglon(): BelongsTo
    {
        return $this->belongsTo(EmpleadoRenglon::class);
    }

    public function alertasVencimiento(): MorphMany
    {
        return $this->morphMany(AlertaVencimiento::class, 'alertable');
    }
}
