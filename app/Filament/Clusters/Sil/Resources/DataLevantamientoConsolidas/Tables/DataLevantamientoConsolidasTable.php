<?php

namespace App\Filament\Clusters\Sil\Resources\DataLevantamientoConsolidas\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use App\Services\Sil\DataLevantamiento\DataLevantamientoService;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\TextSize;
use Filament\Forms\Components\Select;
use App\Models\EstadoLevantamiento;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Filament\Actions;
use App\Services\Sil\DataLevantamiento\LicenciaLevantamientoService;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;


class DataLevantamientoConsolidasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // Obtener todos los SMls que tienen licencias relacionadas
                $service = app(DataLevantamientoService::class);
                $smlsConLicencias = $service->getSmlsConLicencias();

                // Filtrar solo los registros cuyos SML están en la lista
                if (!empty($smlsConLicencias)) {
                    return $query->whereIn('sml', $smlsConLicencias);
                }
                // Si no hay SMls con licencias, no mostrar ningún registro
                return $query->whereRaw('1 = 0');
            })
            ->defaultSort('feclevan', 'desc')
            ->columns([
                TextColumn::make('cantidad_licencias')
                    ->label('Cantidad Licencias')
                    ->badge()
                    ->color('info')
                    ->state(function ($record) {
                        $service = app(DataLevantamientoService::class);
                        $licencias = $service->getLicenciasRelacionadas($record->sml);
                        return $licencias->count();
                    })
                    ->sortable(false)
                    ->alignCenter(),

                TextColumn::make('licencias_atendidas')
                    ->label('Licencias Atendidas')
                    ->badge()
                    ->color('success')
                    ->state(function ($record) {
                        $dataService = app(DataLevantamientoService::class);
                        $licenciaService = app(LicenciaLevantamientoService::class);

                        // Obtener todas las licencias relacionadas
                        $licencias = $dataService->getLicenciasRelacionadas($record->sml);

                        // Obtener los IDs de las licencias
                        $licIds = $licencias->pluck('lic_id')->toArray();

                        // Contar cuántas tienen estado de levantamiento
                        return $licenciaService->contarLicenciasAtendidasPorLicIds($licIds);
                    })
                    ->sortable(false)
                    ->alignCenter(),

                TextColumn::make('feclevan')
                    ->label('Fecha de Levantamiento')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d/m/Y'))
                    ->searchable(),
                TextColumn::make('sml')
                    ->label('SML')
                    ->searchable(),
                TextColumn::make('mza_urb')
                    ->label('Manzana')
                    ->searchable(),
                TextColumn::make('lot_urb')
                    ->label('Lote')
                    ->searchable(),
                /*TextColumn::make('img_edificacion')
                    ->searchable(),*/
                TextColumn::make('npisos')
                    ->label('N° de Pisos')
                    ->searchable(),
                TextColumn::make('usopredom')
                    ->label('Uso Predominante')
                    ->searchable(),
                TextColumn::make('det_ousos')
                    ->label('Detalles de Otros Usos')
                    ->searchable(),
                TextColumn::make('numacteco')
                    ->label('N° Acteco')
                    ->searchable(),
                TextColumn::make('giro1')
                    ->label('Giro 1')
                    ->searchable(),
                TextColumn::make('img_licencia')
                    ->searchable(),
                TextColumn::make('img_itse')
                    ->searchable(),
                TextColumn::make('giro2')
                    ->searchable(),
                TextColumn::make('img_lic_g2')
                    ->searchable(),
                TextColumn::make('giro3')
                    ->searchable(),
                TextColumn::make('tienelf1')
                    ->searchable(),
                TextColumn::make('img_lic_g3')
                    ->searchable(),
                TextColumn::make('giro4')
                    ->searchable(),
                TextColumn::make('tienelf5')
                    ->searchable(),
                TextColumn::make('img_lf_gir4')
                    ->searchable(),
                TextColumn::make('giro5')
                    ->searchable(),
                TextColumn::make('tienelf2')
                    ->searchable(),
                TextColumn::make('tienelf3')
                    ->searchable(),
                TextColumn::make('tienelf4')
                    ->searchable(),
                TextColumn::make('img_lf_gir31')
                    ->searchable(),
                TextColumn::make('img_lf_gir41')
                    ->searchable(),
                TextColumn::make('img_lf_gir5')
                    ->searchable(),
                TextColumn::make('ei_cam_vigil')
                    ->searchable(),
                TextColumn::make('publicidad')
                    ->searchable(),
                TextColumn::make('ei_estacionam')
                    ->searchable(),
                TextColumn::make('reja')
                    ->searchable(),
                TextColumn::make('ei_otros')
                    ->searchable(),
                TextColumn::make('ei_dotros')
                    ->searchable(),
                TextColumn::make('num_estacionam')
                    ->searchable(),
                TextColumn::make('img_ei')
                    ->searchable(),
                TextColumn::make('numacteco1')
                    ->searchable(),
                TextColumn::make('ae_ambul_giro1')
                    ->searchable(),
                TextColumn::make('ae_tipo_estructura_1')
                    ->searchable(),
                TextColumn::make('otro_amb_01')
                    ->searchable(),
                TextColumn::make('img_ae_amb_01')
                    ->searchable(),
                TextColumn::make('ae_ambul_giro2')
                    ->searchable(),
                TextColumn::make('ae_tipo_estructura_2')
                    ->searchable(),
                TextColumn::make('img_ae_amb_02')
                    ->searchable(),
                TextColumn::make('ae_ambul_giro3')
                    ->searchable(),
                TextColumn::make('ae_tipo_estructura_3')
                    ->searchable(),
                TextColumn::make('otro_amb_02')
                    ->searchable(),
                TextColumn::make('otro_amb_03')
                    ->searchable(),
                TextColumn::make('img_ae_amb_021')
                    ->searchable(),
                TextColumn::make('observa')
                    ->searchable(),
                TextColumn::make('autoriza_gir1')
                    ->searchable(),
                TextColumn::make('certif_itse1')
                    ->searchable(),
                TextColumn::make('cesto_basura')
                    ->searchable(),
                TextColumn::make('estamb_01')
                    ->searchable(),
                TextColumn::make('estamb02')
                    ->searchable(),
                TextColumn::make('correo')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('cantidad_licencias')
                    ->label('Cantidad de Licencias')
                    ->options([
                        '0' => '0 licencias',
                        '1' => '1 licencia',
                        '2' => '2 licencias',
                        '3' => '3 licencias',
                        '4' => '4 licencias',
                        '5' => '5 licencias',
                        '6+' => '6 o más licencias',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $cantidad = $data['value'] ?? null;

                        if ($cantidad === null) {
                            return $query;
                        }

                        $service = app(DataLevantamientoService::class);

                        // Determinar si es "6+" o un número específico
                        if ($cantidad === '6+') {
                            $smls = $service->getSmlsPorCantidadLicencias(6, true);
                        } else {
                            $smls = $service->getSmlsPorCantidadLicencias((int) $cantidad, false);
                        }

                        // Si no hay SMls que cumplan, retornar query vacío
                        if (empty($smls)) {
                            return $query->whereRaw('1 = 0');
                        }

                        return $query->whereIn('sml', $smls);
                    })
                    ->indicator('Cantidad'),

                SelectFilter::make('licencias_atendidas')
                    ->label('Licencias Atendidas')
                    ->options([
                        '0' => '0 atendidas',
                        '1' => '1 atendida',
                        '2' => '2 atendidas',
                        '3' => '3 atendidas',
                        '4' => '4 atendidas',
                        '5' => '5 atendidas',
                        '6+' => '6 o más atendidas',
                    ])
                    ->query(function (Builder $query, array $data) {
                        $cantidad = $data['value'] ?? null;

                        if ($cantidad === null) {
                            return $query;
                        }

                        $service = app(DataLevantamientoService::class);

                        // Determinar si es "6+" o un número específico
                        if ($cantidad === '6+') {
                            $smls = $service->getSmlsPorLicenciasAtendidas(6, true);
                        } else {
                            $smls = $service->getSmlsPorLicenciasAtendidas((int) $cantidad, false);
                        }

                        // Si no hay SMls que cumplan, retornar query vacío
                        if (empty($smls)) {
                            return $query->whereRaw('1 = 0');
                        }

                        return $query->whereIn('sml', $smls);
                    })
                    ->indicator('Atendidas'),

            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->filtersTriggerAction(
                fn(\Filament\Actions\Action $action) => $action
                    ->button()
                    ->label('Filtros')
                    ->modalHeading('Filtros de Levantamiento')
                    ->color('info')
            )
            ->recordActions([
                Action::make('ver-licencias')
                    ->label('Ver Licencias')
                    ->icon('heroicon-m-document-text')
                    ->tooltip('Consultar licencias vinculadas')
                    ->color('teal')
                    ->modalHeading(fn($record) => "Licencias vinculadas al SML: {$record->sml}")
                    ->modalDescription('Consulta todas las licencias de funcionamiento asociadas a este predio')
                    ->modalWidth('2xl')
                    ->infolist(function ($record) {
                        $licencias = app(DataLevantamientoService::class)->getLicenciasRelacionadas($record->sml);

                        if ($licencias->isEmpty()) {
                            return [
                                Section::make()
                                    ->schema([
                                        TextEntry::make('empty_state')
                                            ->label('Sin licencias registradas')
                                            ->state('No se encontraron licencias de funcionamiento vinculadas a este sector, manzana y lote.')
                                            ->icon('heroicon-o-document-text')
                                            ->iconColor('gray')
                                            ->iconSize(IconSize::Large)
                                            ->color('gray')
                                            ->alignCenter()
                                    ])
                            ];
                        }

                        return [
                            // Header mejorado con información contextual
                            Section::make()
                                ->schema([
                                    Grid::make(3)
                                        ->schema([
                                            TextEntry::make('sml_label')
                                                ->label('Código SML')
                                                ->state($record->sml)
                                                ->weight(FontWeight::Bold)
                                                ->size(TextSize::Large)
                                                ->icon('heroicon-m-map-pin')
                                                ->iconColor('teal')
                                                ->columnSpan(2),

                                            TextEntry::make('total')
                                                ->label('Total')
                                                ->state($licencias->count() . ' ' . str('licencia')->plural($licencias->count()))
                                                ->badge()
                                                ->color('teal')
                                                ->size(TextSize::Large)
                                                ->alignStart(),
                                        ]),
                                ])
                                ->columnSpanFull(),

                            // Sección de licencias - cada una collapsible
                            Section::make('Licencias de Funcionamiento')
                                ->description('Listado completo de licencias asociadas')
                                ->icon('heroicon-o-clipboard-document-list')
                                ->schema(
                                    $licencias->map(function ($licencia, $index) {
                                        return Section::make()
                                            ->heading("Licencia #{$licencia->lic_numlic}")
                                            ->description("Año: {$licencia->anno}")
                                            ->icon('heroicon-o-document-text')
                                            ->iconColor('teal')
                                            ->collapsible()
                                            ->collapsed()
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextEntry::make("lic_giro_{$index}")
                                                            ->label('Giro del Negocio')
                                                            ->state($licencia->lic_giro)
                                                            ->weight(FontWeight::Medium)
                                                            ->color('gray')
                                                            ->columnSpanFull(),

                                                        TextEntry::make("tli_descripcion_{$index}")
                                                            ->label('Tipo de Licencia')
                                                            ->state($licencia->tli_descripcion ?? 'No especificado')
                                                            ->badge()
                                                            ->color('blue')
                                                            ->columnSpanFull(),
                                                        TextEntry::make("esl_descripcion_{$index}")
                                                            ->label('Estado de la Licencia')
                                                            ->state($licencia->esl_descripcion ?? 'No especificado')
                                                            ->badge()
                                                            ->color('gray')
                                                            ->columnSpanFull(),
                                                        TextEntry::make("codigo_catastral_{$index}")
                                                            ->label('Código Catastral')
                                                            ->state($licencia->fiu_coduca ?? $licencia->fiu_codcat ?? 'No disponible')
                                                            ->badge()
                                                            ->color('gray')
                                                            ->columnSpanFull(),

                                                        Select::make("estado_levantamiento_{$index}")
                                                            ->label('Estado de Levantamiento')
                                                            ->options(EstadoLevantamiento::pluck('descripcion', 'id'))
                                                            ->placeholder('Seleccionar estado')
                                                            ->searchable()
                                                            ->live()
                                                            ->default(function () use ($licencia) {
                                                                $service = app(LicenciaLevantamientoService::class);
                                                                $estadoActual = $service->obtenerEstadoLevantamiento($licencia->lic_id);
                                                                return $estadoActual?->id_estado_levantamiento;
                                                            }),

                                                        Action::make("guardar_{$index}")
                                                            ->label('Guardar Estado')
                                                            ->color('success')
                                                            ->disabled(fn($get) => empty($get("estado_levantamiento_{$index}")))
                                                            ->action(function ($data, $get) use ($licencia, $index) {
                                                                try {
                                                                    $estadoId = $get("estado_levantamiento_{$index}");

                                                                    $service = app(LicenciaLevantamientoService::class);
                                                                    $service->guardarEstadoLevantamiento(
                                                                        licId: $licencia->lic_id,
                                                                        estadoLevantamientoId: $estadoId
                                                                    );

                                                                    Notification::make()
                                                                        ->title('Estado guardado exitosamente')
                                                                        ->success()
                                                                        ->body("El estado de levantamiento se ha guardado para la licencia #{$licencia->lic_numlic}")
                                                                        ->send();

                                                                } catch (\Exception $e) {
                                                                    Notification::make()
                                                                        ->title('Error al guardar')
                                                                        ->danger()
                                                                        ->body('No se pudo guardar el estado de levantamiento. Por favor, intente nuevamente.')
                                                                        ->send();
                                                                }
                                                            })
                                                    ])
                                            ]);
                                    })->toArray()
                                )
                                ->columnSpanFull(),
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalCancelAction(fn($action) => $action->color('gray'))

            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
