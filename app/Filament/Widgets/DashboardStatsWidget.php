<?php

namespace App\Filament\Widgets;

use App\Models\Empleado;
use App\Models\Contrato;
use App\Models\AltaBaja;
use App\Models\AlertaVencimiento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Empleados', Empleado::count())
                ->description('Empleados registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Empleados Activos', Empleado::where('estado', 'activo')->count())
                ->description('Empleados en servicio')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),

            Stat::make('Contratos Vigentes', Contrato::where('estado', 'vigente')->count())
                ->description('Contratos activos')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Alertas Pendientes', AlertaVencimiento::where('estado', 'pendiente')->count())
                ->description('Alertas por revisar')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),

            Stat::make('Altas del Mes', AltaBaja::where('tipo_movimiento', 'alta')
                ->whereMonth('fecha_movimiento', now()->month)
                ->whereYear('fecha_movimiento', now()->year)
                ->count())
                ->description('Nuevos ingresos')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Bajas del Mes', AltaBaja::where('tipo_movimiento', 'baja')
                ->whereMonth('fecha_movimiento', now()->month)
                ->whereYear('fecha_movimiento', now()->year)
                ->count())
                ->description('Empleados dados de baja')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
}