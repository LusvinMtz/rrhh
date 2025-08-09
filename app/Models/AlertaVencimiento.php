<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AlertaVencimiento extends Model
{
    protected $table = 'alertas_vencimiento';

    protected $fillable = [
        'alertable_type',
        'alertable_id',
        'tipo_alerta',
        'titulo',
        'descripcion',
        'fecha_vencimiento',
        'dias_anticipacion',
        'fecha_alerta',
        'prioridad',
        'estado',
        'destinatarios',
        'fecha_notificacion',
        'acciones_tomadas',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
        'fecha_alerta' => 'date',
        'fecha_notificacion' => 'datetime',
        'destinatarios' => 'array',
    ];

    public function alertable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePorVencer($query, $dias = 30)
    {
        return $query->where('fecha_vencimiento', '<=', now()->addDays($dias));
    }
}
