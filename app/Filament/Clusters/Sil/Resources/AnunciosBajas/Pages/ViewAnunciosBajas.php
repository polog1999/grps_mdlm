<?php

namespace App\Filament\Clusters\Sil\Resources\AnunciosBajas\Pages;

use App\Filament\Clusters\Sil\Resources\AnunciosBajas\AnunciosBajasResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAnunciosBajas extends ViewRecord
{
    protected static string $resource = AnunciosBajasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
