<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource;
use App\Filament\Actions\ExportCertificadoAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCertificadoInspeccions extends ListRecords
{
    protected static string $resource = CertificadoInspeccionResource::class;

    // Título personalizado de la página List
    protected static ?string $title = 'Listado de Certificados de Inspección';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportCertificadoAction::make(),
        ];
    }

}