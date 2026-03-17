<?php

namespace App\Filament\Clusters\Visitas\Resources\Motivos\Pages;

use App\Filament\Clusters\Visitas\Resources\Motivos\MotivoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMotivos extends ListRecords
{
    protected static string $resource = MotivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
