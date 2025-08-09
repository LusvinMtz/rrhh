<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadoResource\Pages;
use App\Filament\Resources\EmpleadoResource\RelationManagers;
use App\Models\Empleado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpleadoResource extends Resource
{
    protected static ?string $model = Empleado::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        Forms\Components\TextInput::make('codigo_empleado')
                            ->label('Código de Empleado')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),
                        
                        Forms\Components\TextInput::make('nombres')
                            ->label('Nombres')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('apellidos')
                            ->label('Apellidos')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('dpi')
                            ->label('DPI')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(13),
                        
                        Forms\Components\TextInput::make('nit')
                            ->label('NIT')
                            ->maxLength(12),
                        
                        Forms\Components\DatePicker::make('fecha_nacimiento')
                            ->label('Fecha de Nacimiento')
                            ->required(),
                        
                        Forms\Components\Select::make('genero')
                            ->label('Género')
                            ->options([
                                'M' => 'Masculino',
                                'F' => 'Femenino',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('estado_civil')
                            ->label('Estado Civil')
                            ->options([
                                'soltero' => 'Soltero/a',
                                'casado' => 'Casado/a',
                                'divorciado' => 'Divorciado/a',
                                'viudo' => 'Viudo/a',
                                'union_libre' => 'Unión Libre',
                            ])
                            ->required(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Información de Contacto')
                    ->schema([
                        Forms\Components\TextInput::make('telefono')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(15),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),
                        
                        Forms\Components\Textarea::make('direccion')
                            ->label('Dirección')
                            ->rows(2)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('departamento')
                            ->label('Departamento')
                            ->maxLength(50),
                        
                        Forms\Components\TextInput::make('municipio')
                            ->label('Municipio')
                            ->maxLength(50),
                    ])->columns(2),
                
                Forms\Components\Section::make('Información Laboral')
                    ->schema([
                        Forms\Components\TextInput::make('puesto')
                            ->label('Puesto')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('area_trabajo')
                            ->label('Área de Trabajo')
                            ->required()
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('salario_base')
                            ->label('Salario Base')
                            ->numeric()
                            ->prefix('Q')
                            ->required(),
                        
                        Forms\Components\DatePicker::make('fecha_ingreso')
                            ->label('Fecha de Ingreso')
                            ->required(),
                        
                        Forms\Components\Select::make('tipo_contrato')
                            ->label('Tipo de Contrato')
                            ->options([
                                'indefinido' => 'Indefinido',
                                'temporal' => 'Temporal',
                                'por_obra' => 'Por Obra',
                                'practicas' => 'Prácticas',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'activo' => 'Activo',
                                'inactivo' => 'Inactivo',
                                'suspendido' => 'Suspendido',
                                'retirado' => 'Retirado',
                            ])
                            ->required()
                            ->default('activo'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Información de Seguridad Social')
                    ->schema([
                        Forms\Components\TextInput::make('numero_igss')
                            ->label('Número IGSS')
                            ->maxLength(15),
                        
                        Forms\Components\TextInput::make('numero_irtra')
                            ->label('Número IRTRA')
                            ->maxLength(15),
                    ])->columns(2),
                
                Forms\Components\Section::make('Contacto de Emergencia')
                    ->schema([
                        Forms\Components\TextInput::make('contacto_emergencia_nombre')
                            ->label('Nombre del Contacto')
                            ->maxLength(100),
                        
                        Forms\Components\TextInput::make('contacto_emergencia_telefono')
                            ->label('Teléfono del Contacto')
                            ->tel()
                            ->maxLength(15),
                        
                        Forms\Components\TextInput::make('contacto_emergencia_relacion')
                            ->label('Relación')
                            ->maxLength(50),
                    ])->columns(2),
                
                Forms\Components\Section::make('Observaciones')
                    ->schema([
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
                Tables\Columns\TextColumn::make('codigo_empleado')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('nombres')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('apellidos')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('puesto')
                    ->label('Puesto')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('area_trabajo')
                    ->label('Área')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('salario_base')
                    ->label('Salario')
                    ->money('GTQ')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'success' => 'activo',
                        'warning' => 'suspendido',
                        'danger' => 'inactivo',
                        'secondary' => 'retirado',
                    ]),
                
                Tables\Columns\TextColumn::make('fecha_ingreso')
                    ->label('Fecha Ingreso')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('contratos_count')
                    ->label('Contratos')
                    ->counts('contratos')
                    ->badge(),
                
                Tables\Columns\IconColumn::make('contrato_proximo_vencer')
                    ->label('Alerta Contrato')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('')
                    ->trueColor('warning')
                    ->getStateUsing(function ($record) {
                        $contratoActivo = $record->contratos()
                            ->where('estado', 'activo')
                            ->whereNotNull('fecha_fin')
                            ->orderBy('fecha_fin', 'asc')
                            ->first();
                        
                        if (!$contratoActivo) {
                            return false;
                        }
                        
                        $diasRestantes = now()->diffInDays($contratoActivo->fecha_fin, false);
                        return $diasRestantes <= 15 && $diasRestantes >= 0;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'suspendido' => 'Suspendido',
                        'retirado' => 'Retirado',
                    ]),
                
                Tables\Filters\SelectFilter::make('tipo_contrato')
                    ->label('Tipo de Contrato')
                    ->options([
                        'indefinido' => 'Indefinido',
                        'temporal' => 'Temporal',
                        'por_obra' => 'Por Obra',
                        'practicas' => 'Prácticas',
                    ]),
                
                Tables\Filters\SelectFilter::make('area_trabajo')
                    ->label('Área de Trabajo')
                    ->options(function () {
                        return \App\Models\Empleado::distinct()
                            ->pluck('area_trabajo', 'area_trabajo')
                            ->filter()
                            ->toArray();
                    }),
                
                Tables\Filters\Filter::make('contrato_por_vencer')
                    ->label('Contrato por vencer (15 días)')
                    ->query(function (Builder $query): Builder {
                        return $query->whereHas('contratos', function (Builder $query) {
                            $query->where('estado', 'activo')
                                ->whereNotNull('fecha_fin')
                                ->whereBetween('fecha_fin', [now(), now()->addDays(15)]);
                        });
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
            ->recordClasses(function ($record) {
                $contratoActivo = $record->contratos()
                    ->where('estado', 'activo')
                    ->whereNotNull('fecha_fin')
                    ->orderBy('fecha_fin', 'asc')
                    ->first();
                
                if (!$contratoActivo) {
                    return null;
                }
                
                $diasRestantes = now()->diffInDays($contratoActivo->fecha_fin, false);
                
                if ($diasRestantes <= 15 && $diasRestantes >= 0) {
                    return 'bg-yellow-50 border-l-4 border-yellow-400';
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
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleado::route('/create'),
            'edit' => Pages\EditEmpleado::route('/{record}/edit'),
        ];
    }
}
