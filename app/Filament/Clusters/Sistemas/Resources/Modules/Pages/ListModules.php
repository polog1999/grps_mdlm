<?php

namespace App\Filament\Clusters\Sistemas\Resources\Modules\Pages;

use App\Filament\Clusters\Sistemas\Resources\Modules\ModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
