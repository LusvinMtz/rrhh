<?php

namespace App\Filament\Resources\AlertaVencimientoResource\Pages;

use App\Filament\Resources\AlertaVencimientoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlertaVencimiento extends EditRecord
{
    protected static string $resource = AlertaVencimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
