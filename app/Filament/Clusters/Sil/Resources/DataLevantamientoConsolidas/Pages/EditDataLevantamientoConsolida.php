<?php

namespace App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Pages;

use App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\DataLevantamientoConsolidaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDataLevantamientoConsolida extends EditRecord
{
    protected static string $resource = DataLevantamientoConsolidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
