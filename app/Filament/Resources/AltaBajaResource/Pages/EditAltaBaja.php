<?php

namespace App\Filament\Resources\AltaBajaResource\Pages;

use App\Filament\Resources\AltaBajaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAltaBaja extends EditRecord
{
    protected static string $resource = AltaBajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
