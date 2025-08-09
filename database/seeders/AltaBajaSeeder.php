<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AltaBajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empleados = \App\Models\Empleado::all();
        
        // Crear registros de altas (ingresos)
        foreach ($empleados as $empleado) {
            \App\Models\AltaBaja::create([
                'empleado_id' => $empleado->id,
                'tipo_movimiento' => 'alta',
                'fecha_movimiento' => $empleado->fecha_ingreso,
                'motivo' => 'contratacion',
                'descripcion' => 'Alta por contratación en el puesto de ' . $empleado->puesto . ' en el área de ' . $empleado->area_trabajo,
                'documento_soporte' => 'Contrato de trabajo CT-' . date('Y', strtotime($empleado->fecha_ingreso)) . '-' . str_pad($empleado->id, 4, '0', STR_PAD_LEFT),
                'autorizado_por' => 'Director de Recursos Humanos',
                'observaciones' => 'Ingreso inicial del empleado al sistema',
                'estado' => 'aprobado',
                'fecha_efectiva' => $empleado->fecha_ingreso
            ]);
        }
        
        // Crear algunas bajas de ejemplo
        $empleadosParaBaja = $empleados->random(2);
        
        foreach ($empleadosParaBaja as $empleado) {
            \App\Models\AltaBaja::create([
                'empleado_id' => $empleado->id,
                'tipo_movimiento' => 'baja',
                'fecha_movimiento' => now()->subDays(rand(30, 180)),
                'motivo' => 'renuncia',
                'descripcion' => 'Renuncia voluntaria del empleado',
                'documento_soporte' => 'Carta de renuncia',
                'autorizado_por' => 'Director de Recursos Humanos',
                'observaciones' => 'Renuncia por motivos personales',
                'estado' => 'ejecutado',
                'fecha_efectiva' => now()->subDays(rand(25, 175))
            ]);
        }
    }
}
