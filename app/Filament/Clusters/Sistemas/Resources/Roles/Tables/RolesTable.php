<?php

namespace App\Filament\Clusters\Sistemas\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\Action;
use App\Models\RoleHasPermission;
use Illuminate\Support\HtmlString;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                /*    TextColumn::make('guard_name')
                    ->searchable(),

                */
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
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Modificar usuario')
                    ->color('warning'),

                //Action para ver los permisos del rol 
                Action::make('ver_permisos')
                    ->label('Ver permisos')
                    ->icon('heroicon-o-eye')
                    ->tooltip('Ver permisos del rol')
                    ->iconButton()
                    ->color('info')
                    ->modalWidth('4xl')
                    ->modalHeading(fn($record) => "Permisos del rol: {$record->name}")
                    ->modalDescription(fn($record) => 'Lista completa de permisos asignados a este rol')
                    ->infolist(function ($record) {
                        // Obtener permisos con su módulo relacionado
                        $permissions = RoleHasPermission::where('role_id', $record->id)
                            ->with(['permission.module'])
                            ->get()
                            ->pluck('permission')
                            ->filter(); // Eliminar nulls
            
                        if ($permissions->isEmpty()) {
                            return [
                                Section::make()
                                    ->schema([
                                        TextEntry::make('empty')
                                            ->label('')
                                            ->state('Este rol no tiene permisos asignados')
                                            ->badge()
                                            ->color('gray')
                                            ->icon('heroicon-o-exclamation-circle')
                                    ])
                                    ->columnSpanFull()
                            ];
                        }

                        // Agrupar permisos por módulo
                        $groupedByModule = [];
                        foreach ($permissions as $permission) {
                            $moduleName = $permission->module->name ?? 'Sin módulo';

                            if (!isset($groupedByModule[$moduleName])) {
                                $groupedByModule[$moduleName] = [];
                            }
                            $groupedByModule[$moduleName][] = $permission->name;
                        }

                        ksort($groupedByModule);

                        // Construir secciones dinámicamente
                        $sections = [];

                        // Sección de resumen
                        $sections[] = Section::make('Resumen')
                            ->icon('heroicon-o-chart-bar')
                            ->description('Total de permisos asignados a este rol')
                            ->schema([
                                TextEntry::make('total')
                                    ->label('Total Permisos')
                                    ->state($permissions->count())
                                    ->badge()
                                    ->color('primary')
                                    ->icon('heroicon-o-shield-check')
                                    ->size('lg'),
                            ])
                            ->collapsible()
                            ->collapsed(false);

                        // Secciones por módulo
                        foreach ($groupedByModule as $moduleName => $perms) {
                            $permissionEntries = [];

                            foreach ($perms as $permName) {
                                // Determinar color según tipo de acción
                                $color = 'gray';
                                $icon = 'heroicon-o-key';

                                if (str_contains($permName, 'create') || str_contains($permName, 'add')) {
                                    $color = 'success';
                                    $icon = 'heroicon-o-plus-circle';
                                } elseif (str_contains($permName, 'edit') || str_contains($permName, 'update')) {
                                    $color = 'info';
                                    $icon = 'heroicon-o-pencil-square';
                                } elseif (str_contains($permName, 'delete') || str_contains($permName, 'remove')) {
                                    $color = 'danger';
                                    $icon = 'heroicon-o-trash';
                                } elseif (str_contains($permName, 'view') || str_contains($permName, 'read') || str_contains($permName, 'list')) {
                                    $color = 'warning';
                                    $icon = 'heroicon-o-eye';
                                }

                                $permissionEntries[] = TextEntry::make($permName)
                                    ->state($permName)
                                    ->badge()
                                    ->color($color)
                                    ->icon($icon)
                                    ->hiddenLabel();
                            }

                            $sections[] = Section::make($moduleName)
                                ->icon('heroicon-o-folder-open')
                                ->description(count($perms) . ' permiso(s)')
                                ->schema([
                                    Grid::make([
                                        'default' => 1,
                                        'sm' => 2,
                                        'lg' => 3,
                                    ])->schema($permissionEntries)
                                ])
                                ->collapsible()
                                ->collapsed(false)
                                ->compact();
                        }

                        return $sections;
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
                Action::make('editar_permisos')
                    ->label('Editar permisos')
                    ->icon('heroicon-o-key')
                    ->tooltip('Editar permisos')
                    ->iconButton()
                    ->color('primary')
                    ->modalWidth('5xl')
                    ->modalHeading(fn($record) => "Editar permisos del rol: {$record->name}")
                    ->modalDescription('Selecciona los permisos que deseas asignar a este rol. Puedes buscar escribiendo.')
                    ->form([
                        Select::make('permissions')
                            ->label('Permisos del rol')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(function () {
                                return \App\Models\Permission::with('module')
                                    ->get()
                                    ->mapWithKeys(function ($permission) {
                                        $moduleName = $permission->module->name ?? 'Sin módulo';
                                        return [$permission->id => "{$permission->name} ({$moduleName})"];
                                    });
                            })
                            ->default(function ($record) {
                                return RoleHasPermission::where('role_id', $record->id)
                                    ->pluck('permission_id')
                                    ->toArray();
                            })
                            ->helperText('Escribe para buscar permisos. Se muestra el módulo entre paréntesis.')
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data, $record) {
                        // Eliminar todas las asignaciones actuales
                        RoleHasPermission::where('role_id', $record->id)->delete();

                        // Crear nuevas asignaciones
                        if (!empty($data['permissions'])) {
                            foreach ($data['permissions'] as $permissionId) {
                                RoleHasPermission::create([
                                    'role_id' => $record->id,
                                    'permission_id' => $permissionId,
                                ]);
                            }
                        }

                        // Ejecutar el seeder automáticamente
                        try {
                            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                                '--class' => 'RolesAndPermissionsSeeder',
                                '--force' => true,
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Permisos actualizados correctamente')
                                ->body('Los permisos se sincronizaron automáticamente.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Error al ejecutar seeder de permisos', [
                                'error' => $e->getMessage()
                            ]);

                            \Filament\Notifications\Notification::make()
                                ->title('Permisos actualizados')
                                ->body('Los permisos se guardaron pero hubo un error al sincronizar.')
                                ->warning()
                                ->send();
                        }
                    }),

                Action::make('borrar')
                    ->label('Borrar')
                    ->icon('heroicon-o-trash')
                    ->tooltip('Borrar certificado')
                    ->iconButton()
                    ->color('danger'),
            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([

            ]);
    }
}
