<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource;
use App\Services\Sil\CertificadoInspeccion\CertificadoPdfGenerator;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCertificadoInspeccion extends CreateRecord
{
    protected static string $resource = CertificadoInspeccionResource::class;
    protected static ?string $title = 'Registro de Certificado de Inspección';

    /**
     * Hook ejecutado después de crear el registro.
     * Genera automáticamente el PDF del certificado.
     */
    protected function afterCreate(): void
    {
        $pdfGenerator = app(CertificadoPdfGenerator::class);
        $filename = $pdfGenerator->generateAndSave($this->record);

        if ($filename) {
            Notification::make()
                ->title('PDF Generado')
                ->body("El certificado PDF se generó exitosamente: {$filename}")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Advertencia')
                ->body('El certificado se guardó pero no se pudo generar el PDF automáticamente.')
                ->warning()
                ->send();
        }
    }

    /**
     * Redirige a la vista de lista después de crear el certificado.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
