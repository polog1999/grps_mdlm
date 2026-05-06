<?php

namespace App\Filament\Clusters\Sistemas\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use App\Models\Role;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\DB;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Rol')
                    ->badge()
                    ->separator(',')
                    ->searchable(),
                TextColumn::make('sede')
                    ->label('Sede')
                    ->badge()
                    ->separator(',')
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Activo')
                    ->onColor('success')
                    ->offColor('danger')
                    ->disabled(fn($record) => $record?->id === auth()->user()->id)
                    ->afterStateUpdated(function ($record, $state) {
                        // Opcional: Si lo desactivas, puedes borrar su sesión de golpe
                        if (!$state) {
                            DB::table('sessions')
                                ->where('user_id', $record->id)
                                ->delete();
                        }
                    }),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('role')
                    ->label('Rol')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Modificar usuario')
                    ->color('warning'),
                /*  
                Action::make('borrar')
                    ->label('Borrar')
                    ->icon('heroicon-o-trash')
                    ->tooltip('Borrar certificado')
                    ->iconButton()
                    ->color('danger'),
                */
                Action::make('asignar_rol')
                    ->label('Asignar rol')
                    ->icon('heroicon-o-user-group')
                    ->tooltip('Asignar rol')
                    ->iconButton()
                    ->color('info')
                    ->modalHeading(fn($record) => "Asignar rol a {$record->name}")
                    ->modalDescription('Seleccione el rol que desea asignar a este usuario o déjelo vacío para quitar todos los roles')
                    ->modalSubmitActionLabel('Asignar')
                    ->form([
                        Select::make('role_id')
                            ->label('Rol')
                            ->options(Role::all()->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Sin rol')
                            ->default(fn($record) => $record->roles->first()?->id)
                    ])
                    ->action(function (array $data, $record) {
                        if (empty($data['role_id'])) {
                            // Si no se seleccionó ningún rol, quitar todos los roles
                            $record->syncRoles([]);

                            Notification::make()
                                ->title('Roles removidos correctamente')
                                ->success()
                                ->send();
                        } else {
                            // Sincronizar el rol seleccionado
                            $record->syncRoles([$data['role_id']]);

                            Notification::make()
                                ->title('Rol asignado correctamente')
                                ->success()
                                ->send();
                        }
                    })

            ], position: RecordActionsPosition::BeforeCells)
            ->toolbarActions([
                /*
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),*/]);
    }
}
