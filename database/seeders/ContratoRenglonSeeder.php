<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContratoRenglon;
use App\Models\EmpleadoRenglon;

class ContratoRenglonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empleadosRenglon = EmpleadoRenglon::where('tipo_renglon', '022')->get();
        
        foreach ($empleadosRenglon as $empleadoRenglon) {
            ContratoRenglon::create([
                'empleado_renglon_id' => $empleadoRenglon->id,
                'numero_contrato_renglon' => 'CR-' . date('Y') . '-' . str_pad($empleadoRenglon->id, 4, '0', STR_PAD_LEFT),
                'tipo_contrato' => $this->getTipoContrato(),
                'fecha_inicio' => now()->subDays(rand(1, 180)),
                'fecha_fin' => now()->addDays(rand(90, 365)),
                'monto_total' => rand(50000, 200000),
                'monto_mensual' => rand(8000, 15000),
                'objeto_contrato' => $this->getObjetoContrato(),
                'productos_entregables' => $this->getProductosEntregables(),
                'fuente_financiamiento' => 'Fondos Propios',
                'programa_presupuestario' => '01 Administración General',
                'estado' => $this->getEstadoContrato(),
                'fecha_firma' => now()->subDays(rand(1, 200)),
                'supervisor_contrato' => $this->getSupervisor(),
                'clausulas_especiales' => 'Cumplimiento de horarios y entrega de productos según cronograma',
                'observaciones' => 'Contrato por renglón presupuestario 022'
            ]);
        }
        
        // Crear algunos contratos adicionales
        $empleadosRenglonAdicionales = EmpleadoRenglon::whereIn('tipo_renglon', ['021', '029'])->take(5)->get();
        
        foreach ($empleadosRenglonAdicionales as $empleadoRenglon) {
            ContratoRenglon::create([
                'empleado_renglon_id' => $empleadoRenglon->id,
                'numero_contrato_renglon' => 'CR-' . date('Y') . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'tipo_contrato' => $this->getTipoContrato(),
                'fecha_inicio' => now()->subDays(rand(1, 90)),
                'fecha_fin' => now()->addDays(rand(180, 365)),
                'monto_total' => rand(30000, 150000),
                'monto_mensual' => rand(5000, 12000),
                'objeto_contrato' => $this->getObjetoContrato(),
                'productos_entregables' => $this->getProductosEntregables(),
                'fuente_financiamiento' => rand(0, 1) ? 'Fondos Propios' : 'Transferencias del Gobierno Central',
                'programa_presupuestario' => $this->getProgramaPresupuestario(),
                'estado' => $this->getEstadoContrato(),
                'fecha_firma' => now()->subDays(rand(1, 100)),
                'supervisor_contrato' => $this->getSupervisor(),
                'clausulas_especiales' => 'Sujeto a disponibilidad presupuestaria',
                'observaciones' => 'Contrato temporal'
            ]);
        }
    }
    
    private function getTipoContrato()
    {
        $tipos = ['temporal', 'por_servicios', 'consultoria'];
        return $tipos[array_rand($tipos)];
    }
    
    private function getObjetoContrato()
    {
        $objetos = [
            'Prestación de servicios profesionales en el área administrativa',
            'Servicios técnicos especializados para proyectos municipales',
            'Consultoría para mejora de procesos administrativos',
            'Ejecución de obras de infraestructura menor',
            'Suministro de materiales y equipos para la municipalidad'
        ];
        return $objetos[array_rand($objetos)];
    }
    
    private function getProductosEntregables()
    {
        $productos = [
            'Informes mensuales de actividades realizadas',
            'Documentos técnicos y estudios especializados',
            'Reportes de avance de proyectos',
            'Entrega de obras según especificaciones técnicas',
            'Suministros según listado de requerimientos'
        ];
        return $productos[array_rand($productos)];
    }
    
    private function getEstadoContrato()
    {
        $estados = ['activo', 'activo', 'activo', 'borrador', 'terminado'];
        return $estados[array_rand($estados)];
    }
    
    private function getSupervisor()
    {
        $supervisores = [
            'Lic. María González - Directora Administrativa',
            'Ing. Carlos Pérez - Jefe de Proyectos',
            'Licda. Ana Rodríguez - Coordinadora de RRHH',
            'Lic. José Martínez - Supervisor General'
        ];
        return $supervisores[array_rand($supervisores)];
    }
    
    private function getProgramaPresupuestario()
    {
        $programas = [
            '01 Administración General',
            '02 Servicios Públicos',
            '03 Desarrollo Social',
            '04 Infraestructura'
        ];
        return $programas[array_rand($programas)];
    }
}
