<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Resources\Pages\EditRecord;
use App\Services\Sil\Licencias\GiroLicenciaService;
use App\Services\Sil\Licencias\LicenciaService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TransferirCertificadoLicenciaFuncionamiento extends EditRecord
{
    use \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Traits\HasLicenciaFormHandling;

    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;

    protected static ?string $title = 'Transferir Certificado de Licencia de Funcionamiento';

    protected function getHeaderActions(): array
    {
        return [
            // No actions needed for transfer page
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->fillLicenciaFormData($data, $this->record, true);
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $params = $this->getCommonLicenciaParams($data);

        // Specifics for Transfer
        $params['lic_id_ori'] = $record->lic_id;
        $params['urbanizacion_id'] = $data['coduca'] ?? '';

        $params['giros'] = $this->getGirosForNew($data);

        try {
            $service = app(LicenciaService::class);
            Log::info('Iniciando proceso de transferencia de licencia', [
                'lic_id_original' => $record->lic_id,
                'params' => $params
            ]);

            $resultado = $service->transfer($params);

            // El SP retorna error > 0 para éxito (el nuevo lic_id)
            if (!empty($resultado) && isset($resultado[0])) {
                $spResult = $resultado[0];
                $nuevoLicId = $spResult->error ?? 0;

                if ($nuevoLicId > 0) {
                    Notification::make()
                        ->title('Licencia transferida exitosamente')
                        ->body("Nueva licencia creada con ID: {$nuevoLicId}. La licencia original ha sido marcada como transferida.")
                        ->success()
                        ->send();

                    // Redirigir a la lista
                    $this->redirect(CertificadoLicenciaFuncionamientoResource::getUrl('index'));
                } else {
                    throw new \Exception($spResult->mensaje ?? 'Error desconocido al transferir');
                }
            } else {
                Log::error('No se recibió respuesta del servidor al transferir licencia', ['resultado' => $resultado]);
                throw new \Exception('No se recibió respuesta del servidor');
            }

        } catch (\Exception $e) {
            Log::error('Excepción al transferir licencia', [
                'mensaje' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Notification::make()
                ->title('Error al transferir licencia')
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }

        return $record;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Transferir Licencia')
                ->icon('heroicon-o-arrow-path'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        // Finalizar el permiso si existe y fue usado con exito
        \App\Models\SolicitudPermiso::query()
            ->where('record_id', $this->record->lic_id)
            ->where('user_id', auth()->id())
            ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
            ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::TRANSFERIR_LICENCIA)
            ->update([
                'estado' => \App\Enums\SolicitudPermisoEstado::FINALIZADO
            ]);
    }
}
