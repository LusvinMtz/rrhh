<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AltaBajaResource\Pages;
use App\Filament\Resources\AltaBajaResource\RelationManagers;
use App\Models\AltaBaja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AltaBajaResource extends Resource
{
    protected static ?string $model = AltaBaja::class;

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

                Forms\Components\Select::make('tipo_movimiento')
                    ->label('Tipo de Movimiento')
                    ->options([
                        'alta' => 'Alta',
                        'baja' => 'Baja',
                        'traslado' => 'Traslado',
                        'suspension' => 'Suspensión',
                        'reintegro' => 'Reintegro',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('fecha_movimiento')
                    ->label('Fecha de Movimiento')
                    ->required(),

                Forms\Components\TextInput::make('motivo')
                    ->label('Motivo')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3),

                Forms\Components\TextInput::make('documento_soporte')
                    ->label('Documento de Soporte')
                    ->maxLength(255),

                Forms\Components\TextInput::make('autorizado_por')
                    ->label('Autorizado Por')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('fecha_efectiva')
                    ->label('Fecha Efectiva')
                    ->required(),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobado' => 'Aprobado',
                        'ejecutado' => 'Ejecutado',
                        'cancelado' => 'Cancelado',
                    ])
                    ->default('pendiente')
                    ->required(),

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

                Tables\Columns\BadgeColumn::make('tipo_movimiento')
                    ->label('Tipo de Movimiento')
                    ->colors([
                        'success' => 'alta',
                        'danger' => 'baja',
                        'warning' => 'traslado',
                        'secondary' => 'suspension',
                        'primary' => 'reintegro',
                    ]),

                Tables\Columns\TextColumn::make('fecha_movimiento')
                    ->label('Fecha de Movimiento')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('motivo')
                    ->label('Motivo')
                    ->limit(50),

                Tables\Columns\TextColumn::make('autorizado_por')
                    ->label('Autorizado Por')
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_efectiva')
                    ->label('Fecha Efectiva')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobado',
                        'primary' => 'ejecutado',
                        'danger' => 'cancelado',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_movimiento')
                    ->options([
                        'alta' => 'Alta',
                        'baja' => 'Baja',
                        'traslado' => 'Traslado',
                        'suspension' => 'Suspensión',
                        'reintegro' => 'Reintegro',
                    ]),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobado' => 'Aprobado',
                        'ejecutado' => 'Ejecutado',
                        'cancelado' => 'Cancelado',
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
            'index' => Pages\ListAltaBajas::route('/'),
            'create' => Pages\CreateAltaBaja::route('/create'),
            'edit' => Pages\EditAltaBaja::route('/{record}/edit'),
        ];
    }
}
