<?php

namespace App\Filament\Clusters\Sil\Resources\AnunciosBajas\Pages;

use App\Filament\Clusters\Sil\Resources\AnunciosBajas\AnunciosBajasResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAnunciosBajas extends EditRecord
{
    protected static string $resource = AnunciosBajasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
