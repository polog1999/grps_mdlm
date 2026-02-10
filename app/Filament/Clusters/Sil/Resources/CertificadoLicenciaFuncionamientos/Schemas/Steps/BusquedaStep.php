<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Actions\Sil\ProcesarBusquedaExpedienteAction;
use App\DTOs\Sil\BusquedaExpedienteResult;
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
            Log::info('BusquedaStep: Iniciando búsqueda', ['expediente' => $state]);
            $action = app(ProcesarBusquedaExpedienteAction::class);
            $result = $action->execute($state);
            Log::info('BusquedaStep: Resultado búsqueda', [
                'status' => $result->status,
                'message' => $result->message,
                'data_keys' => array_keys($result->data ?? []),
                'matches_count' => count($result->matches ?? [])
            ]);

            $set('_catastro_coincidencias', null);
            $set('_resolucion_areas_coincidencias', null);
            $set('_itse_coincidencias', null);
            $set('_tiene_errores', false);

            switch ($result->status) {
                case BusquedaExpedienteResult::STATUS_SELECTION_ITSE:
                    $set('_datos_completos', $result->data);
                    $set('_itse_coincidencias', $result->matches);
                    self::notify('info', 'Selección de ITSE Requerida', $result->message);
                    return;

                case BusquedaExpedienteResult::STATUS_NOT_FOUND:
                    self::notify('warning', 'Atención', $result->message);
                    return;

                case BusquedaExpedienteResult::STATUS_SELECTION_CATASTRO:
                    $set('_datos_completos', $result->data);
                    $set('_catastro_coincidencias', $result->matches);
                    self::notify('warning', 'Múltiples coincidencias', $result->message);
                    return;

                case BusquedaExpedienteResult::STATUS_SELECTION_RESOLUCION:
                    $set('_datos_completos', $result->data);
                    $set('_resolucion_areas_coincidencias', $result->matches);
                    self::notify('info', 'Selección Requerida', $result->message);
                    break;

                case BusquedaExpedienteResult::STATUS_SUCCESS:
                    $set('_datos_completos', $result->data);
                    self::notify('success', 'Éxito', 'Datos recuperados exitosamente.');
                    self::actualizarFormulario($result->data, $set);
                    break;
                case BusquedaExpedienteResult::STATUS_MISSING_PERSONA:
                    $set('_datos_completos', $result->data);
                    $set('_persona_requerida', true);
                    $set('_catastro_coincidencias', null);
                    $set('_resolucion_areas_coincidencias', null);

                    self::notify('warning', 'Datos Incompletos', $result->message);
                    self::actualizarFormulario($result->data, $set);
                    break;
            }

        } catch (\Throwable $e) {
            Log::error('Error crítico en búsqueda: ' . $e->getMessage());
            self::notify('danger', 'Error del Sistema', 'Ocurrió un error inesperado.');
        }
    }
    private static function actualizarFormulario(array $data, callable $set): void
    {
        DatosCompletosStep::autocompletarDatos($data, $set);
    }
    private static function notify(string $type, string $title, string $body): void
    {
        Notification::make()->$type()->title($title)->body($body)->send();
    }
}
