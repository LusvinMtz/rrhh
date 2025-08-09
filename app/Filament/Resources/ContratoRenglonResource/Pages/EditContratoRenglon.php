<?php

namespace App\Filament\Resources\ContratoRenglonResource\Pages;

use App\Filament\Resources\ContratoRenglonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContratoRenglon extends EditRecord
{
    protected static string $resource = ContratoRenglonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
