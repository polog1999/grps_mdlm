<?php

namespace App\Filament\Resources\CertificadoInspeccions\Pages;

use App\Filament\Resources\CertificadoInspeccions\CertificadoInspeccionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertificadoInspeccion extends CreateRecord
{
    protected static string $resource = CertificadoInspeccionResource::class;
    protected static ?string $title = 'Creación de Certificado de Inspección';

}
