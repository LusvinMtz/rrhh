<?php

namespace App\Exports;

use App\Models\Reporte;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

class ReporteExport implements FromCollection, WithHeadings, WithTitle
{
    protected $reporte;
    protected $data;
    
    public function __construct(Reporte $reporte, array $data)
    {
        $this->reporte = $reporte;
        $this->data = $data;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        if (empty($this->data)) {
            return [];
        }
        
        $columnas = $this->reporte->columnas;
        
        if ($columnas) {
            // Ensure columnas is always an array
            if (is_string($columnas)) {
                $columnas = json_decode($columnas, true) ?? [];
            }
            if (is_array($columnas) && !empty($columnas)) {
                return $columnas;
            }
        }
        
        // Si no hay columnas definidas, usar las claves del primer registro
        return array_keys($this->data[0]);
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        return $this->reporte->nombre_reporte;
    }
}
