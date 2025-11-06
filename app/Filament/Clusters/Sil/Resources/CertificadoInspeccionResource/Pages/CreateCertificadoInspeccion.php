<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertificadoInspeccion extends CreateRecord
{
    protected static string $resource = CertificadoInspeccionResource::class;
    protected static ?string $title = 'Registro de Certificado de Inspección';
}
