<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reporte->nombre_reporte }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #333;
            margin: 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $reporte->nombre_reporte }}</h1>
    </div>
    
    <div class="info">
        <div class="info-item"><strong>Tipo de Reporte:</strong> {{ ucfirst(str_replace('_', ' ', $reporte->tipo_reporte)) }}</div>
        <div class="info-item"><strong>Descripción:</strong> {{ $reporte->descripcion }}</div>
        <div class="info-item"><strong>Fecha de Generación:</strong> {{ $reporte->fecha_generacion->format('d/m/Y H:i:s') }}</div>
        <div class="info-item"><strong>Total de Registros:</strong> {{ count($data) }}</div>
        @if($reporte->fecha_desde)
            <div class="info-item"><strong>Fecha Desde:</strong> {{ $reporte->fecha_desde->format('d/m/Y') }}</div>
        @endif
        @if($reporte->fecha_hasta)
            <div class="info-item"><strong>Fecha Hasta:</strong> {{ $reporte->fecha_hasta->format('d/m/Y') }}</div>
        @endif
    </div>
    
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    @php
                        $columnas = json_decode($reporte->columnas, true) ?? array_keys($data[0]);
                    @endphp
                    @foreach($columnas as $columna)
                        <th>{{ ucfirst(str_replace('_', ' ', $columna)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($columnas as $columna)
                            <td>
                                @if(is_array($row))
                                    {{ $row[$columna] ?? '' }}
                                @else
                                    {{ $row->$columna ?? '' }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No se encontraron datos para mostrar.</p>
    @endif
    
    <div class="footer">
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }} - Sistema de Gestión de Empleados</p>
    </div>
</body>
</html>