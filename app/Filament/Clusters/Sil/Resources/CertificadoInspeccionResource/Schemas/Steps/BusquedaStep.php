<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Grid;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\Sil\CertificadoInspeccion\ResolucionService;
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
                
                // We need a way to trigger the search. In a Wizard step, we can use a button or live updates.
                // The original form used a modal action. Here we are in the form itself.
                // We can use a Actions component or just rely on a button.
                // However, Filament Forms inside a Wizard don't easily support standalone buttons that are not form actions.
                // But we can use `suffixAction` on the inputs or a `ViewField` with a button, or just `live()` on inputs.
                // Let's use `live(onBlur: true)` and `afterStateUpdated` like the example `BusquedaStep.php`.
                // But we have 3 fields. We might want a dedicated "Search" button or search on blur of any field.
                // The example uses `live(onBlur: true)` on `lic_expnum`.
                
                // Let's try to add a "Search" action using `Filament\Forms\Components\Actions`.
                // Or better, let's make the inputs live and trigger search when any of them changes (on blur).
                
            ])
            // We can add an action to the step schema? No, schema expects components.
            // We can use `Filament\Forms\Components\Actions::make([ ... ])`
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('search_expediente')
                            ->label('Número de Expediente')
                            ->placeholder('Ej: 2025-001234')
                            ->suffixIcon('heroicon-o-folder-open')
                            ->helperText('Ingrese el expediente administrativo')
                            ->live(onBlur: true),

                        TextInput::make('search_licencia')
                            ->label('Número de Licencia')
                            ->placeholder('Ej: 2024-12345')
                            ->suffixIcon('heroicon-o-document-check')
                            ->helperText('Ingrese el número de licencia')
                            ->live(onBlur: true),

                        TextInput::make('search_resolucion')
                            ->label('Número de Resolución')
                            ->placeholder('Ej: 3095-2024')
                            ->suffixIcon('heroicon-o-document-text')
                            ->helperText('Ingrese el número de resolución')
                            ->live(onBlur: true),
                    ]),
                
                    Action::make('buscar')
                        ->label('Buscar')
                        ->icon('heroicon-o-magnifying-glass')
                        ->color('primary')
                        ->action(function ($get, $set) {
                            $data = [
                                'search_expediente' => $get('search_expediente'),
                                'search_licencia' => $get('search_licencia'),
                                'search_resolucion' => $get('search_resolucion'),
                            ];
                            CertificadoInspeccionForm::manejarBusquedaLicencia($data, $set);
                    })
            ]);
    }
}
