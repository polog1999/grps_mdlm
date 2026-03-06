<?php

namespace App\Filament\Clusters\Visitas\Resources\Regimens\Pages;

use App\Filament\Clusters\Visitas\Resources\Regimens\RegimenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRegimen extends EditRecord
{
    protected static string $resource = RegimenResource::class;

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
