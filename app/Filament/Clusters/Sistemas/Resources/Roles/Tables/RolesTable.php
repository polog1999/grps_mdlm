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
                    ->icon('heroicon-o-key')
                    ->tooltip('Ver permisos del rol')
                    ->iconButton()
                    ->color('primary')
                    ->modalWidth('4xl')
                    ->modalHeading(fn($record) => "Permisos del rol: {$record->name}")
                    ->modalDescription(fn($record) => 'Lista completa de permisos asignados a este rol')
                    ->infolist(function ($record) {
                        $permissions = RoleHasPermission::where('role_id', $record->id)
                            ->with('permission')
                            ->get()
                            ->pluck('permission.name')
                            ->toArray();

                        if (empty($permissions)) {
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
                        $groupedPermissions = [];
                        foreach ($permissions as $permission) {
                            $parts = explode('.', $permission);
                            $module = $parts[0] ?? 'general';

                            if (!isset($groupedPermissions[$module])) {
                                $groupedPermissions[$module] = [];
                            }
                            $groupedPermissions[$module][] = $permission;
                        }

                        ksort($groupedPermissions);

                        // Construir secciones dinámicamente
                        $sections = [];

                        // Sección de resumen
                        $sections[] = Section::make('Resumen')
                            ->icon('heroicon-o-chart-bar')
                            ->description('Total de permisos asignados a este rol')
                            ->schema([
                                TextEntry::make('total')
                                    ->label('Total Permisos')
                                    ->state(count($permissions))
                                    ->badge()
                                    ->color('primary')
                                    ->icon('heroicon-o-shield-check')
                                    ->size('lg'),
                            ])
                            ->collapsible()
                            ->collapsed(false);

                        // Secciones por módulo
                        foreach ($groupedPermissions as $module => $perms) {
                            $moduleTitle = ucfirst(str_replace(['_', '-'], ' ', $module));

                            // Determinar color del módulo
                            $moduleColor = 'gray';
                            if (str_contains($module, 'user'))
                                $moduleColor = 'blue';
                            elseif (str_contains($module, 'role'))
                                $moduleColor = 'purple';
                            elseif (str_contains($module, 'setting'))
                                $moduleColor = 'orange';
                            elseif (str_contains($module, 'report'))
                                $moduleColor = 'green';

                            $permissionEntries = [];
                            foreach ($perms as $perm) {
                                // Determinar color según tipo de acción
                                $color = 'gray';
                                $icon = 'heroicon-o-key';

                                if (str_contains($perm, 'create') || str_contains($perm, 'add')) {
                                    $color = 'success';
                                    $icon = 'heroicon-o-plus-circle';
                                } elseif (str_contains($perm, 'edit') || str_contains($perm, 'update')) {
                                    $color = 'info';
                                    $icon = 'heroicon-o-pencil-square';
                                } elseif (str_contains($perm, 'delete') || str_contains($perm, 'remove')) {
                                    $color = 'danger';
                                    $icon = 'heroicon-o-trash';
                                } elseif (str_contains($perm, 'view') || str_contains($perm, 'read') || str_contains($perm, 'list')) {
                                    $color = 'warning';
                                    $icon = 'heroicon-o-eye';
                                }

                                $permissionEntries[] = TextEntry::make('perm_' . md5($perm))
                                    ->label('')
                                    ->state($perm)
                                    ->badge()
                                    ->color($color)
                                    ->icon($icon);
                            }

                            $sections[] = Section::make($moduleTitle)
                                ->icon('heroicon-o-folder-open')
                                ->description(count($perms) . ' permiso(s) en este módulo')
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
