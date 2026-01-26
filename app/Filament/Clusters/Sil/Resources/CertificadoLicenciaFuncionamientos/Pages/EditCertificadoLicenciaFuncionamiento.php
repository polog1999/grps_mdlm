<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Services\Sil\Licencias\GiroLicenciaService;
use App\Services\Sil\Licencias\LicenciaService;
use Carbon\Carbon;
use App\Models\CertificadoLicenciaFuncionamiento;
class EditCertificadoLicenciaFuncionamiento extends EditRecord
{
    use \App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Traits\HasLicenciaFormHandling;

    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->fillLicenciaFormData($data, $this->record);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $params = $this->getCommonLicenciaParams($data);

        // Parametros específicos para Update
        $params['lic_id'] = $record->lic_id;
        $params['lca_urbanizacion'] = $data['descurb'] ?? '';
        $params['lic_compatibilidad'] = $data['compatibilidad'] ?? '';
        $params['lic_compatibilidadnumero'] = $data['nro_compatibilidad'] ?? '';
        $params['lic_compatibilidadfecha'] = $data['fecha_compatibilidad'];
        $params['lca_origen'] = 'S';
        $params['rsgparrafo1'] = '';
        $params['rsgparrafo2'] = '';

        $params['giros'] = $this->getGirosForUpdate($record, $data);

        $service = app(LicenciaService::class);
        $service->update($params);

        return CertificadoLicenciaFuncionamiento::find($record->lic_id);
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $user = auth()->user();

        // Obtener el ID del módulo basado en el recurso
        $moduleId = \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id');

        // Buscar si existe un ticket APROBADO para este usuario, registro y módulo
        // y pasarlo a FINALIZADO para cerrar el ciclo de permiso.
        \App\Models\SolicitudPermiso::query()
            ->where('module_id', $moduleId)
            ->where('record_id', $this->record->lic_id)
            ->where('user_id', $user->id)
            ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
            ->update([
                'estado' => \App\Enums\SolicitudPermisoEstado::FINALIZADO
            ]);
    }
}
