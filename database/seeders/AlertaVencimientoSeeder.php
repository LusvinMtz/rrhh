<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AlertaVencimiento;
use App\Models\Contrato;
use App\Models\ContratoRenglon;
use App\Models\EmpleadoRenglon;

class AlertaVencimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Alertas para contratos regulares
        $contratos = Contrato::take(5)->get();
        foreach ($contratos as $contrato) {
            AlertaVencimiento::create([
                'alertable_type' => 'App\\Models\\Contrato',
                'alertable_id' => $contrato->id,
                'tipo_alerta' => 'contrato_vencimiento',
                'titulo' => 'Vencimiento de Contrato - ' . $contrato->numero_contrato,
                'descripcion' => 'El contrato está próximo a vencer. Revisar renovación.',
                'fecha_vencimiento' => $contrato->fecha_fin,
                'dias_anticipacion' => 30,
                'fecha_alerta' => $contrato->fecha_fin->subDays(30),
                'prioridad' => $this->getPrioridad(),
                'estado' => $this->getEstadoAlerta(),
                'destinatarios' => 'admin@sansare.gob.gt, rrhh@sansare.gob.gt',
                'fecha_notificacion' => rand(0, 1) ? now()->subDays(rand(1, 15)) : null,
                'acciones_tomadas' => rand(0, 1) ? 'Se notificó al supervisor del contrato' : null
            ]);
        }
        
        // Alertas para contratos por renglón
        $contratosRenglon = ContratoRenglon::take(8)->get();
        foreach ($contratosRenglon as $contratoRenglon) {
            AlertaVencimiento::create([
                'alertable_type' => 'App\\Models\\ContratoRenglon',
                'alertable_id' => $contratoRenglon->id,
                'tipo_alerta' => 'contrato_renglon_vencimiento',
                'titulo' => 'Vencimiento Contrato Renglón - ' . $contratoRenglon->numero_contrato_renglon,
                'descripcion' => 'El contrato por renglón está próximo a vencer.',
                'fecha_vencimiento' => $contratoRenglon->fecha_fin,
                'dias_anticipacion' => 15,
                'fecha_alerta' => $contratoRenglon->fecha_fin->subDays(15),
                'prioridad' => $this->getPrioridad(),
                'estado' => $this->getEstadoAlerta(),
                'destinatarios' => 'admin@sansare.gob.gt, presupuesto@sansare.gob.gt',
                'fecha_notificacion' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                'acciones_tomadas' => rand(0, 1) ? 'Revisión de presupuesto realizada' : null
            ]);
        }
        
        // Alertas para empleados por renglón
        $empleadosRenglon = EmpleadoRenglon::where('fecha_vigencia_fin', '>', now())->take(6)->get();
        foreach ($empleadosRenglon as $empleadoRenglon) {
            AlertaVencimiento::create([
                'alertable_type' => 'App\\Models\\EmpleadoRenglon',
                'alertable_id' => $empleadoRenglon->id,
                'tipo_alerta' => 'contrato_renglon_vencimiento',
                'titulo' => 'Renovación Pendiente - Renglón ' . $empleadoRenglon->numero_renglon,
                'descripcion' => 'La vigencia del renglón está próxima a vencer. Evaluar renovación.',
                'fecha_vencimiento' => $empleadoRenglon->fecha_vigencia_fin,
                'dias_anticipacion' => 45,
                'fecha_alerta' => $empleadoRenglon->fecha_vigencia_fin->subDays(45),
                'prioridad' => $this->getPrioridad(),
                'estado' => $this->getEstadoAlerta(),
                'destinatarios' => 'rrhh@sansare.gob.gt, presupuesto@sansare.gob.gt',
                'fecha_notificacion' => rand(0, 1) ? now()->subDays(rand(1, 20)) : null,
                'acciones_tomadas' => rand(0, 1) ? 'Evaluación de desempeño programada' : null
            ]);
        }
        
        // Alertas adicionales de diferentes tipos
        $tiposAdicionales = ['documento_vencimiento', 'permiso_vencimiento'];
        
        for ($i = 0; $i < 10; $i++) {
            $tipo = $tiposAdicionales[array_rand($tiposAdicionales)];
            $contrato = Contrato::inRandomOrder()->first();
            
            AlertaVencimiento::create([
                'alertable_type' => 'App\\Models\\Contrato',
                'alertable_id' => $contrato->id,
                'tipo_alerta' => $tipo,
                'titulo' => $this->getTituloTipo($tipo),
                'descripcion' => $this->getDescripcionTipo($tipo),
                'fecha_vencimiento' => now()->addDays(rand(1, 60)),
                'dias_anticipacion' => rand(7, 30),
                'fecha_alerta' => now()->addDays(rand(1, 30)),
                'prioridad' => $this->getPrioridad(),
                'estado' => $this->getEstadoAlerta(),
                'destinatarios' => 'admin@sansare.gob.gt',
                'fecha_notificacion' => rand(0, 1) ? now()->subDays(rand(1, 5)) : null,
                'acciones_tomadas' => rand(0, 1) ? 'En proceso de revisión' : null
            ]);
        }
    }
    
    private function getPrioridad()
    {
        $prioridades = ['baja', 'media', 'alta', 'critica'];
        return $prioridades[array_rand($prioridades)];
    }
    
    private function getEstadoAlerta()
    {
        $estados = ['pendiente', 'pendiente', 'notificada', 'atendida', 'vencida'];
        return $estados[array_rand($estados)];
    }
    
    private function getTituloTipo($tipo)
    {
        return match($tipo) {
            'documento_vencimiento' => 'Documento Próximo a Vencer',
            'permiso_vencimiento' => 'Permiso Próximo a Vencer',
            default => 'Alerta General'
        };
    }
    
    private function getDescripcionTipo($tipo)
    {
        return match($tipo) {
            'documento_vencimiento' => 'Documentos del contrato próximos a vencer. Revisar y actualizar.',
            'permiso_vencimiento' => 'Permiso próximo a vencer. Renovar documentación.',
            default => 'Revisar estado del contrato'
        };
    }
}
