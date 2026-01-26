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
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                $user_role_id = $user->modelHasRole?->role_id;

                // Role 1 = Admin (global access)
                // Role 2 = SPEA (only Licencias - module_id=2)
                // Role 6 = ITSE (only ITSE - module_id=1)
                if ($user_role_id === 2) {
                    $query->where('module_id', 2); // Only Licencias
                } elseif ($user_role_id === 6) {
                    $query->where('module_id', 1); // Only ITSE
                }
                // Role 1 sees all, no filter needed
    
                return $query->where('estado', '!=', SolicitudPermisoEstado::FINALIZADO);
            })
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
                        if ($record->module_id === 1) {
                            return $record->certificado?->cin_numero ?? $state;
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
                    ->visible(function ($record) {
                        $user = auth()->user();
                        $user_role_id = $user->modelHasRole?->role_id;

                        // Role 1 = Admin (global access)
                        if ($user_role_id === 1) {
                            return true;
                        }
                        // Role 2 = SPEA (only Licencias - module_id=2)
                        if ($user_role_id === 2) {
                            return $record->module_id === 2;
                        }
                        // Role 6 = ITSE (only ITSE - module_id=1)
                        if ($user_role_id === 6) {
                            return $record->module_id === 1;
                        }

                        return false;
                    })
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([

            ]);
    }
}
