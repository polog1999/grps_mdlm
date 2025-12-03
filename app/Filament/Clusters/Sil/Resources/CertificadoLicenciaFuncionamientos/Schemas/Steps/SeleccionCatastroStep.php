<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps;

use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard\Step;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps\DatosCompletosStep;

class SeleccionCatastroStep
{
    public static function make(): Step
    {
        return Step::make('Selección')
            ->description(fn($get) => self::getDescription($get))
            ->icon('heroicon-m-clipboard-document-check')
            ->schema(fn($get) => self::generarInterfazSeleccion($get))
            ->visible(fn($get) => !empty($get('_catastro_coincidencias')) || !empty($get('_resolucion_areas_coincidencias')));
    }

    private static function getDescription(callable $get): string
    {
        if (!empty($get('_resolucion_areas_coincidencias'))) {
            return 'Seleccione el área de resolución correcta';
        }
        return 'Resolver duplicados de catastro';
    }

    private static function generarInterfazSeleccion(callable $get): array
    {
        // Detectar qué tipo de selección se necesita
        $areasResolucion = $get('_resolucion_areas_coincidencias') ?? [];
        $catastroCoincidencias = $get('_catastro_coincidencias') ?? [];

        // Prioridad: Si hay áreas de resolución, mostrar eso primero
        if (!empty($areasResolucion)) {
            return self::generarSeleccionAreas($areasResolucion, $get);
        } elseif (!empty($catastroCoincidencias)) {
            return self::generarSeleccionCatastro($catastroCoincidencias, $get);
        }

        return [];
    }

    private static function generarSeleccionAreas(array $areas, callable $get): array
    {
        $cantidad = count($areas);
        $opciones = [];
        $descripciones = [];

        foreach ($areas as $item) {
            $codigoUnico = is_object($item) ? ($item->codigo_unico_tramite ?? null) : ($item['codigo_unico_tramite'] ?? null);
            $areaCompleta = is_object($item) ? ($item->area_completa ?? 'Sin área') : ($item['area_completa'] ?? 'Sin área');

            if (!$codigoUnico)
                continue;

            $opciones[$codigoUnico] = "Código: {$codigoUnico}";
            $descripciones[$codigoUnico] = "Área: {$areaCompleta}";
        }

        return [
            Section::make('Múltiples áreas de resolución encontradas')
                ->description("Se encontraron {$cantidad} áreas diferentes para esta resolución. Por favor, seleccione el área correcta.")
                ->icon('heroicon-o-building-office')
                ->schema([
                    Radio::make('codigo_unico_tramite_seleccionado')
                        ->label('Áreas disponibles')
                        ->hiddenLabel()
                        ->options($opciones)
                        ->descriptions($descripciones)
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            if (!$state)
                                return;

                            // Buscar el área completa seleccionada
                            $areas = $get('_resolucion_areas_coincidencias') ?? [];
                            $areaSeleccionada = null;

                            foreach ($areas as $item) {
                                $codigoUnico = is_object($item) ? ($item->codigo_unico_tramite ?? null) : ($item['codigo_unico_tramite'] ?? null);

                                if ($codigoUnico == $state) {
                                    $areaSeleccionada = is_object($item) ? (array) $item : $item;
                                    break;
                                }
                            }

                            if ($areaSeleccionada) {
                                // Obtener los datos completos actuales
                                $datosCompletos = $get('_datos_completos') ?? [];

                                // Agregar el codigo_unico_tramite a la resolución
                                if (!isset($datosCompletos['resolucion'])) {
                                    $datosCompletos['resolucion'] = [];
                                }
                                $datosCompletos['resolucion']['codigo_unico_tramite'] = $areaSeleccionada['codigo_unico_tramite'] ?? '';

                                // Guardar de vuelta
                                $set('_datos_completos', $datosCompletos);

                                // Llamar al autocompletado
                                DatosCompletosStep::autocompletarDatos($datosCompletos, $set);
                            }
                        })
                        ->columnSpanFull()
                ])
                ->collapsible(false)
                ->compact()
        ];
    }

    private static function generarSeleccionCatastro(array $coincidencias, callable $get): array
    {
        $cantidad = count($coincidencias);
        $opciones = [];
        $descripciones = [];

        foreach ($coincidencias as $item) {
            $fiuId = is_object($item) ? ($item->fiu_id ?? null) : ($item['fiu_id'] ?? null);
            $via = is_object($item) ? ($item->via_completa ?? 'Sin vía') : ($item['via_completa'] ?? 'Sin vía');

            if (!$fiuId)
                continue;

            $opciones[$fiuId] = "FIU ID: {$fiuId}";
            $descripciones[$fiuId] = "Dirección: {$via}";
        }

        return [
            Section::make('Múltiples registros catastrales encontrados')
                ->description("Hemos detectado {$cantidad} ubicaciones similares. Por favor, seleccione el código de identificación único (FIU ID) correcto para continuar.")
                ->icon('heroicon-o-exclamation-triangle')
                ->schema([
                    Radio::make('fiu_id_seleccionado')
                        ->label('Listado de coincidencias')
                        ->hiddenLabel()
                        ->options($opciones)
                        ->descriptions($descripciones)
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            if (!$state)
                                return;

                            // Buscar el registro completo por fiu_id
                            $coincidencias = $get('_catastro_coincidencias') ?? [];
                            $catastroSeleccionado = null;

                            foreach ($coincidencias as $item) {
                                $fiuId = is_object($item) ? ($item->fiu_id ?? null) : ($item['fiu_id'] ?? null);

                                if ($fiuId == $state) {
                                    $catastroSeleccionado = is_object($item) ? (array) $item : $item;
                                    break;
                                }
                            }

                            if ($catastroSeleccionado) {
                                // Obtener los datos completos actuales
                                $datosCompletos = $get('_datos_completos') ?? [];

                                // Agregar el catastro seleccionado
                                $datosCompletos['catastro'] = $catastroSeleccionado;

                                // Guardar de vuelta
                                $set('_datos_completos', $datosCompletos);

                                // Llamar al autocompletado
                                DatosCompletosStep::autocompletarDatos($datosCompletos, $set);
                            }
                        })
                        ->columnSpanFull()
                ])
                ->collapsible(false)
                ->compact()
        ];
    }
}