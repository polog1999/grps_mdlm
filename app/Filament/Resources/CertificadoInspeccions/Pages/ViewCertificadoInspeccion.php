<?php

namespace App\Filament\Resources\CertificadoInspeccions\Pages;

use App\Filament\Resources\CertificadoInspeccions\CertificadoInspeccionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Facades\FilamentColor;

class ViewCertificadoInspeccion extends ViewRecord
{
    protected static string $resource = CertificadoInspeccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
    
}
