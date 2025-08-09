<?php

namespace App\Services;

use App\Models\Reporte;
use App\Models\Empleado;
use App\Models\Contrato;
use App\Models\AltaBaja;
use App\Models\AlertaVencimiento;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteExport;

class ReportGeneratorService
{
    public function generate(Reporte $reporte)
    {
        try {
            // Actualizar estado a generando
            $reporte->update([
                'estado' => 'generando',
                'fecha_generacion' => now(),
                'mensaje_error' => null
            ]);
            
            // Obtener datos según el tipo de reporte
            $data = $this->getData($reporte);
            
            // Verificar que se obtuvieron datos
            if (empty($data)) {
                throw new \Exception('No se encontraron datos para generar el reporte. Verifique los parámetros y fechas del reporte.');
            }
            
            // Generar archivo según el formato
            $filePath = $this->generateFile($reporte, $data);
            
            // Verificar que el archivo se generó correctamente
            if (!$filePath || !\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                throw new \Exception('Error al generar el archivo del reporte. Verifique los permisos de escritura.');
            }
            
            // Actualizar reporte con información del archivo generado
            $reporte->update([
                'estado' => 'completado',
                'archivo_path' => $filePath,
                'total_registros' => count($data)
            ]);
            
            return $filePath;
            
        } catch (\Exception $e) {
            // Actualizar reporte con error
            $reporte->update([
                'estado' => 'error',
                'mensaje_error' => $e->getMessage()
            ]);
            
            // Re-lanzar la excepción para que pueda ser manejada por el llamador
            throw $e;
        }
    }
    
    private function getData(Reporte $reporte)
    {
        $parametros = $reporte->parametros ?? [];
        $fechaDesde = $reporte->fecha_desde;
        $fechaHasta = $reporte->fecha_hasta;
        
        return match($reporte->tipo_reporte) {
            'general_interno', 'informacion_publica' => $this->getEmpleadosData($parametros, $fechaDesde, $fechaHasta),
            'historial_empleados' => $this->getEmpleadosData($parametros, $fechaDesde, $fechaHasta),
            'historial_contratos' => $this->getContratosData($parametros, $fechaDesde, $fechaHasta),
            'historial_permisos' => $this->getPermisosData($parametros, $fechaDesde, $fechaHasta),
            'dashboard' => $this->getDashboardData($parametros, $fechaDesde, $fechaHasta),
            'personalizado' => $this->getPersonalizadoData($parametros, $fechaDesde, $fechaHasta),
            default => []
        };
    }
    
    private function getEmpleadosData($parametros, $fechaDesde, $fechaHasta)
    {
        $query = Empleado::query();
        
        if (isset($parametros['estado'])) {
            $query->where('estado', $parametros['estado']);
        }
        
        // For general reports, only apply date filter if specifically requested
        // and if the date range is reasonable (not too restrictive)
        if ($fechaDesde && $fechaHasta) {
            // Check if it's a reasonable date range (more than 1 year)
            $dateFrom = \Carbon\Carbon::parse($fechaDesde);
            $dateTo = \Carbon\Carbon::parse($fechaHasta);
            
            if ($dateTo->diffInYears($dateFrom) >= 1) {
                $query->where('fecha_ingreso', '>=', $fechaDesde)
                      ->where('fecha_ingreso', '<=', $fechaHasta);
            }
        }
        
        return $query->get()->toArray();
    }
    
    private function getContratosData($parametros, $fechaDesde, $fechaHasta)
    {
        $query = Contrato::with('empleado');
        
        if (isset($parametros['estado'])) {
            $query->where('estado', $parametros['estado']);
        }
        
        if ($fechaDesde) {
            $query->where('fecha_inicio', '>=', $fechaDesde);
        }
        
        if ($fechaHasta) {
            $query->where('fecha_fin', '<=', $fechaHasta);
        }
        
        return $query->get()->toArray();
    }
    
    private function getPermisosData($parametros, $fechaDesde, $fechaHasta)
    {
        // Implementar lógica para permisos cuando el modelo esté disponible
        return [];
    }
    
    private function getDashboardData($parametros, $fechaDesde, $fechaHasta)
    {
        // Datos agregados para dashboard
        return [
            'total_empleados' => Empleado::count(),
            'contratos_activos' => Contrato::where('estado', 'activo')->count(),
            'alertas_pendientes' => AlertaVencimiento::where('estado', 'pendiente')->count(),
        ];
    }
    
    private function getPersonalizadoData($parametros, $fechaDesde, $fechaHasta)
    {
        // Lógica personalizada según parámetros
        return $this->getEmpleadosData($parametros, $fechaDesde, $fechaHasta);
    }
    
    private function generateFile(Reporte $reporte, array $data)
    {
        $fileName = 'reportes/' . $reporte->id . '_' . now()->format('Y_m_d_H_i_s');
        
        return match($reporte->formato) {
            'pdf' => $this->generatePDF($reporte, $data, $fileName),
            'excel' => $this->generateExcel($reporte, $data, $fileName),
            'csv' => $this->generateCSV($reporte, $data, $fileName),
            'html' => $this->generateHTML($reporte, $data, $fileName),
            default => throw new \Exception('Formato no soportado: ' . $reporte->formato)
        };
    }
    
    private function generatePDF(Reporte $reporte, array $data, string $fileName)
    {
        try {
            $fileName .= '.pdf';
            
            $pdf = Pdf::loadView('reportes.pdf', [
                'reporte' => $reporte,
                'data' => $data
            ]);
            
            $pdfOutput = $pdf->output();
            if (!$pdfOutput) {
                throw new \Exception('Error al generar el contenido del PDF');
            }
            
            Storage::disk('public')->put($fileName, $pdfOutput);
            
            return $fileName;
        } catch (\Exception $e) {
            throw new \Exception('Error al generar PDF: ' . $e->getMessage());
        }
    }
    
    private function generateExcel(Reporte $reporte, array $data, string $fileName)
    {
        try {
            $fileName .= '.xlsx';
            
            $export = new ReporteExport($reporte, $data);
            Excel::store($export, $fileName, 'public');
            
            // Verificar que el archivo se creó correctamente
            if (!Storage::disk('public')->exists($fileName)) {
                throw new \Exception('El archivo Excel no se generó correctamente');
            }
            
            return $fileName;
        } catch (\Exception $e) {
            throw new \Exception('Error al generar Excel: ' . $e->getMessage());
        }
    }
    
    private function generateCSV(Reporte $reporte, array $data, string $fileName)
    {
        try {
            $fileName .= '.csv';
            
            $csvContent = $this->arrayToCsv($data);
            if (empty($csvContent)) {
                throw new \Exception('Error al generar el contenido CSV');
            }
            
            Storage::disk('public')->put($fileName, $csvContent);
            
            return $fileName;
        } catch (\Exception $e) {
            throw new \Exception('Error al generar CSV: ' . $e->getMessage());
        }
    }
    
    private function generateHTML(Reporte $reporte, array $data, string $fileName)
    {
        try {
            $fileName .= '.html';
            
            $html = view('reportes.html', [
                'reporte' => $reporte,
                'data' => $data
            ])->render();
            
            if (empty($html)) {
                throw new \Exception('Error al generar el contenido HTML');
            }
            
            Storage::disk('public')->put($fileName, $html);
            
            return $fileName;
        } catch (\Exception $e) {
            throw new \Exception('Error al generar HTML: ' . $e->getMessage());
        }
    }
    
    private function arrayToCsv(array $data)
    {
        if (empty($data)) {
            return '';
        }
        
        $output = fopen('php://temp', 'r+');
        
        // Escribir encabezados
        fputcsv($output, array_keys($data[0]));
        
        // Escribir datos
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}