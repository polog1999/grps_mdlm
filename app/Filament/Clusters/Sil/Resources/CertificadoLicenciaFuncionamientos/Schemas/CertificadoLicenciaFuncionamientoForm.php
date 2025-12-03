<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps\BusquedaStep;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps\SeleccionCoincidenciasStep;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps\DatosCompletosStep;

class CertificadoLicenciaFuncionamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make([
                BusquedaStep::make()
                    ->hidden(fn(string $operation) => $operation === 'edit'),
                SeleccionCoincidenciasStep::make()
                    ->hidden(fn(string $operation) => $operation === 'edit'),
                DatosCompletosStep::make(),
            ])->columnSpanFull()
        ]);
    }
}