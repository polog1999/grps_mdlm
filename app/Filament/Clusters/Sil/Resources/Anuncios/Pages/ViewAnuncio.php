<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Pages;

use App\Filament\Clusters\Sil\Resources\Anuncios\AnunciosResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;


class ViewAnuncio extends ViewRecord
{
    protected static string $resource = AnunciosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
