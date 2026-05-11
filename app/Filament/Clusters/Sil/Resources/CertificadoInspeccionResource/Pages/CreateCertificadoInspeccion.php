<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource;
use App\Services\Sil\CertificadoInspeccion\CertificadoInspeccionService;
use App\Services\Sil\CertificadoInspeccion\CertificadoPdfGenerator;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCertificadoInspeccion extends CreateRecord
{
    protected static string $resource = CertificadoInspeccionResource::class;
    protected static ?string $title = 'Registro de Certificado de Inspección';


      /**
     * Este método se ejecuta JUSTO ANTES de crear el registro en la DB
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Calculamos el número en el último segundo posible
        $servicio = app(CertificadoInspeccionService::class);
        $siguiente = $servicio->obtenerSiguienteNumero();

        // 2. Lo inyectamos en los datos que se van a guardar
        $data['cin_numero'] = $siguiente;
        // $data['cin_anio'] = now()->year;

        return $data;
    }

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
