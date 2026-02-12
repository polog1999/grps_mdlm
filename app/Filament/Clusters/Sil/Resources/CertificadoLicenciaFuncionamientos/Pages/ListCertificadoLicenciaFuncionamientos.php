<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use App\Filament\Actions\ExportLicenciasAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;



class ListCertificadoLicenciaFuncionamientos extends ListRecords
{
    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Registrar Nueva Licencia')
                ->icon('heroicon-o-plus')
                ->visible(fn() => auth()->user()->hasPermissionTo('create::certificado_licencia_funcionamiento')),
            ExportLicenciasAction::make()
                ->label('Exportar Licencias')
                ->icon('heroicon-o-document-arrow-down')
                ->color('warning')
        ];
    }


}
