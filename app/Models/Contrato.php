<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contrato extends Model
{
    protected $fillable = [
        'empleado_id',
        'numero_contrato',
        'tipo_contrato',
        'fecha_inicio',
        'fecha_fin',
        'salario',
        'puesto',
        'area_trabajo',
        'descripcion_puesto',
        'jornada_laboral',
        'horas_semanales',
        'clausulas_especiales',
        'estado',
        'fecha_firma',
        'lugar_trabajo',
        'beneficios',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_firma' => 'date',
        'salario' => 'decimal:2',
        'horas_semanales' => 'integer',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function renovaciones(): HasMany
    {
        return $this->hasMany(Renovacion::class);
    }
}
