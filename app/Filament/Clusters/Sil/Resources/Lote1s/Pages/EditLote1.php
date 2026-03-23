<?php

namespace App\Filament\Clusters\Sil\Resources\Lote1s\Pages;

use App\Filament\Clusters\Sil\Resources\Lote1s\Lote1Resource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLote1 extends EditRecord
{
    protected static string $resource = Lote1Resource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
