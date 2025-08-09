<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $fillable = [
        'nombre_reporte',
        'tipo_reporte',
        'descripcion',
        'parametros',
        'columnas',
        'formato',
        'generado_por',
        'fecha_generacion',
        'archivo_path',
        'total_registros',
        'estado',
        'mensaje_error',
        'es_publico',
        'fecha_desde',
        'fecha_hasta',
    ];

    protected $casts = [
        'fecha_generacion' => 'datetime',
        'fecha_desde' => 'date',
        'fecha_hasta' => 'date',
        'parametros' => 'array',
        'columnas' => 'array',
        'es_publico' => 'boolean',
    ];

    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopePublicos($query)
    {
        return $query->where('es_publico', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_reporte', $tipo);
    }
}
