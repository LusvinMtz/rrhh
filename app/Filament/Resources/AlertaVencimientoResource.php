<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertaVencimientoResource\Pages;
use App\Filament\Resources\AlertaVencimientoResource\RelationManagers;
use App\Models\AlertaVencimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlertaVencimientoResource extends Resource
{
    protected static ?string $model = AlertaVencimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('alertable_type')
                    ->label('Tipo de Entidad')
                    ->options([
                        'App\\Models\\Contrato' => 'Contrato',
                        'App\\Models\\ContratoRenglon' => 'Contrato Renglón',
                        'App\\Models\\EmpleadoRenglon' => 'Empleado Renglón',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('alertable_id')
                    ->label('ID de la Entidad')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('tipo_alerta')
                    ->label('Tipo de Alerta')
                    ->options([
                        'vencimiento_contrato' => 'Vencimiento de Contrato',
                        'renovacion_pendiente' => 'Renovación Pendiente',
                        'documento_vencido' => 'Documento Vencido',
                        'evaluacion_pendiente' => 'Evaluación Pendiente',
                        'pago_pendiente' => 'Pago Pendiente',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('titulo')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3),

                Forms\Components\DatePicker::make('fecha_vencimiento')
                    ->label('Fecha de Vencimiento')
                    ->required(),

                Forms\Components\TextInput::make('dias_anticipacion')
                    ->label('Días de Anticipación')
                    ->numeric()
                    ->default(30)
                    ->required(),

                Forms\Components\DatePicker::make('fecha_alerta')
                    ->label('Fecha de Alerta')
                    ->required(),

                Forms\Components\Select::make('prioridad')
                    ->label('Prioridad')
                    ->options([
                        'baja' => 'Baja',
                        'media' => 'Media',
                        'alta' => 'Alta',
                        'critica' => 'Crítica',
                    ])
                    ->default('media')
                    ->required(),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'notificada' => 'Notificada',
                        'atendida' => 'Atendida',
                        'vencida' => 'Vencida',
                        'cancelada' => 'Cancelada',
                    ])
                    ->default('pendiente')
                    ->required(),

                Forms\Components\Textarea::make('destinatarios')
                    ->label('Destinatarios (emails separados por comas)')
                    ->rows(2),

                Forms\Components\DateTimePicker::make('fecha_notificacion')
                    ->label('Fecha de Notificación'),

                Forms\Components\Textarea::make('acciones_tomadas')
                    ->label('Acciones Tomadas')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('tipo_alerta')
                    ->label('Tipo de Alerta')
                    ->colors([
                        'danger' => 'vencimiento_contrato',
                        'warning' => 'renovacion_pendiente',
                        'secondary' => 'documento_vencido',
                        'primary' => 'evaluacion_pendiente',
                        'success' => 'pago_pendiente',
                    ]),

                Tables\Columns\TextColumn::make('alertable_type')
                    ->label('Entidad')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'App\\Models\\Contrato' => 'Contrato',
                        'App\\Models\\ContratoRenglon' => 'Contrato Renglón',
                        'App\\Models\\EmpleadoRenglon' => 'Empleado Renglón',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Fecha Vencimiento')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_alerta')
                    ->label('Fecha Alerta')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('prioridad')
                    ->label('Prioridad')
                    ->colors([
                        'secondary' => 'baja',
                        'warning' => 'media',
                        'danger' => 'alta',
                        'primary' => 'critica',
                    ]),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'primary' => 'notificada',
                        'success' => 'atendida',
                        'danger' => 'vencida',
                        'secondary' => 'cancelada',
                    ]),

                Tables\Columns\TextColumn::make('dias_anticipacion')
                    ->label('Días Anticipación')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_alerta')
                    ->options([
                        'vencimiento_contrato' => 'Vencimiento de Contrato',
                        'renovacion_pendiente' => 'Renovación Pendiente',
                        'documento_vencido' => 'Documento Vencido',
                        'evaluacion_pendiente' => 'Evaluación Pendiente',
                        'pago_pendiente' => 'Pago Pendiente',
                    ]),
                Tables\Filters\SelectFilter::make('prioridad')
                    ->options([
                        'baja' => 'Baja',
                        'media' => 'Media',
                        'alta' => 'Alta',
                        'critica' => 'Crítica',
                    ]),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'notificada' => 'Notificada',
                        'atendida' => 'Atendida',
                        'vencida' => 'Vencida',
                        'cancelada' => 'Cancelada',
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
            'index' => Pages\ListAlertaVencimientos::route('/'),
            'create' => Pages\CreateAlertaVencimiento::route('/create'),
            'edit' => Pages\EditAlertaVencimiento::route('/{record}/edit'),
        ];
    }
}
