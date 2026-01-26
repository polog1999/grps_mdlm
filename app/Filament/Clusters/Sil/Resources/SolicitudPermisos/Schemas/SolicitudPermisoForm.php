<?php

namespace App\Filament\Clusters\Sil\Resources\SolicitudPermisos\Schemas;

use App\Enums\SolicitudPermisoEstado;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class SolicitudPermisoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Solicitud')
                    ->schema([
                        TextInput::make('module_name')
                            ->label('Módulo')
                            ->formatStateUsing(fn($record) => $record->module->name ?? '-')
                            ->disabled()
                            ->dehydrated(false), // No se guarda

                        TextInput::make('user_name')
                            ->label('Solicitante')
                            ->formatStateUsing(fn($record) => $record->user->name ?? '-')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('display_record_id')
                            ->label('Registro / Licencia')
                            ->formatStateUsing(function ($record) {
                                if ($record->module_id === 2) {
                                    return 'Licencia N°: ' . ($record->licencia?->lic_numlic ?? 'S/N');
                                }
                                return 'Registro ID: ' . $record->record_id;
                            })
                            ->disabled()
                            ->dehydrated(false),

                        Textarea::make('observacion')
                            ->label('Motivo de Solicitud')
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Gestión')
                    ->schema([
                        Select::make('estado')
                            ->label('Estado de Aprobación')
                            ->options(SolicitudPermisoEstado::class)
                            ->required()
                            ->native(false),

                        TextInput::make('admin_id')
                            ->hidden(),

                        DateTimePicker::make('fecha_aprobacion')
                            ->hidden(),
                    ])
            ]);
    }
}
