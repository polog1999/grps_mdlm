<?php

namespace App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Pages;

use App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\DataLevantamientoConsolidaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataLevantamientoConsolidas extends ListRecords
{
    protected static string $resource = DataLevantamientoConsolidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
