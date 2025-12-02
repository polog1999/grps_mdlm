<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCertificadoInspeccion extends EditRecord
{
    protected static string $resource = CertificadoInspeccionResource::class;
    protected static ?string $title = 'Edición de Certificado de Inspección';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            //DeleteAction::make(),
        ];
    }
}
