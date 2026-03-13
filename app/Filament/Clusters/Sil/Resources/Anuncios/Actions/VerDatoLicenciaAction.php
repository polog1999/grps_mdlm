<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Actions;

use Filament\Actions\Action;
use Filament\Support\Colors\Color;

class VerDatoLicenciaAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'ver_dato_licencia';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Licencia')
            ->icon('heroicon-o-eye')
            ->color(Color::Fuchsia)
            ->modalHeading('Datos de la Licencia')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar')
            ->form([
                \Filament\Forms\Components\Select::make('lic_id')
                    ->label('Licencia')
                    ->options(function () {
                        return \App\Models\CertificadoLicenciaFuncionamiento::query()
                            ->limit(50) // Limitamos para inicializar rápido
                            ->get()
                            ->mapWithKeys(function ($licencia) {
                                return [$licencia->lic_id => $licencia->lic_numlic];
                            });
                    })
                    ->getSearchResultsUsing(
                        fn(string $search): array => \App\Models\CertificadoLicenciaFuncionamiento::where('lic_numlic', 'like', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($licencia) {
                                return [$licencia->lic_id => $licencia->lic_numlic];
                            })
                            ->toArray()
                    )
                    ->searchable()
                    ->placeholder('Busque por N° de licencia...'),
            ]);
    }
}
