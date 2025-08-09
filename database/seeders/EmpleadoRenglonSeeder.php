<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmpleadoRenglon;
use App\Models\Empleado;

class EmpleadoRenglonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empleados = Empleado::all();
        
        foreach ($empleados as $empleado) {
            EmpleadoRenglon::create([
                'empleado_id' => $empleado->id,
                'numero_renglon' => '011-' . str_pad($empleado->id, 3, '0', STR_PAD_LEFT),
                'tipo_renglon' => '011',
                'descripcion_renglon' => 'Personal Permanente - ' . $empleado->puesto,
                'salario_renglon' => rand(8000, 25000),
                'fecha_asignacion' => now()->subDays(rand(30, 365)),
                'fecha_vigencia_inicio' => now()->subDays(rand(30, 365)),
                'fecha_vigencia_fin' => now()->addDays(rand(365, 730)),
                'estado' => 'activo',
                'dependencia' => 'Municipalidad de Sansare',
                'unidad_ejecutora' => 'Administración Municipal',
                'observaciones' => 'Renglón presupuestario asignado'
            ]);
        }
        
        // Crear algunos renglones adicionales de diferentes tipos
        $tiposRenglon = ['021', '022', '029', '031'];
        
        for ($i = 0; $i < 10; $i++) {
            $empleado = $empleados->random();
            $tipo = $tiposRenglon[array_rand($tiposRenglon)];
            
            EmpleadoRenglon::create([
                'empleado_id' => $empleado->id,
                'numero_renglon' => $tipo . '-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                'tipo_renglon' => $tipo,
                'descripcion_renglon' => $this->getDescripcionTipo($tipo),
                'salario_renglon' => rand(5000, 15000),
                'fecha_asignacion' => now()->subDays(rand(1, 180)),
                'fecha_vigencia_inicio' => now()->subDays(rand(1, 180)),
                'fecha_vigencia_fin' => now()->addDays(rand(90, 365)),
                'estado' => rand(0, 1) ? 'activo' : 'inactivo',
                'dependencia' => 'Municipalidad de Sansare',
                'unidad_ejecutora' => 'Administración Municipal',
                'observaciones' => 'Renglón temporal'
            ]);
        }
    }
    
    private function getDescripcionTipo($tipo)
    {
        return match($tipo) {
            '021' => 'Personal Supernumerario',
            '022' => 'Personal por Contrato',
            '029' => 'Otras Remuneraciones',
            '031' => 'Jornales',
            default => 'Personal Permanente'
        };
    }
}
