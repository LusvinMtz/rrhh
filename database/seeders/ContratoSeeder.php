<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contrato;
use App\Models\Empleado;

class ContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empleados = Empleado::all();
        
        foreach ($empleados as $empleado) {
            // Crear contrato principal para cada empleado
            Contrato::create([
                'empleado_id' => $empleado->id,
                'numero_contrato' => 'CT-' . date('Y') . '-' . str_pad($empleado->id, 4, '0', STR_PAD_LEFT),
                'tipo_contrato' => $empleado->tipo_contrato === 'permanente' ? 'indefinido' : 'temporal',
                'fecha_inicio' => $empleado->fecha_ingreso,
                'fecha_fin' => $empleado->tipo_contrato === 'permanente' ? 
                    now()->addYears(2) : 
                    now()->addMonths(rand(6, 18)),
                'fecha_firma' => $empleado->fecha_ingreso->subDays(rand(1, 15)),
                'puesto' => $empleado->puesto,
                'area_trabajo' => $empleado->area_trabajo,
                'lugar_trabajo' => 'Municipalidad de Sansare',
                'descripcion_puesto' => $this->getDescripcionPuesto($empleado->puesto),
                'salario' => $empleado->salario_base,
                'jornada_laboral' => 'completa',
                'horas_semanales' => 40,
                'beneficios' => 'Aguinaldo, Bono 14, Vacaciones, IGSS, IRTRA',
                'clausulas_especiales' => $this->getClausulas(),
                'observaciones' => 'Contrato generado automáticamente por el sistema',
                'estado' => $this->getEstadoContrato($empleado->fecha_ingreso)
            ]);
        }
        
        // Crear algunos contratos adicionales temporales
        for ($i = 0; $i < 5; $i++) {
            $empleado = $empleados->random();
            
            Contrato::create([
                'empleado_id' => $empleado->id,
                'numero_contrato' => 'CT-TEMP-' . date('Y') . '-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                'tipo_contrato' => 'temporal',
                'fecha_inicio' => now()->subDays(rand(30, 180)),
                'fecha_fin' => now()->addDays(rand(30, 120)),
                'fecha_firma' => now()->subDays(rand(35, 200)),
                'puesto' => 'Consultor ' . $empleado->area_trabajo,
                'area_trabajo' => $empleado->area_trabajo,
                'lugar_trabajo' => 'Municipalidad de Sansare',
                'descripcion_puesto' => 'Consultoría especializada en ' . strtolower($empleado->area_trabajo),
                'salario' => rand(6000, 12000),
                'jornada_laboral' => 'parcial',
                'horas_semanales' => 32,
                'beneficios' => 'Vacaciones, IGSS',
                'clausulas_especiales' => 'Contrato temporal sujeto a disponibilidad presupuestaria',
                'observaciones' => 'Contrato temporal para proyecto específico',
                'estado' => 'activo'
            ]);
        }
    }
    
    private function getDescripcionPuesto($puesto)
    {
        $descripciones = [
            'Director de Recursos Humanos' => 'Dirigir y coordinar las actividades del departamento de recursos humanos',
            'Contador General' => 'Llevar la contabilidad general de la municipalidad',
            'Secretaria Municipal' => 'Brindar apoyo administrativo y secretarial',
            'Técnico en Sistemas' => 'Mantener y administrar los sistemas informáticos',
            'Conserje' => 'Realizar labores de limpieza y mantenimiento',
            'Guardia de Seguridad' => 'Velar por la seguridad de las instalaciones',
            'Auxiliar Contable' => 'Apoyar en las actividades contables y financieras',
            'Recepcionista' => 'Atender al público y manejar comunicaciones',
            'Chofer' => 'Conducir vehículos municipales',
            'Jardinero' => 'Mantener áreas verdes y jardines'
        ];
        
        return $descripciones[$puesto] ?? 'Realizar las funciones asignadas al puesto';
    }
    
    private function getClausulas()
    {
        return 'El empleado se compromete a cumplir con las funciones asignadas, respetar el horario de trabajo, mantener confidencialidad de la información y cumplir con las políticas municipales.';
    }
    
    private function getEstadoContrato($fechaIngreso)
    {
        $diasTrabajados = now()->diffInDays($fechaIngreso);
        
        if ($diasTrabajados > 365) {
            return 'vencido';
        } elseif ($diasTrabajados > 90) {
            return rand(0, 1) ? 'activo' : 'vencido';
        } else {
            return 'activo';
        }
    }
}