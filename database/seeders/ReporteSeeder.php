<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reporte;
use App\Models\User;

class ReporteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = User::all();
        $usuarioAdmin = $usuarios->first();
        
        // Reportes de empleados
        Reporte::create([
            'nombre_reporte' => 'Listado General de Empleados',
            'tipo_reporte' => 'general_interno',
            'descripcion' => 'Reporte completo de todos los empleados activos en la municipalidad',
            'parametros' => json_encode(['estado' => 'activo', 'incluir_foto' => true]),
            'columnas' => json_encode(['nombres', 'apellidos', 'dpi', 'puesto', 'salario', 'fecha_ingreso']),
            'formato' => 'pdf',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subDays(2),
            'archivo_path' => 'reportes/empleados_general_' . now()->format('Y_m_d') . '.pdf',
            'total_registros' => 45,
            'estado' => 'completado',
            'es_publico' => false,
            'fecha_desde' => now()->subYear(),
            'fecha_hasta' => now()
        ]);
        
        Reporte::create([
            'nombre_reporte' => 'Empleados por Departamento',
            'tipo_reporte' => 'informacion_publica',
            'descripcion' => 'Distribución de empleados por departamento municipal',
            'parametros' => json_encode(['agrupar_por' => 'departamento', 'incluir_graficos' => true]),
            'columnas' => json_encode(['departamento', 'total_empleados', 'salario_promedio']),
            'formato' => 'excel',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subDays(5),
            'archivo_path' => 'reportes/empleados_departamento.xlsx',
            'total_registros' => 8,
            'estado' => 'completado',
            'es_publico' => true,
            'fecha_desde' => now()->subMonths(6),
            'fecha_hasta' => now()
        ]);
        
        // Reportes de contratos
        Reporte::create([
            'nombre_reporte' => 'Contratos Vigentes',
            'tipo_reporte' => 'historial_contratos',
            'descripcion' => 'Listado de todos los contratos actualmente vigentes',
            'parametros' => json_encode(['estado' => 'vigente', 'incluir_montos' => true]),
            'columnas' => json_encode(['numero_contrato', 'empleado', 'fecha_inicio', 'fecha_fin', 'monto_total']),
            'formato' => 'pdf',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subDays(1),
            'archivo_path' => 'reportes/contratos_vigentes.pdf',
            'total_registros' => 23,
            'estado' => 'completado',
            'es_publico' => false,
            'fecha_desde' => now()->subMonths(3),
            'fecha_hasta' => now()->addMonths(6)
        ]);
        
        Reporte::create([
            'nombre_reporte' => 'Contratos por Vencer',
            'tipo_reporte' => 'historial_contratos',
            'descripcion' => 'Contratos que vencen en los próximos 60 días',
            'parametros' => json_encode(['dias_vencimiento' => 60, 'ordenar_por' => 'fecha_fin']),
            'columnas' => json_encode(['numero_contrato', 'empleado', 'fecha_fin', 'dias_restantes']),
            'formato' => 'excel',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now(),
            'archivo_path' => 'reportes/contratos_por_vencer.xlsx',
            'total_registros' => 7,
            'estado' => 'generando',
            'es_publico' => false,
            'fecha_desde' => now(),
            'fecha_hasta' => now()->addDays(60)
        ]);
        
        // Reportes de renglones
        Reporte::create([
            'nombre_reporte' => 'Empleados por Renglón Presupuestario',
            'tipo_reporte' => 'general_interno',
            'descripcion' => 'Distribución de empleados según renglón presupuestario',
            'parametros' => json_encode(['incluir_salarios' => true, 'agrupar_por_tipo' => true]),
            'columnas' => json_encode(['numero_renglon', 'tipo_renglon', 'empleados_asignados', 'monto_total']),
            'formato' => 'pdf',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subDays(3),
            'archivo_path' => 'reportes/empleados_renglon.pdf',
            'total_registros' => 15,
            'estado' => 'completado',
            'es_publico' => true,
            'fecha_desde' => now()->subYear(),
            'fecha_hasta' => now()
        ]);
        
        // Reportes financieros
        Reporte::create([
            'nombre_reporte' => 'Resumen Financiero Mensual',
            'tipo_reporte' => 'dashboard',
            'descripcion' => 'Resumen de gastos en personal por mes',
            'parametros' => json_encode(['incluir_prestaciones' => true, 'moneda' => 'GTQ']),
            'columnas' => json_encode(['mes', 'total_salarios', 'total_prestaciones', 'total_general']),
            'formato' => 'excel',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subDays(7),
            'archivo_path' => 'reportes/resumen_financiero_mensual.xlsx',
            'total_registros' => 12,
            'estado' => 'completado',
            'es_publico' => false,
            'fecha_desde' => now()->subYear(),
            'fecha_hasta' => now()
        ]);
        
        // Reportes de alertas
        Reporte::create([
            'nombre_reporte' => 'Alertas Pendientes',
            'tipo_reporte' => 'dashboard',
            'descripcion' => 'Listado de alertas pendientes de atención',
            'parametros' => json_encode(['estado' => 'pendiente', 'prioridad_minima' => 'media']),
            'columnas' => json_encode(['titulo', 'tipo_alerta', 'fecha_vencimiento', 'prioridad']),
            'formato' => 'pdf',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subHours(6),
            'archivo_path' => 'reportes/alertas_pendientes.pdf',
            'total_registros' => 18,
            'estado' => 'completado',
            'es_publico' => false,
            'fecha_desde' => now()->subDays(30),
            'fecha_hasta' => now()->addDays(30)
        ]);
        
        // Reportes en proceso y con errores
        Reporte::create([
            'nombre_reporte' => 'Análisis de Productividad',
            'tipo_reporte' => 'dashboard',
            'descripcion' => 'Análisis de productividad por departamento',
            'parametros' => json_encode(['incluir_graficos' => true, 'periodo' => 'trimestral']),
            'columnas' => json_encode(['departamento', 'proyectos_completados', 'eficiencia']),
            'formato' => 'pdf',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subMinutes(30),
            'archivo_path' => null,
            'total_registros' => 0,
            'estado' => 'generando',
            'es_publico' => true,
            'fecha_desde' => now()->subMonths(3),
            'fecha_hasta' => now()
        ]);
        
        Reporte::create([
            'nombre_reporte' => 'Reporte de Asistencia',
            'tipo_reporte' => 'personalizado',
            'descripcion' => 'Control de asistencia mensual',
            'parametros' => json_encode(['incluir_tardanzas' => true, 'formato_horas' => '24h']),
            'columnas' => json_encode(['empleado', 'dias_trabajados', 'tardanzas', 'faltas']),
            'formato' => 'excel',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subHours(2),
            'archivo_path' => null,
            'total_registros' => 0,
            'estado' => 'error',
            'mensaje_error' => 'Error al acceder a los datos de asistencia. Verificar conexión con sistema biométrico.',
            'es_publico' => false,
            'fecha_desde' => now()->subMonth(),
            'fecha_hasta' => now()
        ]);
        
        Reporte::create([
            'nombre_reporte' => 'Evaluaciones de Desempeño',
            'tipo_reporte' => 'personalizado',
            'descripcion' => 'Resultados de evaluaciones de desempeño anuales',
            'parametros' => json_encode(['incluir_comentarios' => false, 'solo_completadas' => true]),
            'columnas' => json_encode(['empleado', 'puntuacion', 'categoria', 'fecha_evaluacion']),
            'formato' => 'pdf',
            'generado_por' => $usuarioAdmin->id,
            'fecha_generacion' => now()->subDays(10),
            'archivo_path' => 'reportes/evaluaciones_desempeno.pdf',
            'total_registros' => 32,
            'estado' => 'completado',
            'es_publico' => false,
            'fecha_desde' => now()->subYear(),
            'fecha_hasta' => now()
        ]);
    }
}
