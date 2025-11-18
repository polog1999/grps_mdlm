<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Tables;

use App\Models\CertificadoLicenciaFuncionamiento;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamiento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Collection;
use Filament\Actions\Action;

class CertificadoLicenciaFuncionamientosTable
{

    protected static $service;

    public static function configure(Table $table): Table
    {

        if (!isset(self::$service)) {
            self::$service = new CertificadoLincenciaFuncionamiento();
        }

        return $table

            ->modifyQueryUsing(function (Builder $query, $livewire) {
                $hasFilters = false;
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

                return ($livewire->hasTableSearch() || $hasFilters) ? $query : $query->whereRaw('1 = 0');
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
                        if (! $lic_id) {
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
                    ->options(fn () => CertificadoLicenciaFuncionamiento::query()
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
                ->options(fn () => CertificadoLicenciaFuncionamiento::query()
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
                ->options(fn () => CertificadoLicenciaFuncionamiento::query()
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

            ], layout: FiltersLayout::Modal)
                ->modifyQueryUsing(fn ($query) => $query->where('lic_filaeliminada', false))
                ->filtersFormColumns(3)
                ->filtersFormMaxHeight('400px')
            ->recordActions([
                EditAction::make(),
            ])
            
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filtros')
                    ->modalHeading('Filtros Avanzados de Certificados')
                    ->modalDescription('Utilice los filtros para refinar la lista de certificados según sus criterios.')
                    ->modalIcon('heroicon-o-funnel')
                    ->color('info')
                    ->modalSubmitActionLabel('Buscar Certificados')
                    ->modalCancelActionLabel('Cancelar')
    );
    }
}
