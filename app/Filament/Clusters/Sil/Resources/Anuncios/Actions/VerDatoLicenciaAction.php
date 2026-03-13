<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Actions;

use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;
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
                Select::make('lic_id')
                    ->label('Licencia')
                    ->options(function () {
                        return \App\Models\CertificadoLicenciaFuncionamiento::query()
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($licencia) {
                                return [$licencia->lic_id => $licencia->lic_numlic];
                            });
                    })
                    ->getSearchResultsUsing(
                        fn(string $search): array => \App\Models\CertificadoLicenciaFuncionamiento::where('lic_numlic', 'ilike', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($licencia) {
                                return [$licencia->lic_id => $licencia->lic_numlic];
                            })
                            ->toArray()
                    )
                    ->searchable()
                    ->placeholder('Busque por N° de licencia...')
                    ->live(),

                Section::make('Información del Expediente y Solicitante')
                    ->description('Datos administrativos y del solicitante o administrador')
                    ->icon('heroicon-o-folder-open')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('exp_num')
                            ->label('N° Expediente')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'EXPEDIENTE_NRO')),

                        Placeholder::make('exp_fec')
                            ->label('Fecha Expediente')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'lic_expfec') ? date('d/m/Y', strtotime(self::getDatoDirecto($get('lic_id'), 'lic_expfec'))) : '-'),

                        Placeholder::make('exp_razsoc')
                            ->label('Razón Social / Administrado')
                            ->columnSpan(2)
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'RAZON_SOCIAL')),

                        Placeholder::make('numdoc')
                            ->label('RUC / Documento Identidad')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'RUC')),

                        Placeholder::make('numtel')
                            ->label('Teléfono')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'TELEFONO')),

                        Placeholder::make('correo')
                            ->label('Correo Electrónico')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'EMAIL')),

                        Placeholder::make('domfis')
                            ->label('Domicilio Fiscal')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'UBICACION')),
                    ])
                    ->visible(fn(Get $get) => filled($get('lic_id'))),

                Section::make('Datos Catastrales y Ubicación')
                    ->description('Información del predio y su ubicación catastral')
                    ->icon('heroicon-o-map')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('codpredio')
                            ->label('Código Predial')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'CODIGO_PREDIAL')),

                        Placeholder::make('coduca')
                            ->label('Código Catastral')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'CODIGO_CATASTRAL')),

                        Placeholder::make('area_economica')
                            ->label('Área (m²)')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'AREA') ? self::getDatoDirecto($get('lic_id'), 'AREA') . ' m²' : '-'),

                        Placeholder::make('direccion')
                            ->label('Dirección Comercial')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'LIC_DIRECCION') ?? \App\Models\CertificadoLicenciaFuncionamiento::find($get('lic_id'))?->lic_direccion),

                        Placeholder::make('descurb')
                            ->label('Urbanización')
                            ->columnSpan(2)
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'URBANIZACION')),

                        Placeholder::make('zonificacion')
                            ->label('Zonificación')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'ZONIFICACION')),
                    ])
                    ->visible(fn(Get $get) => filled($get('lic_id'))),

                Section::make('Características de la Licencia')
                    ->description('Detalles específicos otorgados en la licencia')
                    ->icon('heroicon-o-document-check')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('numero_licencia')
                            ->label('N° Licencia')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'NUMERO_LICENCIA') ?? \App\Models\CertificadoLicenciaFuncionamiento::find($get('lic_id'))?->lic_numlic),

                        Placeholder::make('tipo_licencia')
                            ->label('Tipo de Licencia')
                            ->content(fn(Get $get) => \App\Models\CertificadoLicenciaFuncionamiento::with('tipoLicencia')->find($get('lic_id'))?->tipoLicencia?->tli_descripcion),

                        Placeholder::make('n_resolucion')
                            ->label('N° Resolución')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'RESOLUCION_NRO') ?? \App\Models\CertificadoLicenciaFuncionamiento::find($get('lic_id'))?->lic_resnum),

                        Placeholder::make('fecha_resolucion')
                            ->label('Fecha Resolución')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'RESOLUCION_FECHA') ? date('d/m/Y', strtotime(self::getDatoDirecto($get('lic_id'), 'RESOLUCION_FECHA'))) : '-'),

                        Placeholder::make('fecha_emision')
                            ->label('Fecha Emisión')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'FECHA_EMISION') ? date('d/m/Y', strtotime(self::getDatoDirecto($get('lic_id'), 'FECHA_EMISION'))) : '-'),

                        Placeholder::make('fecha_vencimiento')
                            ->label('Fecha Vencimiento')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'FECHA_VENCIMIENTO') ? date('d/m/Y', strtotime(self::getDatoDirecto($get('lic_id'), 'FECHA_VENCIMIENTO'))) : '-'),

                        Placeholder::make('mype')
                            ->label('Clasificación MYPE')
                            ->content(function (Get $get) {
                                $state = self::getDatoDirecto($get('lic_id'), 'MYPE') ?? \App\Models\CertificadoLicenciaFuncionamiento::find($get('lic_id'))?->lic_mype;
                                return (strtolower((string) ($state ?? 'false')) === 'true' || $state == 1 || $state == '1') ? 'Sí' : 'No';
                            }),

                        Placeholder::make('local')
                            ->label('Nombre de Local / Galerías')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'lcc_local')),

                        Placeholder::make('compatibilidad')
                            ->label('Compatibilidad')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'lic_compatibilidad') ?? \App\Models\CertificadoLicenciaFuncionamiento::find($get('lic_id'))?->lic_compatibilidad ?? 'NO ESPECIFICADO'),

                        Placeholder::make('nro_compatibilidad')
                            ->label('Nro. Compatibilidad')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'lic_compatibilidadnumero') ?? \App\Models\CertificadoLicenciaFuncionamiento::find($get('lic_id'))?->lic_compatibilidadnumero ?? '-'),

                        Placeholder::make('fecha_compatibilidad')
                            ->label('Fecha Compatibilidad')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'lic_compatibilidadfecha') ? date('d/m/Y', strtotime(self::getDatoDirecto($get('lic_id'), 'lic_compatibilidadfecha') ?? \App\Models\CertificadoLicenciaFuncionamiento::find($get('lic_id'))?->lic_compatibilidadfecha)) : '-'),
                    ])
                    ->visible(fn(Get $get) => filled($get('lic_id'))),

                Section::make('Detalles de Giros Comerciales')
                    ->description('Giros aprobados por la licencia')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Placeholder::make('giros_list')
                            ->label('Giros Registrados')
                            ->columnSpanFull()
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'GIRO_ESPECIFICOS') ?: self::getDatoDirecto($get('lic_id'), 'GIRO')),
                    ])
                    ->visible(fn(Get $get) => filled($get('lic_id'))),

                Section::make('Observaciones y Notas')
                    ->schema([
                        Placeholder::make('observaciones')
                            ->label('Observación de la Licencia')
                            ->content(fn(Get $get) => self::getDatoDirecto($get('lic_id'), 'OBSERVACIONES') ?? \App\Models\CertificadoLicenciaFuncionamiento::find($get('lic_id'))?->lic_licobs ?? 'Ninguna'),
                    ])
                    ->collapsible()
                    ->collapsed(true)
                    ->visible(fn(Get $get) => filled($get('lic_id'))),
            ]);
    }

    private static array $datosDirectosCache = [];

    /**
     * Helper para obtener datos del SP de Licencias por ID.
     */
    private static function getDatoDirecto($licId, $key)
    {
        if (!$licId) {
            return null;
        }

        if (!isset(self::$datosDirectosCache[$licId])) {
            try {
                $service = app(\App\Services\Sil\Licencias\LicenciaService::class);
                self::$datosDirectosCache[$licId] = $service->obtenerDatosPorIdLicenciaDirecta($licId);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Error obteniendo datos directos Action para licencia ' . $licId, ['error' => $e->getMessage()]);
                self::$datosDirectosCache[$licId] = null;
            }
        }

        if (!self::$datosDirectosCache[$licId]) {
            return null;
        }

        return self::$datosDirectosCache[$licId]->{$key} ?? null;
    }
}
