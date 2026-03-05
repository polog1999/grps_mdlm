<?php

namespace App\Filament\Clusters\Visitas\Resources\Areas\Pages;

use App\Filament\Clusters\Visitas\Resources\Areas\AreaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditArea extends EditRecord
{
    protected static string $resource = AreaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasPermissionTo('edit::visitas_area');
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id_modi'] = auth()->id();

        return $data;
    }
}
