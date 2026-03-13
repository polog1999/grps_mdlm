<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\AuditoriaTrabajadorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuditoriaTrabajador extends EditRecord
{
    protected static string $resource = AuditoriaTrabajadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
