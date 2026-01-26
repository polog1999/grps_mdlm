<?php

namespace App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\SolicitudPermisoEstado;

class SolicitudPermisosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', '!=', SolicitudPermisoEstado::FINALIZADO))
            ->columns([
                TextColumn::make('module.name')
                    ->label('Módulo')
                    ->sortable(),
                TextColumn::make('record_id')
                    ->label('Registro')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->module_id === 2) {
                            return $record->licencia?->lic_numlic ?? $state;
                        }
                        return $state;
                    })
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable(),
                TextColumn::make('tipo_accion')
                    ->label('Tipo de acción')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge()
                    ->searchable(),
                TextColumn::make('observacion')
                    ->label('Observación')
                    ->sortable(),
                TextColumn::make('admin.name')
                    ->label('Revisado por')
                    ->sortable(),
                TextColumn::make('fecha_aprobacion')
                    ->label('Fecha de aprobación')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha de creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Fecha de actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->tooltip('Editar solicitud')
                    ->color('warning')
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([

            ]);
    }
}
