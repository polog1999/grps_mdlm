<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Actions;

use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;
class VerDatoItseAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'ver_dato_itse';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('ITSE')
            ->icon('heroicon-o-shield-check')
            ->color(Color::Blue)
            ->modalHeading('Datos de ITSE')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar')
            ->form([
                Select::make('cin_id')
                    ->label('Certificado ITSE')
                    ->options(function () {
                        return \App\Models\CertificadoInspeccion::query()
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($itse) {
                                return [$itse->cin_id => $itse->cin_numero];
                            });
                    })
                    ->getSearchResultsUsing(
                        fn(string $search): array => \App\Models\CertificadoInspeccion::where('cin_numero', 'ilike', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($itse) {
                                return [$itse->cin_id => $itse->cin_numero];
                            })
                            ->toArray()
                    )
                    ->searchable()
                    ->placeholder('Busque por N° de certificado ITSE...')
                    ->live(),

                Group::make()
                    ->columns(1)
                    ->schema([
                        Section::make('Información del Certificado')
                            ->description('Datos principales de identificación y estado del certificado')
                            ->icon('heroicon-o-document-text')
                            ->columns(3)
                            ->schema([
                                Placeholder::make('cin_numero')
                                    ->label('Número de Certificado')
                                    ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_numero')),

                                Placeholder::make('cin_anio')
                                    ->label('Año')
                                    ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_anio')),

                                Placeholder::make('cin_expediente')
                                    ->label('Expediente')
                                    ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_expediente')),

                                Placeholder::make('cin_licencia')
                                    ->label('Número de Licencia')
                                    ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_licencia') ?: 'No especificado'),

                                Placeholder::make('cin_resolucion_completa')
                                    ->label('Resolución')
                                    ->columnSpan(2)
                                    ->content(function (Get $get) {
                                        $itse = self::getItseData($get('cin_id'));
                                        return $itse ? trim($itse->cin_resolucion . ' ' . $itse->cin_resolucion_sigla) : null;
                                    }),
                            ]),

                        Section::make('Vigencia y Capacidad')
                            ->description('Periodo de validez y características del establecimiento')
                            ->icon('heroicon-o-calendar')
                            ->columns(4)
                            ->schema([
                                Placeholder::make('cin_fecha')
                                    ->label('Fecha de Emisión')
                                    ->content(fn(Get $get) => self::formatDate(self::getItseValue($get('cin_id'), 'cin_fecha'))),

                                Placeholder::make('cin_fec_inicio')
                                    ->label('Inicio de Vigencia')
                                    ->content(fn(Get $get) => self::formatDate(self::getItseValue($get('cin_id'), 'cin_fec_inicio')) ?: 'No especificada'),

                                Placeholder::make('cin_fec_fin')
                                    ->label('Fin de Vigencia')
                                    ->visible(fn(Get $get) => !self::getItseValue($get('cin_id'), 'cin_indeterminado'))
                                    ->content(fn(Get $get) => self::formatDate(self::getItseValue($get('cin_id'), 'cin_fec_fin')) ?: 'No especificada'),

                                Placeholder::make('cin_indeterminado')
                                    ->label('Temporalidad')
                                    ->icon('heroicon-m-check-circle')
                                    ->iconColor('info')
                                    ->content('Indeterminado'),
                                Placeholder::make('cin_capacidad')
                                    ->label('Capacidad')
                                    ->content(function (Get $get) {
                                        $val = self::getItseValue($get('cin_id'), 'cin_capacidad');
                                        return $val ? $val . ' personas' : 'No especificada';
                                    }),

                                Placeholder::make('cin_area')
                                    ->label('Área Total')
                                    ->columnSpan(2)
                                    ->content(function (Get $get) {
                                        $val = self::getItseValue($get('cin_id'), 'cin_area');
                                        return $val ? number_format((float) $val, 2, '.', ',') . ' m²' : 'No especificada';
                                    }),
                            ]),

                    ])->visible(fn(Get $get) => filled($get('cin_id'))),

                Section::make('Establecimiento')
                    ->description('Información del establecimiento inspeccionado')
                    ->icon('heroicon-o-building-office')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('tipoEdificacion')
                            ->label('Tipo de Edificación')
                            ->columnSpanFull()
                            ->content(function (Get $get) {
                                $itse = self::getItseData($get('cin_id'));
                                return $itse?->tipoEdificacion?->tie_descripcion;
                            }),

                        Placeholder::make('cin_establecimiento')
                            ->label('Nombre del Establecimiento')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_establecimiento')),

                        Placeholder::make('cin_solicitante')
                            ->label('Solicitante')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_solicitante') ?: 'No especificado'),

                        Placeholder::make('cin_ubicacion')
                            ->label('Ubicación')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_ubicacion') ?: 'No especificada'),

                        Placeholder::make('cin_giro')
                            ->label('Giro del Negocio')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_giro') ?: 'No especificado'),
                    ])->visible(fn(Get $get) => filled($get('cin_id'))),

                Section::make('Información Adicional')
                    ->description('Detalles técnicos y administrativos del certificado')
                    ->icon('heroicon-o-document-text')
                    ->columnSpanFull()
                    ->schema([
                        Placeholder::make('cin_nota')
                            ->label('Nota')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => self::getItseValue($get('cin_id'), 'cin_nota') ?: 'Sin notas'),
                    ])->visible(fn(Get $get) => filled($get('cin_id'))),
            ]);
    }

    private static array $itseCache = [];

    /**
     * Obtiene el modelo ITSE.
     */
    private static function getItseData($id)
    {
        if (!$id)
            return null;
        if (!array_key_exists($id, self::$itseCache)) {
            self::$itseCache[$id] = \App\Models\CertificadoInspeccion::with('tipoEdificacion')->find($id);
        }
        return self::$itseCache[$id];
    }

    /**
     * Obtiene una propiedad específica del modelo.
     */
    private static function getItseValue($id, $key)
    {
        $itse = self::getItseData($id);
        return $itse ? $itse->{$key} : null;
    }

    private static function formatDate(?string $date): ?string
    {
        if (!$date)
            return null;
        return \Carbon\Carbon::parse($date)->format('d/m/Y');
    }
}
