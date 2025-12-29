<?php

namespace App\Filament\Clusters\Sil\Resources\Personas\Pages;

use App\Filament\Clusters\Sil\Resources\Personas\PersonaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersona extends EditRecord
{
    protected static string $resource = PersonaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
