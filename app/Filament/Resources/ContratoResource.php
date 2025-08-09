<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContratoResource\Pages;
use App\Filament\Resources\ContratoResource\RelationManagers;
use App\Models\Contrato;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContratoResource extends Resource
{
    protected static ?string $model = Contrato::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Contrato')
                    ->schema([
                        Forms\Components\Select::make('empleado_id')
                            ->label('Empleado')
                            ->relationship('empleado', 'nombres')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nombres} {$record->apellidos} - {$record->codigo_empleado}"),
                        
                        Forms\Components\TextInput::make('numero_contrato')
                            ->label('Número de Contrato')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),
                        
                        Forms\Components\Select::make('tipo_contrato')
                            ->label('Tipo de Contrato')
                            ->options([
                                'indefinido' => 'Indefinido',
                                'temporal' => 'Temporal',
                                'por_obra' => 'Por Obra',
                                'practicas' => 'Prácticas',
                                'consultoria' => 'Consultoría',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'activo' => 'Activo',
                                'finalizado' => 'Finalizado',
                                'suspendido' => 'Suspendido',
                                'cancelado' => 'Cancelado',
                            ])
                            ->required()
                            ->default('activo'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Fechas del Contrato')
                    ->schema([
                        Forms\Components\DatePicker::make('fecha_inicio')
                            ->label('Fecha de Inicio')
                            ->required(),
                        
                        Forms\Components\DatePicker::make('fecha_fin')
                            ->label('Fecha de Fin')
                            ->afterOrEqual('fecha_inicio'),
                        
                        Forms\Components\DatePicker::make('fecha_firma')
                            ->label('Fecha de Firma')
                            ->required(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Información del Puesto')
                    ->schema([
                        Forms\Components\TextInput::make('puesto')
                            ->label('Puesto')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('area_trabajo')
                            ->label('Área de Trabajo')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('lugar_trabajo')
                            ->label('Lugar de Trabajo')
                            ->maxLength(100),
                        
                        Forms\Components\Textarea::make('descripcion_puesto')
                            ->label('Descripción del Puesto')
                            ->rows(3)
                            ->maxLength(500),
                    ])->columns(2),
                
                Forms\Components\Section::make('Condiciones Laborales')
                    ->schema([
                        Forms\Components\TextInput::make('salario')
                            ->label('Salario')
                            ->numeric()
                            ->prefix('Q')
                            ->required(),
                        
                        Forms\Components\Select::make('jornada_laboral')
                            ->label('Jornada Laboral')
                            ->options([
                                'completa' => 'Tiempo Completo',
                                'parcial' => 'Tiempo Parcial',
                                'flexible' => 'Horario Flexible',
                                'nocturna' => 'Jornada Nocturna',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('horas_semanales')
                            ->label('Horas Semanales')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(48)
                            ->suffix('horas'),
                        
                        Forms\Components\Textarea::make('beneficios')
                            ->label('Beneficios')
                            ->rows(2)
                            ->maxLength(500),
                    ])->columns(2),
                
                Forms\Components\Section::make('Cláusulas y Observaciones')
                    ->schema([
                        Forms\Components\Textarea::make('clausulas_especiales')
                            ->label('Cláusulas Especiales')
                            ->rows(3)
                            ->maxLength(1000),
                        
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(3)
                            ->maxLength(500),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_contrato')
                    ->label('Número')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('empleado.nombres')
                    ->label('Empleado')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => "{$record->empleado->nombres} {$record->empleado->apellidos}"),
                
                Tables\Columns\TextColumn::make('empleado.codigo_empleado')
                    ->label('Código Empleado')
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('tipo_contrato')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'indefinido',
                        'warning' => 'temporal',
                        'info' => 'por_obra',
                        'secondary' => 'practicas',
                        'success' => 'consultoria',
                    ]),
                
                Tables\Columns\TextColumn::make('puesto')
                    ->label('Puesto')
                    ->searchable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('salario')
                    ->label('Salario')
                    ->money('GTQ')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('Indefinido'),
                
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'success' => 'activo',
                        'secondary' => 'finalizado',
                        'warning' => 'suspendido',
                        'danger' => 'cancelado',
                    ]),
                
                Tables\Columns\IconColumn::make('proximo_vencer')
                    ->label('Alerta')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('')
                    ->trueColor('warning')
                    ->getStateUsing(function ($record) {
                        if (!$record->fecha_fin || $record->estado !== 'activo') {
                            return false;
                        }
                        
                        $diasRestantes = now()->diffInDays($record->fecha_fin, false);
                        return $diasRestantes <= 15 && $diasRestantes >= 0;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_contrato')
                    ->label('Tipo de Contrato')
                    ->options([
                        'indefinido' => 'Indefinido',
                        'temporal' => 'Temporal',
                        'por_obra' => 'Por Obra',
                        'practicas' => 'Prácticas',
                        'consultoria' => 'Consultoría',
                    ]),
                
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'finalizado' => 'Finalizado',
                        'suspendido' => 'Suspendido',
                        'cancelado' => 'Cancelado',
                    ]),
                
                Tables\Filters\Filter::make('proximo_vencer')
                    ->label('Próximo a vencer (15 días)')
                    ->query(function (Builder $query): Builder {
                        return $query->where('estado', 'activo')
                            ->whereNotNull('fecha_fin')
                            ->whereBetween('fecha_fin', [now(), now()->addDays(15)]);
                    }),
                
                Tables\Filters\Filter::make('vencidos')
                    ->label('Vencidos')
                    ->query(function (Builder $query): Builder {
                        return $query->where('estado', 'activo')
                            ->whereNotNull('fecha_fin')
                            ->where('fecha_fin', '<', now());
                    }),
                
                Tables\Filters\SelectFilter::make('empleado')
                    ->label('Empleado')
                    ->relationship('empleado', 'nombres')
                    ->searchable()
                    ->preload(),
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
            ->recordClasses(function ($record) {
                if (!$record->fecha_fin || $record->estado !== 'activo') {
                    return null;
                }
                
                $diasRestantes = now()->diffInDays($record->fecha_fin, false);
                
                if ($diasRestantes < 0) {
                    return 'bg-red-50 border-l-4 border-red-400'; // Vencido
                } elseif ($diasRestantes <= 15) {
                    return 'bg-yellow-50 border-l-4 border-yellow-400'; // Próximo a vencer
                }
                
                return null;
            });
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
            'index' => Pages\ListContratos::route('/'),
            'create' => Pages\CreateContrato::route('/create'),
            'edit' => Pages\EditContrato::route('/{record}/edit'),
        ];
    }
}
