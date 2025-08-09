<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadoRenglonResource\Pages;
use App\Filament\Resources\EmpleadoRenglonResource\RelationManagers;
use App\Models\EmpleadoRenglon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmpleadoRenglonResource extends Resource
{
    protected static ?string $model = EmpleadoRenglon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('empleado_id')
                    ->label('Empleado')
                    ->relationship('empleado', 'nombres')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('numero_renglon')
                    ->label('Número de Renglón')
                    ->required()
                    ->maxLength(50),

                Forms\Components\Select::make('tipo_renglon')
                    ->label('Tipo de Renglón')
                    ->options([
                        '011' => '011 - Personal Permanente',
                        '021' => '021 - Personal Supernumerario',
                        '022' => '022 - Personal por Contrato',
                        '029' => '029 - Otras Remuneraciones',
                        '031' => '031 - Jornales',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('descripcion_renglon')
                    ->label('Descripción del Renglón')
                    ->rows(2),

                Forms\Components\TextInput::make('salario_renglon')
                    ->label('Salario del Renglón')
                    ->numeric()
                    ->prefix('Q')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_asignacion')
                    ->label('Fecha de Asignación')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_vigencia_inicio')
                    ->label('Fecha Inicio Vigencia')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_vigencia_fin')
                    ->label('Fecha Fin Vigencia'),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'suspendido' => 'Suspendido',
                        'finalizado' => 'Finalizado',
                    ])
                    ->default('activo')
                    ->required(),

                Forms\Components\TextInput::make('dependencia')
                    ->label('Dependencia')
                    ->maxLength(255),

                Forms\Components\TextInput::make('unidad_ejecutora')
                    ->label('Unidad Ejecutora')
                    ->maxLength(255),

                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('empleado.nombres')
                    ->label('Empleado')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('numero_renglon')
                    ->label('Número de Renglón')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo_renglon')
                    ->label('Tipo de Renglón')
                    ->colors([
                        'primary' => '011',
                        'success' => '021',
                        'warning' => '022',
                        'secondary' => '029',
                        'danger' => '031',
                    ]),

                Tables\Columns\TextColumn::make('salario_renglon')
                    ->label('Salario')
                    ->money('GTQ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_asignacion')
                    ->label('Fecha Asignación')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_vigencia_inicio')
                    ->label('Vigencia Inicio')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_vigencia_fin')
                    ->label('Vigencia Fin')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'success' => 'activo',
                        'danger' => 'inactivo',
                        'warning' => 'suspendido',
                        'secondary' => 'finalizado',
                    ]),

                Tables\Columns\TextColumn::make('dependencia')
                    ->label('Dependencia')
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_renglon')
                    ->options([
                        '011' => '011 - Personal Permanente',
                        '021' => '021 - Personal Supernumerario',
                        '022' => '022 - Personal por Contrato',
                        '029' => '029 - Otras Remuneraciones',
                        '031' => '031 - Jornales',
                    ]),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'suspendido' => 'Suspendido',
                        'finalizado' => 'Finalizado',
                    ]),
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
            'index' => Pages\ListEmpleadoRenglons::route('/'),
            'create' => Pages\CreateEmpleadoRenglon::route('/create'),
            'edit' => Pages\EditEmpleadoRenglon::route('/{record}/edit'),
        ];
    }
}
