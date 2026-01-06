<?php

namespace App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Pages;

use App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\AuditoriaLicenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditoriaLicencias extends ListRecords
{
    protected static string $resource = AuditoriaLicenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
