<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Actions\SectionHeaderActions;
use Filament\Actions\Action;
use App\Services\Sil\Syscat\FichaUbicacionService;
use App\Services\Sil\Syscat\ViaService;
use App\Services\Sil\Licencias\TipoEstablecimientoService;
use Illuminate\Support\Facades\Log;

class CatastroSection
{
    public static function make(): Section
    {
        return Section::make('Catastro')
            ->description('Información catastral del predio')
            ->icon('heroicon-o-map-pin')
            ->collapsible()
            ->schema([
                TextInput::make('coduca')
                    ->label('Código Catastral')
                    ->maxLength(50)
                    ->extraAttributes(['inputmode' => 'numeric'])
                    ->rule('regex:/^[0-9]+$/')
                    ->suffixAction(
                        Action::make('seleccionar_catastro')
                            ->icon('heroicon-o-pencil-square')
                            ->tooltip('Seleccionar Catastro')
                            ->modalHeading('Seleccionar Catastro')
                            ->modalDescription('Busque y seleccione el catastro correspondiente')
                            ->modalWidth('7xl')
                            ->modalSubmitActionLabel('Seleccionar')
                            ->modalCancelActionLabel('Cancelar')
                            ->form([
                                Select::make('ficha_seleccionada')
                                    ->label('Buscar Código Catastral')
                                    ->placeholder('Ingrese el código catastral...')
                                    ->searchable()
                                    ->getSearchResultsUsing(function (string $search) {
                                        if (empty($search) || strlen($search) < 3) {
                                            return [];
                                        }

                                        $service = app(FichaUbicacionService::class);
                                        $fichas = $service->buscarPorCoduca($search, false);

                                        return $fichas->mapWithKeys(function ($ficha) {
                                            // Obtener nombre completo de la vía usando ViaService
                                            $viaCompleta = 'N/A';
                                            if ($ficha->via && $ficha->via->via_codvia) {
                                                $viaService = app(ViaService::class);
                                                $viaData = $viaService->obtenerViaCompletaPorCodigo($ficha->via->via_codvia);
                                                $viaCompleta = $viaData->via_completa ?? $ficha->via->via_codvia;
                                            }

                                            $label = sprintf(
                                                'CODUCA: %s | Predial: %s | Mz: %s Lt: %s | Vía: %s | Dpto: %s | Bloque: %s | Zonif: %s | Área: %s m²',
                                                $ficha->fiu_coduca ?? 'N/A',
                                                $ficha->fiu_codpre ?? 'N/A',
                                                $ficha->fiu_manzana ?? 'N/A',
                                                $ficha->fiu_lote ?? 'N/A',
                                                $viaCompleta,
                                                $ficha->fiu_intdpto ?? 'N/A',
                                                $ficha->fiu_blockedif ?? 'N/A',
                                                $ficha->fiu_zonificacion ?? 'N/A',
                                                $ficha->fiu_areaeconomica ? number_format($ficha->fiu_areaeconomica, 2) : 'N/A'
                                            );

                                            return [$ficha->fiu_id => $label];
                                        })->toArray();
                                    })
                                    ->getOptionLabelUsing(function ($value) {
                                        $service = app(FichaUbicacionService::class);
                                        $ficha = $service->obtenerPorId($value);

                                        if (!$ficha) {
                                            return 'Ficha no encontrada';
                                        }

                                        // Obtener nombre completo de la vía usando ViaService
                                        $viaCompleta = 'N/A';
                                        if ($ficha->via && $ficha->via->via_codvia) {
                                            $viaService = app(ViaService::class);
                                            $viaData = $viaService->obtenerViaCompletaPorCodigo($ficha->via->via_codvia);
                                            $viaCompleta = $viaData->via_completa ?? $ficha->via->via_codvia;
                                        }

                                        return sprintf(
                                            'CODUCA: %s | Predial: %s | Mz: %s Lt: %s | Vía: %s | Dpto: %s | Bloque: %s | Zonif: %s | Área: %s m²',
                                            $ficha->fiu_coduca ?? 'N/A',
                                            $ficha->fiu_codpre ?? 'N/A',
                                            $ficha->fiu_manzana ?? 'N/A',
                                            $ficha->fiu_lote ?? 'N/A',
                                            $viaCompleta,
                                            $ficha->fiu_intdpto ?? 'N/A',
                                            $ficha->fiu_blockedif ?? 'N/A',
                                            $ficha->fiu_zonificacion ?? 'N/A',
                                            $ficha->fiu_areaeconomica ? number_format($ficha->fiu_areaeconomica, 2) : 'N/A'
                                        );
                                    })
                                    ->required()
                                    ->helperText('Ingrese al menos 3 caracteres para buscar')
                                    ->columnSpanFull(),
                            ])
                            ->action(function (array $data, callable $set, callable $get) {
                                // Obtener la ficha seleccionada
                                $fichaService = app(FichaUbicacionService::class);
                                $ficha = $fichaService->obtenerPorId($data['ficha_seleccionada']);

                                if ($ficha) {
                                    // Construir via_completa usando método escalable
                                    $viaCompleta = self::buildViaCompleta($ficha);

                                    // Setear todos los campos del formulario con los datos de la ficha
                                    $set('via_completa', $viaCompleta);
                                    $set('coduca', $ficha->fiu_coduca);
                                    $set('fiu_id', $ficha->fiu_id);
                                    $set('mz', $ficha->fiu_manzana);
                                    $set('lote', $ficha->fiu_lote);
                                    $set('zonificacion', $ficha->fiu_zonificacion);
                                    $set('area_economica', $ficha->fiu_areaeconomica);
                                    $set('numvia', $ficha->fiu_numvia);
                                    $set('intdpto', $ficha->fiu_intdpto);
                                    $set('blockedif', $ficha->fiu_blockedif);
                                    $set('codpredio', $ficha->fiu_codpre);

                                    // Setear urbanización si existe
                                    if ($ficha->urbanizacion && $ficha->urbanizacion->urb_descurb) {
                                        $set('descurb', $ficha->urbanizacion->urb_descurb);
                                    }

                                    // Actualizar dirección completa
                                    $set('direccion', self::buildDireccionCompleta($get));

                                    // Autocompletar Tipo Establecimiento desde CODUCA
                                    Log::info('CatastroSection: Iniciando autocompletado de Tipo Establecimiento', [
                                        'fiu_coduca' => $ficha->fiu_coduca,
                                        'fiu_id' => $ficha->fiu_id
                                    ]);

                                    $tipoEstService = app(TipoEstablecimientoService::class);
                                    $tesId = $tipoEstService->obtenerTipoEstablecimientoPorCoduca($ficha->fiu_coduca);

                                    if ($tesId) {
                                        $set('tipo_establecimientos', $tesId);
                                        Log::info('CatastroSection: Tipo Establecimiento autocompletado exitosamente', [
                                            'fiu_coduca' => $ficha->fiu_coduca,
                                            'tes_id' => $tesId
                                        ]);
                                    } else {
                                        Log::warning('CatastroSection: No se pudo autocompletar Tipo Establecimiento', [
                                            'fiu_coduca' => $ficha->fiu_coduca,
                                            'razon' => 'No se encontró correspondencia CODUCA -> CODUSO -> TipoEstablecimiento'
                                        ]);
                                    }
                                }
                            })
                    )
                    ->dehydrated(),
                TextInput::make('codpredio')->label('Código Predial')
                    ->maxLength(50)
                    ->rule('regex:/^[0-9]+$/')
                    //->disabled()
                    ->dehydrated(),
                TextInput::make('descurb')->label('Urbanización')->maxLength(255)->columnSpanFull()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(callable $set, callable $get) => $set('direccion', self::buildDireccionCompleta($get)))
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('via_completa')->label('Vía')->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(callable $set, callable $get) => $set('direccion', self::buildDireccionCompleta($get)))
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('numvia')->label('Número')->maxLength(20)->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(callable $set, callable $get) => $set('direccion', self::buildDireccionCompleta($get)))
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('intdpto')->label('Dpto.')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(callable $set, callable $get) => $set('direccion', self::buildDireccionCompleta($get)))
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('blockedif')->label('Bloque')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(callable $set, callable $get) => $set('direccion', self::buildDireccionCompleta($get)))
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('mz')->label('Manzana')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(callable $set, callable $get) => $set('direccion', self::buildDireccionCompleta($get)))
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('lote')->label('Lote')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(callable $set, callable $get) => $set('direccion', self::buildDireccionCompleta($get)))
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('zonificacion')->label('Zonificación')->maxLength(100)->dehydrated(),
                TextInput::make('area_economica')->label('Área Económica')->numeric()->step(0.01)->suffix('m²')->formatStateUsing(fn($state) => $state ? number_format((float) $state, 2, '.', '') : null)->extraInputAttributes(['onchange' => "if(this.value) this.value = parseFloat(this.value).toFixed(2)"])
                    //->disabled()
                    ->dehydrated(),

                Hidden::make('fiu_id')->label('')
                    //->maxLength(20)
                    ->disabled()
                    ->dehydrated(),
            ])
            ->headerActions(SectionHeaderActions::make('catastro'))
            ->columnSpanFull()
            ->columns(3);
    }



    /**
     * Construye el nombre completo de la vía a partir de una ficha de ubicación
     * 
     * @param \App\Models\FichaUbicacion $ficha
     * @return string
     */
    private static function buildViaCompleta($ficha): string
    {
        // Obtener nombre completo de la vía (ej: "Av. Los Pinos")
        if ($ficha->via && $ficha->via->via_codvia) {
            $viaService = app(ViaService::class);
            $viaData = $viaService->obtenerViaCompletaPorCodigo($ficha->via->via_codvia);
            if ($viaData && $viaData->via_completa) {
                return trim($viaData->via_completa);
            }
        }
        return '';
    }


    /**
     * Construye la dirección completa concatenando todos los componentes
     * Formato: Via + Nro + Dpto + Bloque + Mz + Lt + Urbanización
     * 
     * @param callable $get
     * @return string
     */
    public static function buildDireccionCompleta(callable $get): string
    {
        $partes = [];

        // 1. Vía completa
        $viaCompleta = trim($get('via_completa') ?? '');
        if (!empty($viaCompleta)) {
            $partes[] = $viaCompleta;
        }

        // 2. Número
        $numvia = trim($get('numvia') ?? '');
        if (!empty($numvia)) {
            $partes[] = 'NRO ' . $numvia;
        }

        // 3. Departamento
        $intdpto = trim($get('intdpto') ?? '');
        if (!empty($intdpto)) {
            $partes[] = 'DPTO ' . $intdpto;
        }

        // 4. Bloque
        $blockedif = trim($get('blockedif') ?? '');
        if (!empty($blockedif)) {
            $partes[] = 'BLOQUE ' . $blockedif;
        }

        // 5. Manzana
        $mz = trim($get('mz') ?? '');
        if (!empty($mz)) {
            $partes[] = 'MZ ' . $mz;
        }

        // 6. Lote
        $lote = trim($get('lote') ?? '');
        if (!empty($lote)) {
            $partes[] = 'LT ' . $lote;
        }

        // 7. Urbanización
        $descurb = trim($get('descurb') ?? '');
        if (!empty($descurb)) {
            $partes[] = 'URB. ' . $descurb;
        }

        return trim(implode(' ', $partes));
    }

}
