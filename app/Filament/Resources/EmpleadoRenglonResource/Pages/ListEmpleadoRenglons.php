<?php

namespace App\Filament\Resources\EmpleadoRenglonResource\Pages;

use App\Filament\Resources\EmpleadoRenglonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpleadoRenglons extends ListRecords
{
    protected static string $resource = EmpleadoRenglonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
