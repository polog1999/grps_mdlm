<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoBorrados\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoBorrados\CertificadoBorradoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCertificadoBorrado extends EditRecord
{
    protected static string $resource = CertificadoBorradoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
