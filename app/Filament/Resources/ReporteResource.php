<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReporteResource\Pages;
use App\Filament\Resources\ReporteResource\RelationManagers;
use App\Models\Reporte;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReporteResource extends Resource
{
    protected static ?string $model = Reporte::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre_reporte')
                    ->label('Nombre del Reporte')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('tipo_reporte')
                    ->label('Tipo de Reporte')
                    ->options([
                        'empleados' => 'Empleados',
                        'contratos' => 'Contratos',
                        'nomina' => 'Nómina',
                        'altas_bajas' => 'Altas y Bajas',
                        'vencimientos' => 'Vencimientos',
                        'presupuesto' => 'Presupuesto',
                        'estadisticas' => 'Estadísticas',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3),

                Forms\Components\Textarea::make('parametros')
                    ->label('Parámetros (JSON)')
                    ->rows(4)
                    ->helperText('Formato JSON con los parámetros del reporte'),

                Forms\Components\Textarea::make('columnas')
                    ->label('Columnas (JSON)')
                    ->rows(4)
                    ->helperText('Formato JSON con las columnas a mostrar'),

                Forms\Components\Select::make('formato')
                    ->label('Formato')
                    ->options([
                        'pdf' => 'PDF',
                        'excel' => 'Excel',
                        'csv' => 'CSV',
                        'html' => 'HTML',
                    ])
                    ->default('pdf')
                    ->required(),

                Forms\Components\TextInput::make('generado_por')
                    ->label('Generado Por')
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('fecha_generacion')
                    ->label('Fecha de Generación'),

                Forms\Components\TextInput::make('archivo_path')
                    ->label('Ruta del Archivo')
                    ->maxLength(500),

                Forms\Components\TextInput::make('total_registros')
                    ->label('Total de Registros')
                    ->numeric(),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'procesando' => 'Procesando',
                        'completado' => 'Completado',
                        'error' => 'Error',
                    ])
                    ->default('pendiente')
                    ->required(),

                Forms\Components\Textarea::make('mensaje_error')
                    ->label('Mensaje de Error')
                    ->rows(3),

                Forms\Components\Toggle::make('es_publico')
                    ->label('Es Público')
                    ->default(false),

                Forms\Components\DatePicker::make('fecha_desde')
                    ->label('Fecha Desde'),

                Forms\Components\DatePicker::make('fecha_hasta')
                    ->label('Fecha Hasta'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre_reporte')
                    ->label('Nombre del Reporte')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo_reporte')
                    ->label('Tipo de Reporte')
                    ->colors([
                        'primary' => 'empleados',
                        'success' => 'contratos',
                        'warning' => 'nomina',
                        'secondary' => 'altas_bajas',
                        'danger' => 'vencimientos',
                        'info' => 'presupuesto',
                        'gray' => 'estadisticas',
                    ]),

                Tables\Columns\BadgeColumn::make('formato')
                    ->label('Formato')
                    ->colors([
                        'danger' => 'pdf',
                        'success' => 'excel',
                        'warning' => 'csv',
                        'primary' => 'html',
                    ]),

                Tables\Columns\TextColumn::make('generado_por')
                    ->label('Generado Por')
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_generacion')
                    ->label('Fecha Generación')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_registros')
                    ->label('Total Registros')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'primary' => 'procesando',
                        'success' => 'completado',
                        'danger' => 'error',
                    ]),

                Tables\Columns\IconColumn::make('es_publico')
                    ->label('Público')
                    ->boolean(),

                Tables\Columns\TextColumn::make('fecha_desde')
                    ->label('Desde')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_hasta')
                    ->label('Hasta')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_reporte')
                    ->options([
                        'empleados' => 'Empleados',
                        'contratos' => 'Contratos',
                        'nomina' => 'Nómina',
                        'altas_bajas' => 'Altas y Bajas',
                        'vencimientos' => 'Vencimientos',
                        'presupuesto' => 'Presupuesto',
                        'estadisticas' => 'Estadísticas',
                    ]),
                Tables\Filters\SelectFilter::make('formato')
                    ->options([
                        'pdf' => 'PDF',
                        'excel' => 'Excel',
                        'csv' => 'CSV',
                        'html' => 'HTML',
                    ]),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'procesando' => 'Procesando',
                        'completado' => 'Completado',
                        'error' => 'Error',
                    ]),
                Tables\Filters\TernaryFilter::make('es_publico')
                    ->label('Es Público'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn ($record) => $record->estado === 'completado' && $record->archivo_path)
                    ->url(fn ($record) => route('reportes.download', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('generate')
                    ->label('Generar')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('primary')
                    ->visible(fn ($record) => in_array($record->estado, ['pendiente', 'error']))
                    ->action(function ($record) {
                        try {
                            $record->update(['estado' => 'generando']);
                            
                            // Usar el servicio directamente para mejor manejo de errores
                            $generator = new \App\Services\ReportGeneratorService();
                            $filePath = $generator->generate($record);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Reporte generado exitosamente')
                                ->body('El reporte se ha generado y está listo para descargar.')
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error al generar reporte')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReportes::route('/'),
            'create' => Pages\CreateReporte::route('/create'),
            'edit' => Pages\EditReporte::route('/{record}/edit'),
        ];
    }
}
