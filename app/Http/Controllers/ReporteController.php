<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ReporteController extends Controller
{
    public function download($id)
    {
        $reporte = Reporte::findOrFail($id);
        
        // Verificar que el reporte esté completado y tenga archivo
        if ($reporte->estado !== 'completado' || !$reporte->archivo_path) {
            abort(404, 'Reporte no disponible para descarga');
        }
        
        // Verificar que el archivo existe
        if (!Storage::disk('public')->exists($reporte->archivo_path)) {
            abort(404, 'Archivo no encontrado');
        }
        
        $filePath = Storage::disk('public')->path($reporte->archivo_path);
        $fileName = $reporte->nombre_reporte . '.' . $this->getFileExtension($reporte->formato);
        
        return response()->download($filePath, $fileName);
    }
    
    private function getFileExtension($formato)
    {
        return match($formato) {
            'pdf' => 'pdf',
            'excel' => 'xlsx',
            'csv' => 'csv',
            'html' => 'html',
            default => 'txt'
        };
    }
}