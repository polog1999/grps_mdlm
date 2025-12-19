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
        $params['compatibilidad'] = $data['compatibilidad'] ?? '';
        $params['lca_origen'] = 'S';
        $params['rsgparrafo1'] = '';
        $params['rsgparrafo2'] = '';

        $params['giros'] = $this->getGirosForUpdate($record, $data);

        $service = app(LicenciaService::class);
        $service->update($params);

        return CertificadoLicenciaFuncionamiento::find($record->lic_id);
    }
}
