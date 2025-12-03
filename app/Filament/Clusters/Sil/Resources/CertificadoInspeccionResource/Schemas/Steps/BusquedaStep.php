<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Grid;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\CertificadoInspeccionForm;

class BusquedaStep
{
    public static function make(): Step
    {
        return Step::make('Búsqueda')
            ->description('Buscar por expediente, licencia o resolución')
            ->icon('heroicon-o-magnifying-glass')
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('search_expediente')
                            ->label('Número de Expediente')
                            ->placeholder('Ej: 2025-001234')
                            ->suffixIcon('heroicon-o-folder-open')
                            ->helperText('Ingrese el expediente administrativo'),

                        TextInput::make('search_licencia')
                            ->label('Número de Licencia')
                            ->placeholder('Ej: 2024-12345')
                            ->suffixIcon('heroicon-o-document-check')
                            ->helperText('Ingrese el número de licencia'),

                        TextInput::make('search_resolucion')
                            ->label('Número de Resolución')
                            ->placeholder('Ej: 3095-2024')
                            ->suffixIcon('heroicon-o-document-text')
                            ->helperText('Ingrese el número de resolución'),
                    ]),

                Hidden::make('search_completed')
                    ->default(false)
                    ->required()
                    ->validationMessages([
                        'required' => 'Debe realizar una búsqueda exitosa para continuar.',
                    ]),
            ])
            ->beforeValidation(function ($state, callable $set) {
                // Execute search when user clicks "Next"
                $data = [
                    'search_expediente' => $state['search_expediente'] ?? '',
                    'search_licencia' => $state['search_licencia'] ?? '',
                    'search_resolucion' => $state['search_resolucion'] ?? '',
                ];

                // Perform the search
                CertificadoInspeccionForm::manejarBusquedaLicencia($data, $set);
            });
    }
}
