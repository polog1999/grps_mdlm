<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoBorrados\CertificadoBorradoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCertificadoBorrados extends ListRecords
{
    protected static string $resource = CertificadoBorradoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
