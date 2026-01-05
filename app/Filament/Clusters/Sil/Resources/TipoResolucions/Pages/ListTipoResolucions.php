<?php

namespace App\Filament\Clusters\Sil\Resources\TipoResolucions\Pages;

use App\Filament\Clusters\Sil\Resources\TipoResolucions\TipoResolucionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTipoResolucions extends ListRecords
{
    protected static string $resource = TipoResolucionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
