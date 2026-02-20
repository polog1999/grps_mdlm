<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Pages;

use App\Filament\Clusters\Sil\Resources\Anuncios\AnunciosResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAnuncios extends EditRecord
{
    protected static string $resource = AnunciosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
