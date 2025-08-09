<?php

namespace App\Filament\Resources\AlertaVencimientoResource\Pages;

use App\Filament\Resources\AlertaVencimientoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlertaVencimientos extends ListRecords
{
    protected static string $resource = AlertaVencimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
