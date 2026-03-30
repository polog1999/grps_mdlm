<?php

namespace App\Filament\Clusters\Visitas\Resources\Motivos\Pages;

use App\Filament\Clusters\Visitas\Resources\Motivos\MotivoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMotivo extends EditRecord
{
    protected static string $resource = MotivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id_modi'] = auth()->id();

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
