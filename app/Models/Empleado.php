<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    protected $fillable = [
        'codigo_empleado',
        'nombres',
        'apellidos',
        'dpi',
        'nit',
        'fecha_nacimiento',
        'genero',
        'estado_civil',
        'telefono',
        'email',
        'direccion',
        'departamento',
        'municipio',
        'puesto',
        'area_trabajo',
        'salario_base',
        'fecha_ingreso',
        'tipo_contrato',
        'estado',
        'numero_igss',
        'numero_irtra',
        'contacto_emergencia_nombre',
        'contacto_emergencia_telefono',
        'contacto_emergencia_relacion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_ingreso' => 'date',
        'salario_base' => 'decimal:2',
    ];

    public function renovaciones(): HasMany
    {
        return $this->hasMany(Renovacion::class);
    }

    public function permisos(): HasMany
    {
        return $this->hasMany(Permiso::class);
    }

    public function altasBajas(): HasMany
    {
        return $this->hasMany(AltaBaja::class);
    }

    public function empleadosRenglon(): HasMany
    {
        return $this->hasMany(EmpleadoRenglon::class);
    }

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombres . ' ' . $this->apellidos;
    }
}
