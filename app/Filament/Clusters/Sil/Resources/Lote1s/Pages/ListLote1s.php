<?php

namespace App\Filament\Clusters\Sil\Resources\Lote1s\Pages;

use App\Filament\Clusters\Sil\Resources\Lote1s\Lote1Resource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLote1s extends ListRecords
{
    protected static string $resource = Lote1Resource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
