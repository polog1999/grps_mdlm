<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Resources\Pages\EditRecord;
use App\Services\Sil\Licencias\GiroLicenciaService;
use App\Services\Sil\Licencias\LicenciaService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class DuplicateCertificadoLicenciaFuncionamiento extends EditRecord
{
    use \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Traits\HasLicenciaFormHandling;

    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;

    protected static ?string $title = 'Duplicar Certificado de Licencia de Funcionamiento';

    protected function getHeaderActions(): array
    {
        return [
            // No actions needed for duplicate page
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->fillLicenciaFormData($data, $this->record, true);
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $params = $this->getCommonLicenciaParams($data);

        // Specifics for Duplicate
        $params['lic_id_ori'] = $record->lic_id;
        $params['urbanizacion_id'] = $data['coduca'] ?? '';

        $params['giros'] = $this->getGirosForNew($data);

        try {
            $service = app(LicenciaService::class);
            Log::info('Iniciando proceso de duplicación de licencia', [
                'lic_id_original' => $record->lic_id,
                'params' => $params
            ]);

            $resultado = $service->duplicate($params);

            // El SP retorna error > 0 para éxito (el nuevo lic_id)
            if (!empty($resultado) && isset($resultado[0])) {
                $spResult = $resultado[0];
                $nuevoLicId = $spResult->error ?? 0;

                if ($nuevoLicId > 0) {
                    Notification::make()
                        ->title('Licencia duplicada exitosamente')
                        ->body("Nueva licencia creada con ID: {$nuevoLicId}")
                        ->success()
                        ->send();

                    // Redirigir a la lista
                    $this->redirect(CertificadoLicenciaFuncionamientoResource::getUrl('index'));
                } else {
                    throw new \Exception($spResult->mensaje ?? 'Error desconocido al duplicar');
                }
            } else {
                Log::error('No se recibió respuesta del servidor al duplicar licencia', ['resultado' => $resultado]);
                throw new \Exception('No se recibió respuesta del servidor');
            }

        } catch (\Exception $e) {
            Log::error('Excepción al duplicar licencia', [
                'mensaje' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Notification::make()
                ->title('Error al duplicar licencia')
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
                ->label('Duplicar Licencia')
                ->icon('heroicon-o-document-duplicate'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
