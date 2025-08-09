<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContratoRenglonResource\Pages;
use App\Filament\Resources\ContratoRenglonResource\RelationManagers;
use App\Models\ContratoRenglon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContratoRenglonResource extends Resource
{
    protected static ?string $model = ContratoRenglon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('empleado_renglon_id')
                    ->label('Empleado Renglón')
                    ->relationship('empleadoRenglon', 'numero_renglon')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('numero_contrato_renglon')
                    ->label('Número de Contrato Renglón')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Select::make('tipo_contrato')
                    ->label('Tipo de Contrato')
                    ->options([
                        'servicios_profesionales' => 'Servicios Profesionales',
                        'servicios_tecnicos' => 'Servicios Técnicos',
                        'consultoria' => 'Consultoría',
                        'obra' => 'Obra',
                        'suministro' => 'Suministro',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->required(),

                Forms\Components\TextInput::make('monto_total')
                    ->label('Monto Total')
                    ->numeric()
                    ->prefix('Q')
                    ->required(),

                Forms\Components\TextInput::make('monto_mensual')
                    ->label('Monto Mensual')
                    ->numeric()
                    ->prefix('Q'),

                Forms\Components\Textarea::make('objeto_contrato')
                    ->label('Objeto del Contrato')
                    ->required()
                    ->rows(3),

                Forms\Components\Textarea::make('productos_entregables')
                    ->label('Productos Entregables')
                    ->rows(3),

                Forms\Components\TextInput::make('fuente_financiamiento')
                    ->label('Fuente de Financiamiento')
                    ->maxLength(255),

                Forms\Components\TextInput::make('programa_presupuestario')
                    ->label('Programa Presupuestario')
                    ->maxLength(255),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'borrador' => 'Borrador',
                        'vigente' => 'Vigente',
                        'finalizado' => 'Finalizado',
                        'rescindido' => 'Rescindido',
                        'suspendido' => 'Suspendido',
                    ])
                    ->default('borrador')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_firma')
                    ->label('Fecha de Firma'),

                Forms\Components\TextInput::make('supervisor_contrato')
                    ->label('Supervisor del Contrato')
                    ->maxLength(255),

                Forms\Components\Textarea::make('clausulas_especiales')
                    ->label('Cláusulas Especiales')
                    ->rows(3),

                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('empleadoRenglon.numero_renglon')
                    ->label('Renglón')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('numero_contrato_renglon')
                    ->label('Número de Contrato')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo_contrato')
                    ->label('Tipo de Contrato')
                    ->colors([
                        'primary' => 'servicios_profesionales',
                        'success' => 'servicios_tecnicos',
                        'warning' => 'consultoria',
                        'secondary' => 'obra',
                        'danger' => 'suministro',
                    ]),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Fecha Inicio')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha Fin')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('monto_total')
                    ->label('Monto Total')
                    ->money('GTQ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('monto_mensual')
                    ->label('Monto Mensual')
                    ->money('GTQ')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'secondary' => 'borrador',
                        'success' => 'vigente',
                        'primary' => 'finalizado',
                        'danger' => 'rescindido',
                        'warning' => 'suspendido',
                    ]),

                Tables\Columns\TextColumn::make('supervisor_contrato')
                    ->label('Supervisor')
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_contrato')
                    ->options([
                        'servicios_profesionales' => 'Servicios Profesionales',
                        'servicios_tecnicos' => 'Servicios Técnicos',
                        'consultoria' => 'Consultoría',
                        'obra' => 'Obra',
                        'suministro' => 'Suministro',
                    ]),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'borrador' => 'Borrador',
                        'vigente' => 'Vigente',
                        'finalizado' => 'Finalizado',
                        'rescindido' => 'Rescindido',
                        'suspendido' => 'Suspendido',
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
            'index' => Pages\ListContratoRenglons::route('/'),
            'create' => Pages\CreateContratoRenglon::route('/create'),
            'edit' => Pages\EditContratoRenglon::route('/{record}/edit'),
        ];
    }
}
