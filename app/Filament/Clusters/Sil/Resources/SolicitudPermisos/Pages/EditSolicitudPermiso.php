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
        $user = auth()->user();
        $user_role_id = $user->modelHasRole?->role_id;

        // Role 1 = Admin (global access)
        // Role 11 = Coordinador SPEA & ITSE (global access)
        if (in_array($user_role_id, [1, 11])) {
            return; // Acceso permitido
        }

        // Role 2 = SPEA (only Licencias - module_id=2)
        if ($user_role_id === 2 && $this->record->module_id === 2) {
            return; // Acceso permitido
        }

        // Role 6 = ITSE (only ITSE - module_id=1)
        if ($user_role_id === 6 && $this->record->module_id === 1) {
            return; // Acceso permitido
        }

        // Si llegamos aquí, no tiene acceso
        \Filament\Notifications\Notification::make()
            ->warning()
            ->title('Acceso denegado')
            ->body('Solo los usuarios autorizados pueden editar tickets de este módulo.')
            ->send();

        $this->halt();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['admin_id'] = auth()->id();
        $data['fecha_aprobacion'] = now();

        return $data;
    }
}
