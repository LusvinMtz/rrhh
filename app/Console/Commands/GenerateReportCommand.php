<?php

namespace App\Console\Commands;

use App\Models\Reporte;
use App\Services\ReportGeneratorService;
use Illuminate\Console\Command;

class GenerateReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reportes:generate {id : ID del reporte a generar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un reporte específico por su ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reporteId = $this->argument('id');
        $reporte = Reporte::find($reporteId);
        
        if (!$reporte) {
            $this->error("Reporte con ID {$reporteId} no encontrado.");
            return 1;
        }
        
        $this->info("Generando reporte: {$reporte->nombre_reporte}");
        
        try {
            $generator = new ReportGeneratorService();
            $generator->generate($reporte);
            
            $this->info("Reporte generado exitosamente.");
            return 0;
        } catch (\Exception $e) {
            $reporte->update([
                'estado' => 'error',
                'mensaje_error' => $e->getMessage()
            ]);
            
            $this->error("Error al generar reporte: " . $e->getMessage());
            return 1;
        }
    }
}
