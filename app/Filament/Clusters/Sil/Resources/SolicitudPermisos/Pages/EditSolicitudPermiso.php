<?php

namespace App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Pages;

use App\Filament\Clusters\Sil\Resources\SolicitudPermisos\SolicitudPermisoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudPermiso extends EditRecord
{
    protected static string $resource = SolicitudPermisoResource::class;

    protected static ?string $title = 'Control de Aprobación de Permisos';

    //change tittle
    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function beforeSave(): void
    {
        if ($this->record->module_id === 2 && !in_array(auth()->id(), [1, 2])) {

            \Filament\Notifications\Notification::make()
                ->warning()
                ->title('Acceso denegado')
                ->body('Solo los usuarios autorizados pueden editar tickets ')
                ->send();

            $this->halt();
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['admin_id'] = auth()->id();
        $data['fecha_aprobacion'] = now();

        return $data;
    }
}
