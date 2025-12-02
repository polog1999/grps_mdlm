<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
use App\Services\Sil\Licencias\LicenciaService;
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
            // Obtener datos completos del expediente
            $serviceCertificado = app(CertificadoLincenciaFuncionamientoService::class);
            $result = $serviceCertificado->obtenerDatosCompletosParaRegistrarPorExpediente($state);

            if (!$result || !isset($result['expediente'])) {
                self::notify('warning', 'No se encontraron datos', 'No se encontró información para el expediente ingresado.');
                return;
            }

            // Obtener el código catastral
            $codcat = $result['expediente']->ecc_codcat ?? null;

            if (empty($codcat)) {
                self::notify('warning', 'Código catastral no encontrado', 'El expediente no tiene un código catastral asociado.');
                return;
            }

            // Usar LicenciaService para obtener datos de catastro
            $serviceLicencia = app(LicenciaService::class);
            $catastroResults = $serviceLicencia->obtenerDatosGeneralesDeCatastroPorCodigoCatastral($codcat);

            // Validar cantidad de resultados
            if (empty($catastroResults)) {
                self::notify('warning', 'Sin datos de catastro', 'No se encontraron datos catastrales para el código: ' . $codcat);
                return;
            }

            $cantidadResultados = is_array($catastroResults) ? count($catastroResults) : (is_countable($catastroResults) ? count($catastroResults) : 1);

            if ($cantidadResultados > 1) {
                // Múltiples resultados: activar step de selección de catastro

                // Convertir objetos stdClass a arrays para que se puedan almacenar correctamente
                $catastroArray = [];
                foreach ($catastroResults as $catastro) {
                    $catastroArray[] = (array) $catastro;
                }

                // Log para debugging
                Log::info('Catastro coincidencias encontradas:', [
                    'cantidad' => $cantidadResultados,
                    'primer_registro' => $catastroArray[0] ?? null
                ]);

                $set('_catastro_coincidencias', $catastroArray);
                $set('_tiene_errores', false);
                $set('_datos_completos', $result);

                self::notify(
                    'warning',
                    'Múltiples coincidencias encontradas',
                    "Se encontraron {$cantidadResultados} registros catastrales. Por favor, revise los datos en el siguiente paso."
                );
            } else {
                // Un solo resultado: continuar normalmente

                // Agregar los datos de catastro al resultado
                if (!empty($catastroResults)) {
                    $primerCatastro = is_array($catastroResults) ? $catastroResults[0] : $catastroResults->first();
                    // Convertir a array si es objeto
                    $result['catastro'] = is_object($primerCatastro) ? (array) $primerCatastro : $primerCatastro;
                }

                $set('_datos_completos', $result);
                $set('_catastro_coincidencias', null);
                $set('_tiene_errores', false);

                // Autocompletar datos
                DatosCompletosStep::autocompletarDatos($result, $set);

                self::notify('success', 'Datos recuperados exitosamente', 'Se encontraron los datos del expediente.');
            }

        } catch (\Throwable $e) {
            Log::error('Error recuperando datos: ' . $e->getMessage());
            self::notify('danger', 'Error', 'Ocurrió un error al recuperar los datos: ' . $e->getMessage());
        }
    }

    private static function notify(string $type, string $title, string $body): void
    {
        Notification::make()->$type()->title($title)->body($body)->send();
    }
}
