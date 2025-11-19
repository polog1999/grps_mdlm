<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertificadoLicenciaFuncionamiento extends CreateRecord
{
    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;
    protected static ?string $title = 'Registro de Certificado de Licencia de Funcionamiento';
}
