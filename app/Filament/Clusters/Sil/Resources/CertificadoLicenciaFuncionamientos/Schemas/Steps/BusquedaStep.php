<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BusquedaStep
{
    public static function make(): Step
    {
        return Step::make('Búsqueda')
            ->description('Ingrese el número de expediente')
            ->icon('heroicon-o-magnifying-glass')
            ->schema([
                TextInput::make('lic_expnum')
                    ->label('Número de Expediente')
                    ->placeholder('Ej: E-06073-2025')
                    ->required()
                    ->maxLength(50)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => self::buscarExpediente($state, $set))
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'text-center']),
            ]);
    }

    private static function buscarExpediente(?string $state, callable $set): void
    {
        if (empty($state))
            return;

        try {
            // Asegúrate que este servicio esté bien inyectado
            $service = app(CertificadoLincenciaFuncionamientoService::class);
            $result = $service->obtenerDatosCompletosParaRegistrarPorExpediente($state);

            if ($result && isset($result['expediente'])) {
                $set('_datos_completos', $result);
                // Call the static method from DatosCompletosStep
                DatosCompletosStep::autocompletarDatos($result, $set);
                self::notify('success', 'Datos recuperados exitosamente', 'Se encontraron los datos del expediente.');
            } else {
                self::notify('warning', 'No se encontraron datos', 'No se encontró información para el expediente ingresado.');
            }
        } catch (\Throwable $e) {
            Log::error('Error recuperando datos: ' . $e->getMessage());
            self::notify('danger', 'Error', 'Ocurrió un error al recuperar los datos.');
        }
    }

    private static function notify(string $type, string $title, string $body): void
    {
        Notification::make()->$type()->title($title)->body($body)->send();
    }
}
