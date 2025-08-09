<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermisoResource\Pages;
use App\Filament\Resources\PermisoResource\RelationManagers;
use App\Models\Permiso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermisoResource extends Resource
{
    protected static ?string $model = Permiso::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Permiso')
                    ->schema([
                        Forms\Components\Select::make('empleado_id')
                            ->label('Empleado')
                            ->relationship('empleado', 'nombres')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nombres} {$record->apellidos} - {$record->codigo_empleado}"),
                        
                        Forms\Components\Select::make('tipo_permiso')
                            ->label('Tipo de Permiso')
                            ->options([
                                'vacaciones' => 'Vacaciones',
                                'enfermedad' => 'Enfermedad',
                                'personal' => 'Personal',
                                'maternidad' => 'Maternidad',
                                'paternidad' => 'Paternidad',
                                'estudio' => 'Estudio',
                                'duelo' => 'Duelo',
                                'matrimonio' => 'Matrimonio',
                                'emergencia' => 'Emergencia',
                                'otro' => 'Otro',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'aprobado' => 'Aprobado',
                                'rechazado' => 'Rechazado',
                                'cancelado' => 'Cancelado',
                            ])
                            ->required()
                            ->default('pendiente'),
                        
                        Forms\Components\Toggle::make('con_goce_salario')
                            ->label('Con Goce de Salario')
                            ->default(true),
                    ])->columns(2),
                
                Forms\Components\Section::make('Fechas del Permiso')
                    ->schema([
                        Forms\Components\DatePicker::make('fecha_inicio')
                            ->label('Fecha de Inicio')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                $fechaFin = $get('fecha_fin');
                                if ($state && $fechaFin) {
                                    $inicio = \Carbon\Carbon::parse($state);
                                    $fin = \Carbon\Carbon::parse($fechaFin);
                                    $dias = $inicio->diffInDays($fin) + 1;
                                    $set('dias_solicitados', $dias);
                                }
                            }),
                        
                        Forms\Components\DatePicker::make('fecha_fin')
                            ->label('Fecha de Fin')
                            ->required()
                            ->afterOrEqual('fecha_inicio')
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                $fechaInicio = $get('fecha_inicio');
                                if ($fechaInicio && $state) {
                                    $inicio = \Carbon\Carbon::parse($fechaInicio);
                                    $fin = \Carbon\Carbon::parse($state);
                                    $dias = $inicio->diffInDays($fin) + 1;
                                    $set('dias_solicitados', $dias);
                                }
                            }),
                        
                        Forms\Components\TextInput::make('dias_solicitados')
                            ->label('Días Solicitados')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->suffix('días'),
                        
                        Forms\Components\DatePicker::make('fecha_solicitud')
                            ->label('Fecha de Solicitud')
                            ->required()
                            ->default(now()),
                    ])->columns(2),
                
                Forms\Components\Section::make('Aprobación')
                    ->schema([
                        Forms\Components\DatePicker::make('fecha_aprobacion')
                            ->label('Fecha de Aprobación')
                            ->visible(fn (callable $get) => in_array($get('estado'), ['aprobado', 'rechazado'])),
                        
                        Forms\Components\TextInput::make('aprobado_por')
                            ->label('Aprobado Por')
                            ->maxLength(100)
                            ->visible(fn (callable $get) => in_array($get('estado'), ['aprobado', 'rechazado'])),
                    ])->columns(2),
                
                Forms\Components\Section::make('Motivos y Observaciones')
                    ->schema([
                        Forms\Components\Textarea::make('motivo')
                            ->label('Motivo del Permiso')
                            ->required()
                            ->rows(3)
                            ->maxLength(500),
                        
                        Forms\Components\Textarea::make('observaciones_empleado')
                            ->label('Observaciones del Empleado')
                            ->rows(2)
                            ->maxLength(500),
                        
                        Forms\Components\Textarea::make('observaciones_supervisor')
                            ->label('Observaciones del Supervisor')
                            ->rows(2)
                            ->maxLength(500)
                            ->visible(fn (callable $get) => in_array($get('estado'), ['aprobado', 'rechazado'])),
                        
                        Forms\Components\FileUpload::make('documento_adjunto')
                            ->label('Documento Adjunto')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->maxSize(5120) // 5MB
                            ->directory('permisos'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('empleado.nombres')
                    ->label('Empleado')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => "{$record->empleado->nombres} {$record->empleado->apellidos}"),
                
                Tables\Columns\TextColumn::make('empleado.codigo_empleado')
                    ->label('Código')
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('tipo_permiso')
                    ->label('Tipo')
                    ->colors([
                        'success' => 'vacaciones',
                        'danger' => 'enfermedad',
                        'warning' => 'personal',
                        'info' => 'maternidad',
                        'primary' => 'paternidad',
                        'secondary' => ['estudio', 'duelo', 'matrimonio', 'emergencia', 'otro'],
                    ]),
                
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('dias_solicitados')
                    ->label('Días')
                    ->suffix(' días')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobado',
                        'danger' => 'rechazado',
                        'secondary' => 'cancelado',
                    ]),
                
                Tables\Columns\IconColumn::make('con_goce_salario')
                    ->label('Con Goce')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('fecha_solicitud')
                    ->label('Solicitado')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('aprobado_por')
                    ->label('Aprobado Por')
                    ->limit(20)
                    ->placeholder('N/A'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_permiso')
                    ->label('Tipo de Permiso')
                    ->options([
                        'vacaciones' => 'Vacaciones',
                        'enfermedad' => 'Enfermedad',
                        'personal' => 'Personal',
                        'maternidad' => 'Maternidad',
                        'paternidad' => 'Paternidad',
                        'estudio' => 'Estudio',
                        'duelo' => 'Duelo',
                        'matrimonio' => 'Matrimonio',
                        'emergencia' => 'Emergencia',
                        'otro' => 'Otro',
                    ]),
                
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                        'cancelado' => 'Cancelado',
                    ]),
                
                Tables\Filters\TernaryFilter::make('con_goce_salario')
                    ->label('Con Goce de Salario')
                    ->boolean()
                    ->trueLabel('Con goce')
                    ->falseLabel('Sin goce')
                    ->placeholder('Todos'),
                
                Tables\Filters\SelectFilter::make('empleado')
                    ->label('Empleado')
                    ->relationship('empleado', 'nombres')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\Filter::make('fecha_inicio')
                    ->form([
                        Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_inicio', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_inicio', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha_solicitud', 'desc');
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
            'index' => Pages\ListPermisos::route('/'),
            'create' => Pages\CreatePermiso::route('/create'),
            'edit' => Pages\EditPermiso::route('/{record}/edit'),
        ];
    }
}
