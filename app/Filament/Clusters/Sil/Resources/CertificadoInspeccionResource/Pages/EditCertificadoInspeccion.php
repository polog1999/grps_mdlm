<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource;
use App\Services\Sil\CertificadoInspeccion\CertificadoPdfGenerator;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

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

    /**
     * Redirige a la vista de la tabla después de guardar los cambios.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Hook ejecutado después de guardar el registro.
     * Regenera automáticamente el PDF del certificado en la carpeta original.
     */
    protected function afterSave(): void
    {
        $pdfGenerator = app(CertificadoPdfGenerator::class);
        $filename = $pdfGenerator->generateAndSave($this->record);

        if ($filename) {
            Notification::make()
                ->title('PDF Regenerado')
                ->body("El certificado PDF se regeneró exitosamente: {$filename}")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Advertencia')
                ->body('Los cambios se guardaron pero no se pudo regenerar el PDF automáticamente.')
                ->warning()
                ->send();
        }

        // Cerrar ciclo de permiso: pasar ticket APROBADO a FINALIZADO
        $user = auth()->user();
        $moduleId = \App\Models\Module::where('filament_class', CertificadoInspeccionResource::class)->value('id');

        \App\Models\SolicitudPermiso::query()
            ->where('module_id', $moduleId)
            ->where('record_id', $this->record->cin_id)
            ->where('user_id', $user->id)
            ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
            ->update([
                'estado' => \App\Enums\SolicitudPermisoEstado::FINALIZADO
            ]);
    }
}
