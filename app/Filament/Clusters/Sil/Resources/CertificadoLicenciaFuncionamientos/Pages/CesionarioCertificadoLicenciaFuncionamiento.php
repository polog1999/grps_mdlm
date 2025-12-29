<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Resources\Pages\EditRecord;
use App\Services\Sil\Licencias\GiroLicenciaService;
use App\Services\Sil\Licencias\LicenciaService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CesionarioCertificadoLicenciaFuncionamiento extends EditRecord
{
    use \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Traits\HasLicenciaFormHandling;

    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;

    protected static ?string $title = 'Cesionario de Certificado de Licencia de Funcionamiento';

    protected function getHeaderActions(): array
    {
        return [
            // No actions needed for cesionario page
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $tipoResolucionSubgerencial = \DB::connection('pgsql_licencias')
            ->table('licencia.tiporesolucion')
            ->where('tir_descripcion', 'LIKE', '%SUBGERENCIAL%')
            ->value('tir_id') ?? 2;

        return [
            'tipo_resolucion' => $tipoResolucionSubgerencial,
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $params = $this->getCommonLicenciaParams($data);

        // Specifics for Cesionario
        $params['lic_id_ori'] = $record->lic_id;
        $params['urbanizacion_id'] = $data['coduca'] ?? '';

        $params['giros'] = $this->getGirosForNew($data);

        try {
            $service = app(LicenciaService::class);
            Log::info('Iniciando proceso de cesionario de licencia', [
                'lic_id_original' => $record->lic_id,
                'params' => $params
            ]);

            // Assuming there's a cesionario method in the service
            // If not, you'll need to create it or use transfer/duplicate as base
            $resultado = $service->cesionario($params);

            // El SP retorna error > 0 para éxito (el nuevo lic_id)
            if (!empty($resultado) && isset($resultado[0])) {
                $spResult = $resultado[0];
                $nuevoLicId = $spResult->error ?? 0;

                if ($nuevoLicId > 0) {
                    Notification::make()
                        ->title('Cesionario registrado exitosamente')
                        ->body("Nueva licencia creada con ID: {$nuevoLicId}. La licencia original ha sido marcada como cedida.")
                        ->success()
                        ->send();

                    // Redirigir a la lista
                    $this->redirect(CertificadoLicenciaFuncionamientoResource::getUrl('index'));
                } else {
                    throw new \Exception($spResult->mensaje ?? 'Error desconocido al registrar cesionario');
                }
            } else {
                Log::error('No se recibió respuesta del servidor al registrar cesionario', ['resultado' => $resultado]);
                throw new \Exception('No se recibió respuesta del servidor');
            }

        } catch (\Exception $e) {
            Log::error('Excepción al registrar cesionario', [
                'mensaje' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Notification::make()
                ->title('Error al registrar cesionario')
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
                ->label('Registrar Cesionario')
                ->icon('heroicon-o-user-plus'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
