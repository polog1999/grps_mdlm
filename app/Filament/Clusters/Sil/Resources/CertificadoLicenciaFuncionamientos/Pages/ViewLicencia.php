<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Pages;

use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Log;

class ViewLicencia extends ViewRecord
{
    protected static string $resource = CertificadoLicenciaFuncionamientoResource::class;

    public function mount(int|string $record): void
    {
        abort_unless(auth()->user()->can('view_details::certificado_licencia_funcionamiento'), 403, 'No tiene permisos para ver los detalles de esta licencia.');
        parent::mount($record);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Expediente y Solicitante')
                    ->description('Datos administrativos y del solicitante o administrador')
                    ->icon('heroicon-o-folder-open')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('exp_num')
                            ->label('N° Expediente')
                            ->badge()
                            ->color('primary')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'EXPEDIENTE_NRO')),

                        TextEntry::make('exp_fec')
                            ->label('Fecha Expediente')
                            ->date('d/m/Y')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'lic_expfec')),

                        TextEntry::make('exp_razsoc')
                            ->label('Razón Social / Administrado')
                            ->columnSpan(2)
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'RAZON_SOCIAL')),

                        TextEntry::make('numdoc')
                            ->label('RUC / Documento Identidad')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'RUC')),

                        TextEntry::make('numtel')
                            ->label('Teléfono')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'TELEFONO')),

                        TextEntry::make('correo')
                            ->label('Correo Electrónico')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'EMAIL')),

                        TextEntry::make('domfis')
                            ->label('Domicilio Fiscal')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'UBICACION')),
                    ]),

                Section::make('Datos Catastrales y Ubicación')
                    ->description('Información del predio y su ubicación catastral')
                    ->icon('heroicon-o-map')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('codpredio')
                            ->label('Código Predial')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'CODIGO_PREDIAL')),

                        TextEntry::make('coduca')
                            ->label('Código Catastral')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'CODIGO_CATASTRAL')),

                        TextEntry::make('area_economica')
                            ->label('Área (m²)')
                            ->numeric()
                            ->suffix(' m²')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'AREA')),

                        TextEntry::make('direccion')
                            ->label('Dirección Comercial')
                            ->columnSpanFull()
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'LIC_DIRECCION') ?? $record->lic_direccion),

                        TextEntry::make('descurb')
                            ->label('Urbanización')
                            ->columnSpan(2)
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'URBANIZACION')),

                        TextEntry::make('zonificacion')
                            ->label('Zonificación')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'ZONIFICACION')),
                    ]),

                Section::make('Características de la Licencia')
                    ->description('Detalles específicos otorgados en la licencia')
                    ->icon('heroicon-o-document-check')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('numero_licencia')
                            ->label('N° Licencia')
                            ->badge()
                            ->color('success')
                            ->size('lg')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'NUMERO_LICENCIA') ?? $record->lic_numlic),

                        TextEntry::make('tipo_licencia')
                            ->label('Tipo de Licencia')
                            ->badge()
                            ->color('info')
                            ->getStateUsing(fn($record) => $record->tipoLicencia?->tli_descripcion),

                        TextEntry::make('n_resolucion')
                            ->label('N° Resolución')
                            ->badge()
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'RESOLUCION_NRO') ?? $record->lic_resnum),

                        TextEntry::make('fecha_resolucion')
                            ->label('Fecha Resolución')
                            ->date('d/m/Y')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'RESOLUCION_FECHA')),

                        TextEntry::make('fecha_emision')
                            ->label('Fecha Emisión')
                            ->date('d/m/Y')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'FECHA_EMISION')),

                        TextEntry::make('fecha_vencimiento')
                            ->label('Fecha Vencimiento')
                            ->date('d/m/Y')
                            ->badge()
                            ->color(fn($state) => $state ? (\Carbon\Carbon::parse($state)->isPast() ? 'danger' : 'success') : 'gray')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'FECHA_VENCIMIENTO')),

                        TextEntry::make('mype')
                            ->label('Clasificación MYPE')
                            ->badge()
                            ->color(fn($state) => strtolower((string) ($state ?? 'false')) === 'true' || $state == 1 || $state == '1' ? 'success' : 'gray')
                            ->formatStateUsing(fn($state) => strtolower((string) ($state ?? 'false')) === 'true' || $state == 1 || $state == '1' ? 'Sí' : 'No')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'MYPE') ?? $record->lic_mype),



                        TextEntry::make('local')
                            ->label('Nombre de Local / Galerías')
                            ->columnSpanFull()
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'lcc_local')),

                        TextEntry::make('compatibilidad')
                            ->label('Compatibilidad')
                            ->badge()
                            ->color(fn($state) => $state === 'CONFORME' ? 'success' : ($state === 'NO CONFORME' ? 'danger' : 'warning'))
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'lic_compatibilidad') ?? $record->lic_compatibilidad ?? 'NO ESPECIFICADO'),

                        TextEntry::make('nro_compatibilidad')
                            ->label('Nro. Compatibilidad')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'lic_compatibilidadnumero') ?? $record->lic_compatibilidadnumero ?? '-'),

                        TextEntry::make('fecha_compatibilidad')
                            ->label('Fecha Compatibilidad')
                            ->date('d/m/Y')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'lic_compatibilidadfecha') ?? $record->lic_compatibilidadfecha),

                        Section::make('Información de la Baja')
                            ->schema([
                                TextEntry::make('licenciaBaja.lib_expnum')
                                    ->label('N° Expediente de Baja')
                                    ->badge()
                                    ->color('danger'),

                                TextEntry::make('licenciaBaja.lib_resnum')
                                    ->label('N° Resolución de Baja')
                                    ->badge()
                                    ->color('danger'),

                                TextEntry::make('licenciaBaja.lib_fecharesolucion')
                                    ->label('Fecha Resolución de Baja')
                                    ->date('d/m/Y'),
                            ])
                            ->columns(3)
                            ->visible(fn($record) => $record->licenciaBaja()->exists())
                            ->columnSpanFull()
                            ->compact(),

                    ]),

                Section::make('Detalles de Giros Comerciales')
                    ->description('Giros aprobados por la licencia')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        TextEntry::make('giros_list')
                            ->label('Giros Registrados')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'GIRO_ESPECIFICOS') ?: self::getDatoDirecto($record, 'GIRO'))->columnSpanFull(),
                    ]),

                Section::make('Observaciones y Notas')
                    ->schema([
                        TextEntry::make('observaciones')
                            ->label('Observación de la Licencia')
                            ->placeholder('Ninguna')
                            ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'OBSERVACIONES') ?? $record->lic_licobs),
                    ])
                    ->collapsible()
                    ->collapsed(true),
            ]);
    }

    /**
     * Cachea localmente la llamada al SP durante el ciclo de vida de la request
     * para no llamar a la base de datos por cada TextEntry del Infolist.
     */
    private static function getDatoDirecto($record, $key)
    {
        if (!$record || !$record->lic_id)
            return null;

        if (!isset($record->datos_directos_cache)) {
            try {
                $service = app(\App\Services\Sil\Licencias\LicenciaService::class);
                $record->datos_directos_cache = $service->obtenerDatosPorIdLicenciaDirecta($record->lic_id);
            } catch (\Throwable $e) {
                Log::error('Error obteniendo datos directos Infolist para licencia ' . $record->lic_id, ['error' => $e->getMessage()]);
                $record->datos_directos_cache = null;
            }
        }

        if (!$record->datos_directos_cache)
            return null;

        return $record->datos_directos_cache->{$key} ?? null;
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label('Volver al listado')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
}

