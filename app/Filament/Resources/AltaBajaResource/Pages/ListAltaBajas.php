<?php

namespace App\Filament\Resources\AltaBajaResource\Pages;

use App\Filament\Resources\AltaBajaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAltaBajas extends ListRecords
{
    protected static string $resource = AltaBajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
