<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Tables;

use App\Models\CertificadoLicenciaFuncionamiento;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Collection;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\RecordActionsPosition;

class CertificadoLicenciaFuncionamientosTable
{

    protected static $service;

    public static function configure(Table $table): Table
    {
        if (!isset(self::$service)) {
            self::$service = new CertificadoLincenciaFuncionamientoService();
        }

        return $table
            ->modifyQueryUsing(function (Builder $query, $livewire) {
                /*$hasFilters = false;
                try {
                    $filters = $livewire->tableFilters ?? [];

                    $hasFilters = collect($filters)
                        ->filter(function ($filter) {
                            if (is_array($filter) || $filter instanceof Collection) {
                                return collect($filter)
                                    ->flatten()
                                    ->reject(fn($v) => $v === null || $v === '' )
                                    ->isNotEmpty();
                            }

                            return !is_null($filter) && $filter !== '';
                        })
                        ->isNotEmpty();
                } catch (\Throwable $e) {
                    Log::debug('Error parsing table filters', ['error' => $e->getMessage()]);
                    $hasFilters = false;
                }

                $searchTerm = null;
                if (isset($livewire->tableSearch)) {
                    $searchTerm = $livewire->tableSearch;
                } elseif (method_exists($livewire, 'getTableSearch')) {
                    try {
                        $searchTerm = $livewire->getTableSearch();
                    } catch (\Throwable $e) {
                        $searchTerm = null;
                    }
                }
                $hasSearch = !empty($searchTerm) && trim((string) $searchTerm) !== '';

                // Apply base condition always
                $query->where('lic_filaeliminada', false);

                // Only allow results when there's an actual search term or active filters
                if ($hasSearch || $hasFilters) {
                    return $query;
                }

                return $query->whereRaw('1 = 0');*/

                $query->where('lic_filaeliminada', false);
                return $query;
            })

            ->defaultSort('lic_filafecha', 'desc')
            ->defaultPaginationPageOption(10)
            ->columns([
                //TextColumn::make('lic_id')->label('ID')->sortable()->searchable(),
                TextColumn::make('lic_numlic')->label('Licencia')->sortable()->searchable(),
                TextColumn::make('lic_expnum')->label('Expediente')->sortable()->searchable(),

                TextColumn::make('codcat')
                    ->label('CodCat')
                    ->getStateUsing(function ($record) {
                        $lic_id = $record->lic_id ?? null;
                        if (!$lic_id) {
                            return null;
                        }

                        try {
                            return self::$service->obtenerCodCatPorExpedienteConVuLicencias($lic_id);
                        } catch (\Throwable $e) {
                            Log::error('Error obteniendo codcat para expediente ' . $lic_id, ['error' => $e->getMessage()]);
                            return null;
                        }
                    })
                    ->sortable(),

                TextColumn::make('lic_razonsocial')->label('Razón Social')->sortable()->searchable(),
                TextColumn::make('tipoLicencia.tli_descripcion')->label('Tipo Licencia')->sortable(),
                TextColumn::make('tipoEstadoLicencia.esl_descripcion')->label('Estado')->sortable(),
                /* 
             TextColumn::make('lic_direccion')->label('Dirección Lic.')->sortable()->searchable(),
             TextColumn::make('lic_direccion_sol')
             ->label('Dirección Sol.')
             ->getStateUsing(function ($record) {
                 $lic_id = $record->lic_id ?? null;
                 if (! $lic_id) {
                     return null;
                 }

                 try {
                     return self::$service->obtenerDireccionSolicitantePorIdLicencia($lic_id);
                 } catch (\Throwable $e) {
                     Log::error('Error obteniendo codcat para expediente ' . $lic_id, ['error' => $e->getMessage()]);
                     return null;
                 }
             })
             ->sortable(),
             */
            ])
            ->filters([
                //Razon Social
                SelectFilter::make('lic_razonsocial')
                    ->label('Razón Social')
                    ->options(fn() => CertificadoLicenciaFuncionamiento::query()
                        ->distinct()
                        ->whereNotNull('lic_razonsocial')
                        ->where('lic_razonsocial', '!=', '')
                        ->orderBy('lic_razonsocial', 'asc')
                        ->pluck('lic_razonsocial', 'lic_razonsocial')
                        ->toArray())
                    ->searchable()
                    ->indicator('Razón Social')
                    ->placeholder('Buscar razón social...')
                    ->native(false),

                SelectFilter::make('lic_numlic')
                    ->label('Número de Licencia')
                    ->options(fn() => CertificadoLicenciaFuncionamiento::query()
                        ->distinct()
                        ->whereNotNull('lic_numlic')
                        ->where('lic_numlic', '!=', '')
                        ->orderBy('lic_numlic', 'asc')
                        ->pluck('lic_numlic', 'lic_numlic')
                        ->toArray())
                    ->searchable()
                    ->indicator('Número de Licencia')
                    ->placeholder('Buscar número de licencia...')
                    ->native(false),

                SelectFilter::make('lic_expnum')
                    ->label('Número de Expediente')
                    ->options(fn() => CertificadoLicenciaFuncionamiento::query()
                        ->distinct()
                        ->whereNotNull('lic_expnum')
                        ->where('lic_expnum', '!=', '')
                        ->orderBy('lic_expnum', 'asc')
                        ->pluck('lic_expnum', 'lic_expnum')
                        ->toArray())
                    ->searchable()
                    ->indicator('Número de Expediente')
                    ->placeholder('Buscar número de expediente...')
                    ->native(false),

                SelectFilter::make('tli_id')
                    ->label('Tipo de Licencia')
                    ->relationship('tipoLicencia', 'tli_descripcion')
                    ->searchable()
                    ->preload()
                    ->indicator('Tipo de Licencia')
                    ->placeholder('Todos los tipos'),

                SelectFilter::make('esl_id')
                    ->label('Estado de Licencia')
                    ->relationship('tipoEstadoLicencia', 'esl_descripcion')
                    ->searchable()
                    ->preload()
                    ->indicator('Estado de Licencia')
                    ->placeholder('Todos los estados'),

                Filter::make('codigocatastral')
                    ->form([
                        TextInput::make('codigocatastral')
                            ->label('Código Catastral')
                            ->placeholder('Ingrese código catastral...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            !empty($data['codigocatastral']),
                            fn(Builder $query) => $query->whereIn('lic_id', function ($subquery) use ($data) {
                                $subquery->select('lic_id')
                                    ->from('licencia.vu_licencia')
                                    ->where('codigocatastral', 'LIKE', '%' . $data['codigocatastral'] . '%');
                            })
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!empty($data['codigocatastral'])) {
                            return 'Código: ' . $data['codigocatastral'];
                        }
                        return null;
                    }),
                SelectFilter::make('lic_codigopredial')
                    ->label('Codigo Predial')
                    ->options(fn() => CertificadoLicenciaFuncionamiento::query()
                        ->distinct()
                        ->whereNotNull('lic_codigopredial')
                        ->where('lic_codigopredial', '!=', '')
                        ->orderBy('lic_codigopredial', 'asc')
                        ->pluck('lic_codigopredial', 'lic_codigopredial')
                        ->toArray())
                    ->searchable()
                    ->indicator('Codigo Predial')
                    ->placeholder('Buscar codigo predial...')
                    ->native(false),

                Filter::make('per_ruc')
                    ->form([
                        TextInput::make('per_ruc')
                            ->label('RUC Personas')
                            ->placeholder('Ingrese RUC...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            !empty($data['per_ruc']),
                            fn(Builder $query) => $query->whereIn('lic_id', function ($subquery) use ($data) {
                                $subquery->select('lic_id')
                                    ->from('licencia.vu_licencia')
                                    ->where('per_ruc', 'LIKE', '%' . $data['per_ruc'] . '%');
                            })
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!empty($data['per_ruc'])) {
                            return 'RUC Persona: ' . $data['per_ruc'];
                        }
                        return null;
                    }),

                Filter::make('numero')
                    ->form([
                        TextInput::make('numero')
                            ->label('Número Dirección')
                            ->placeholder('Ingrese número de dirección...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            !empty($data['numero']),
                            fn(Builder $query) => $query->whereRaw(
                                "lic_direccion LIKE ?",
                                ['%' . $data['numero'] . '%']
                            )
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!empty($data['numero'])) {
                            return 'Número Dirección: ' . $data['numero'];
                        }
                        return null;
                    }),

                Filter::make('lic_direccion')
                    ->form([
                        TextInput::make('lic_direccion')
                            ->label('Dirección Licencia')
                            ->placeholder('Ingrese dirección licencia...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            !empty($data['lic_direccion']),
                            fn(Builder $query) => $query->where('lic_direccion', 'ILIKE', '%' . $data['lic_direccion'] . '%')
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!empty($data['lic_direccion'])) {
                            return 'Dirección Licencia: ' . $data['lic_direccion'];
                        }
                        return null;
                    }),

                Filter::make('per_direccionsol')
                    ->form([
                        TextInput::make('per_direccionsol')
                            ->label('Dirección Solicitante')
                            ->placeholder('Ingrese dirección solicitante...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            !empty($data['per_direccionsol']),
                            fn(Builder $query) => $query->whereIn('lic_id', function ($subquery) use ($data) {
                                $subquery->select('lic_id')
                                    ->from('licencia.vu_licencia')
                                    ->where('per_direccionsol', 'ILIKE', '%' . $data['per_direccionsol'] . '%');
                            })
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!empty($data['per_direccionsol'])) {
                            return 'Dirección Solicitante: ' . $data['per_direccionsol'];
                        }
                        return null;
                    }),

            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(4)
            ->filtersFormMaxHeight('400px')
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Modificar certificado')
                    ->color('warning'),
            ], position: RecordActionsPosition::BeforeCells)

            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filtros')
                    ->modalHeading('Filtros Avanzados de Licencias')
                    ->modalDescription('Utilice los filtros para refinar la lista de licencias según sus criterios.')
                    ->modalIcon('heroicon-o-funnel')
                    ->color('info')
                    ->modalSubmitActionLabel('Buscar Licencias')
                    ->modalCancelActionLabel('Cancelar')
            );
    }
}
