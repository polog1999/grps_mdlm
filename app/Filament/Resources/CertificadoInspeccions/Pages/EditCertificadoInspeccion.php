<?php

namespace App\Filament\Resources\CertificadoInspeccions\Pages;

use App\Filament\Resources\CertificadoInspeccions\CertificadoInspeccionResource;
use Filament\Actions\DeleteAction;
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
