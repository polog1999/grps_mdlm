<?php

namespace App\Filament\Clusters\Sil\Resources\TipoResolucions\Pages;

use App\Filament\Clusters\Sil\Resources\TipoResolucions\TipoResolucionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTipoResolucion extends EditRecord
{
    protected static string $resource = TipoResolucionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
