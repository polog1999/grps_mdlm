<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Pages;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVisita extends EditRecord
{
    protected static string $resource = VisitaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
