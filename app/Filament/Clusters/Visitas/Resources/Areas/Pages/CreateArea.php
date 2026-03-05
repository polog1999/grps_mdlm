<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Pages;

use App\Filament\Clusters\Visitas\Resources\Areas\AreaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArea extends CreateRecord
{
    protected static string $resource = AreaResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
