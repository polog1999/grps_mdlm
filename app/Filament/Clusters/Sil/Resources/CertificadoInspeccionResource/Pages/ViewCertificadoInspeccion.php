<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;


class ViewCertificadoInspeccion extends ViewRecord
{
    protected static string $resource = CertificadoInspeccionResource::class;
    protected static ?string $title = 'Vista de Certificado de Inspección';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

}
