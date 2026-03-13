<?php

namespace App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\Pages;

use App\Filament\Clusters\Visitas\Resources\AuditoriaTrabajadors\AuditoriaTrabajadorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaTrabajadors extends ListRecords
{
    protected static string $resource = AuditoriaTrabajadorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
