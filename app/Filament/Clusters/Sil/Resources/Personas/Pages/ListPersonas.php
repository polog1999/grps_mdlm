<?php

namespace App\Filament\Clusters\Sil\Resources\Personas\Pages;

use App\Filament\Clusters\Sil\Resources\Personas\PersonaResource;
use App\Services\Sil\Personas\PersonaService;
use App\Services\Sil\ExpedienteGestrad\ExpedienteGestradService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPersonas extends ListRecords
{
    protected static string $resource = PersonaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Crear Persona')
                ->icon('heroicon-o-plus')
                ->modalWidth('xl')
                ->form([
                    Grid::make(2)->schema([
                        Select::make('per_nombrerazonsocial')
                            ->label('Buscar por Nombre o Razón Social')
                            ->placeholder('Escriba para buscar...')
                            ->columnSpanFull()
                            ->searchable()
                            ->required()
                            ->getSearchResultsUsing(function (string $search) {
                                if (strlen($search) < 3)
                                    return [];

                                $results = app(ExpedienteGestradService::class)->getPersona($search);

                                return $results->mapWithKeys(function ($item) {
                                    // Fix para Oracle: Convertir stdClass a Array con llaves en Mayúsculas
                                    $data = array_change_key_case((array) $item, CASE_UPPER);

                                    $nombre = $data['EXP_NOMREC'] ?? 'Sin Nombre';
                                    $ruc = $data['EXP_RUC'] ?? $data['EXP_CODCON'] ?? 'S/D';

                                    return [$nombre => "{$nombre} | RUC: {$ruc}"];
                                })->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                if (!$value) {
                                    return null;
                                }

                                // El valor es el nombre, así que simplemente lo retornamos
                                return $value;
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if (!$state)
                                    return;

                                // Buscamos el registro para extraer los otros datos
                                $results = app(ExpedienteGestradService::class)->getPersona($state);
                                $first = $results->first();

                                if ($first) {
                                    // Fix para Oracle: Normalizar llaves
                                    $data = array_change_key_case((array) $first, CASE_UPPER);

                                    $set('nombre_completo', trim($data['EXP_NOMREC'] ?? ''));
                                    $set('cod_contribuyente', trim($data['EXP_CODCON'] ?? ''));
                                    $set('per_ruc', trim($data['EXP_RUC'] ?? $data['EXP_CODCON'] ?? ''));
                                    $set('per_direccion', trim($data['EXP_DIR'] ?? ''));
                                    $set('per_telefono', trim($data['EXP_TELEFONO'] ?? ''));
                                    $set('per_email', trim($data['EXP_EMAIL'] ?? ''));
                                }
                            }),

                        TextInput::make('nombre_completo')
                            ->label('Nombre o Razón Social')
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Se llenará automáticamente al seleccionar'),

                        TextInput::make('cod_contribuyente')
                            ->label('Código de Contribuyente')
                            ->disabled()
                            ->dehydrated()
                            ->placeholder('Se llenará automáticamente')
                            ->helperText('Campo de solo lectura'),

                        TextInput::make('per_ruc')
                            ->label('RUC / DNI')
                            ->required()
                            ->numeric()
                            ->maxLength(15),

                        TextInput::make('per_telefono')
                            ->label('Teléfono')
                            ->required()
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('per_direccion')
                            ->label('Dirección')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('per_email')
                            ->label('Correo Electrónico')
                            ->required()
                            ->email()
                            ->maxLength(100)
                            ->columnSpanFull(),
                    ]),
                ])
                ->action(function (array $data) {
                    try {
                        $service = app(PersonaService::class);

                        // Mapear campos del formulario a los esperados por el SP
                        $dataToSend = [
                            'per_nombrerazonsocial' => $data['nombre_completo'] ?? '',
                            'per_ruc' => $data['per_ruc'] ?? '',
                            'per_direccion' => $data['per_direccion'] ?? '',
                            'per_telefono' => $data['per_telefono'] ?? '',
                            'per_email' => $data['per_email'] ?? '',
                            'per_expcodcon' => $data['cod_contribuyente'] ?? '',
                        ];

                        $result = $service->create_unico($dataToSend);

                        // Manejar respuesta según el tipo
                        if ($result['success']) {
                            // Éxito
                            Notification::make()
                                ->title('¡Persona Creada!')
                                ->body($result['message'] . ' (ID: ' . $result['per_id'] . ')')
                                ->success()
                                ->duration(5000)
                                ->send();
                        } else {
                            // Errores específicos según el tipo
                            switch ($result['type']) {
                                case 'validation':
                                    // Error de validación (nombre vacío)
                                    Notification::make()
                                        ->title('Validación Fallida')
                                        ->body($result['message'])
                                        ->warning()
                                        ->duration(6000)
                                        ->send();
                                    break;

                                case 'duplicate':
                                    // Duplicado exacto
                                    Notification::make()
                                        ->title('Registro Duplicado')
                                        ->body($result['message'])
                                        ->warning()
                                        ->duration(7000)
                                        ->send();
                                    break;

                                case 'internal':
                                    // Error interno del SP
                                    Notification::make()
                                        ->title('Error del Sistema')
                                        ->body($result['message'])
                                        ->danger()
                                        ->duration(10000)
                                        ->send();
                                    break;

                                case 'exception':
                                    // Error de conexión
                                    Notification::make()
                                        ->title('Error de Conexión')
                                        ->body($result['message'])
                                        ->danger()
                                        ->duration(10000)
                                        ->send();
                                    break;

                                default:
                                    // Error desconocido
                                    Notification::make()
                                        ->title('Error Desconocido')
                                        ->body($result['message'] ?? 'Ha ocurrido un error inesperado')
                                        ->danger()
                                        ->duration(8000)
                                        ->send();
                            }
                        }
                    } catch (\Exception $e) {
                        // Catch de excepciones no manejadas
                        Notification::make()
                            ->title('Error Crítico')
                            ->body('Error inesperado: ' . $e->getMessage())
                            ->danger()
                            ->duration(10000)
                            ->send();
                    }
                }),
        ];
    }
}