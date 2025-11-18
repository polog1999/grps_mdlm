<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Tables;

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
                $hasFilters = collect($livewire->tableFilters)
                    ->filter(fn($filter) => !empty($filter['values']))
                    ->isNotEmpty();
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
                //filtrar por Numero de licencia
                

            ], layout: FiltersLayout::Modal)
                ->modifyQueryUsing(fn ($query) => $query->where('lic_filaeliminada', false))

            ->recordActions([
                EditAction::make(),
            ])
            
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
