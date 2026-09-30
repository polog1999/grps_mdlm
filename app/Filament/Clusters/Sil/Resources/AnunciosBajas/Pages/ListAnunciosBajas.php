<?php

namespace App\Filament\Clusters\Sil\Resources\AnunciosBajas\Pages;

use App\Filament\Clusters\Sil\Resources\AnunciosBajas\AnunciosBajasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnunciosBajas extends ListRecords
{
    protected static string $resource = AnunciosBajasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
