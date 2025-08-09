<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $reporte->nombre_reporte }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #333;
            margin: 0;
            font-size: 2.5em;
        }
        .info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-item strong {
            color: #495057;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 0.9em;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-size: 1.2em;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9em;
        }
        @media print {
            body {
                background-color: white;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
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
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">{{ count($data) }}</div>
                    <div class="stat-label">Registros</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $reporte->formato }}</div>
                    <div class="stat-label">Formato</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $reporte->fecha_generacion->format('H:i') }}</div>
                    <div class="stat-label">Hora</div>
                </div>
            </div>
            
            <div class="table-container">
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
            </div>
        @else
            <div class="no-data">
                <p>No se encontraron datos para mostrar en este reporte.</p>
            </div>
        @endif
        
        <div class="footer">
            <p>Generado el {{ now()->format('d/m/Y H:i:s') }} - Sistema de Gestión de Empleados Sansare</p>
            <p>Este reporte contiene información confidencial y debe ser tratado de acuerdo a las políticas de la empresa.</p>
        </div>
    </div>
    
    <script>
        // Función para imprimir el reporte
        function printReport() {
            window.print();
        }
        
        // Agregar botón de impresión si no estamos en modo impresión
        if (!window.matchMedia('print').matches) {
            const printButton = document.createElement('button');
            printButton.textContent = 'Imprimir Reporte';
            printButton.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                z-index: 1000;
            `;
            printButton.onclick = printReport;
            document.body.appendChild(printButton);
        }
    </script>
</body>
</html>