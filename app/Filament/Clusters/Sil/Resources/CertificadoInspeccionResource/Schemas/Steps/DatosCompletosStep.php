<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\Steps;

use Filament\Schemas\Components\Wizard\Step;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\CertificadoInspeccionForm;

class DatosCompletosStep
{
    public static function make(): Step
    {
        return Step::make('Datos Completos')
            ->description('Verifique y complete la información del certificado')
            ->icon('heroicon-o-document-check')
            ->schema([
                CertificadoInspeccionForm::seccionInformacionGeneral(),
                CertificadoInspeccionForm::seccionDatosEstablecimiento(),
                CertificadoInspeccionForm::seccionDimensiones(),
                CertificadoInspeccionForm::seccionVigencia(),
                CertificadoInspeccionForm::seccionResolucion(),
                CertificadoInspeccionForm::seccionLicencia(),
                CertificadoInspeccionForm::seccionInformacionAdicional(),
                ...CertificadoInspeccionForm::camposOcultosSistema(),
            ]);
    }
}
