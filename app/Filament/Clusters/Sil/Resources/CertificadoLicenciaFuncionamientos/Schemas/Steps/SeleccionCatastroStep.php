<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps;

use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard\Step;

class SeleccionCatastroStep
{
    public static function make(): Step
    {
        return Step::make('Selección de Catastro')
            ->description('Resolver duplicados')
            ->icon('heroicon-m-clipboard-document-check')
            ->schema(fn($get) => self::generarInterfazSeleccion($get))
            ->visible(fn($get) => !empty($get('_catastro_coincidencias')));
    }

    private static function generarInterfazSeleccion(callable $get): array
    {
        $coincidencias = $get('_catastro_coincidencias') ?? [];
        $cantidad = is_countable($coincidencias) ? count($coincidencias) : 0;

        // Preparamos los arrays para el componente Radio
        $opciones = [];
        $descripciones = [];

        foreach ($coincidencias as $item) {
            // Normalización de datos
            $fiuId = is_object($item) ? ($item->fiu_id ?? null) : ($item['fiu_id'] ?? null);
            $via = is_object($item) ? ($item->via_completa ?? 'Sin vía') : ($item['via_completa'] ?? 'Sin vía');

            if (!$fiuId)
                continue;

            // Opción principal: El ID
            $opciones[$fiuId] = "FIU ID: {$fiuId}";

            // Descripción nativa: Solo la dirección
            $descripciones[$fiuId] = "Dirección: {$via}";
        }

        return [
            Section::make('Múltiples registros encontrados')
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
                        ->columnSpanFull()
                ])
                ->collapsible(false)
                ->compact()
        ];
    }
}