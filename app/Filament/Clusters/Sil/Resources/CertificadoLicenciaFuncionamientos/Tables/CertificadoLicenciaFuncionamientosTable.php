<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Tables;

use Illuminate\Support\Facades\Storage;
use App\Models\CertificadoLicenciaFuncionamiento;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamientoService;
use App\Services\Sil\Licencias\CertificadoLicenciaPdfService;
use App\Services\Sil\Licencias\CompatibilidadCertificadoPdfService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Collection;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Enums\RecordActionsPosition;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\CertificadoLicenciaFuncionamientoResource;

class CertificadoLicenciaFuncionamientosTable
{

    protected static $service;

    private static function getDatoDirecto($record, $key)
    {
        if (!$record || !$record->lic_id)
            return null;

        // Cache simple en propiedad dinámica del modelo para el ciclo de vida de la request
        if (!isset($record->datos_directos_cache)) {
            try {
                $service = app(\App\Services\Sil\Licencias\LicenciaService::class);
                $record->datos_directos_cache = $service->obtenerDatosPorIdLicenciaDirecta($record->lic_id);
            } catch (\Throwable $e) {
                Log::error('Error obteniendo datos directos para licencia ' . $record->lic_id, ['error' => $e->getMessage()]);
                $record->datos_directos_cache = null;
            }
        }

        if (!$record->datos_directos_cache)
            return null;

        // El resultado es un objeto, acceder a la propiedad
        // Nota: Los keys del SP suelen ser mayúsculas, asegurar coincidencia
        return $record->datos_directos_cache->{$key} ?? null;
    }

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
                                    ->reject(fn($v) => $v === null || $v === '')
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
            ->recordUrl(null)
            ->columns([
                //TextColumn::make('lic_id')->label('ID')->sortable()->searchable(),

                /*
                TextColumn::make('module_id_debug')
                    ->label('Module ID')
                    ->color('gray')
                    ->getStateUsing(fn() => \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id')),
*/
                TextColumn::make('lic_numlic')
                    ->label('Licencia')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'NUMERO_LICENCIA')),

                TextColumn::make('lic_expnum')
                    ->label('Expediente')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'EXPEDIENTE_NRO')),

                TextColumn::make('codcat')
                    ->label('Código Catastral')
                    ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'CODIGO_CATASTRAL'))
                    ->sortable(),

                TextColumn::make('lic_razonsocial')
                    ->label('Razón Social')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'RAZON_SOCIAL')),

                TextColumn::make('tipoLicencia.tli_descripcion')
                    ->label('Tipo Licencia')
                    ->sortable()
                    ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'TIPO_LICENCIA')),

                TextColumn::make('tipoEstadoLicencia.esl_descripcion')
                    ->label('Estado')
                    ->sortable()
                    ->getStateUsing(fn($record) => self::getDatoDirecto($record, 'esl_descripcion')),
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

                \Filament\Tables\Filters\TernaryFilter::make('tiene_itse')
                    ->label('Con ITSE')
                    ->placeholder('Todos')
                    ->trueLabel('Solo con ITSE')
                    ->falseLabel('Sin ITSE')
                    ->queries(
                        true: fn(Builder $query) => $query->whereIn('lic_id', function ($subquery) {
                            $subquery->select('lic_id')
                                ->from('licencia.vu_licencia')
                                ->whereNotNull('cin_numero');
                        }),
                        false: fn(Builder $query) => $query->whereNotIn('lic_id', function ($subquery) {
                            $subquery->select('lic_id')
                                ->from('licencia.vu_licencia')
                                ->whereNotNull('cin_numero');
                        }),
                        blank: fn(Builder $query) => $query,
                    )
                    ->indicator('ITSE'),

                \Filament\Tables\Filters\TernaryFilter::make('tiene_anuncios')
                    ->label('Con Anuncios')
                    ->placeholder('Todos')
                    ->trueLabel('Solo con Anuncios')
                    ->falseLabel('Sin Anuncios')
                    ->queries(
                        true: fn(Builder $query) => $query->whereIn('lic_id', function ($subquery) {
                            $subquery->select(DB::raw('id_licencia::integer'))
                                ->from('anuncios.anuncios')
                                ->whereNotNull('id_licencia')
                                ->whereNull('deleted_at');
                        }),
                        false: fn(Builder $query) => $query->whereNotIn('lic_id', function ($subquery) {
                            $subquery->select(DB::raw('id_licencia::integer'))
                                ->from('anuncios.anuncios')
                                ->whereNotNull('id_licencia')
                                ->whereNull('deleted_at');
                        }),
                        blank: fn(Builder $query) => $query,
                    )
                    ->indicator('Anuncios'),

                Filter::make('lic_filafecha')
                    ->form([
                        DatePicker::make('desde')
                            ->label('Registro desde')
                            ->placeholder('Seleccione fecha inicial')
                            ->native(false),
                        DatePicker::make('Registro hasta')
                            ->label('Hasta')
                            ->placeholder('Seleccione fecha final')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                !empty($data['desde']),
                                fn(Builder $query) => $query->whereDate('lic_filafecha', '>=', $data['desde'])
                            )
                            ->when(
                                !empty($data['hasta']),
                                fn(Builder $query) => $query->whereDate('lic_filafecha', '<=', $data['hasta'])
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (!empty($data['desde'])) {
                            $indicators[] = 'Desde: ' . \Carbon\Carbon::parse($data['desde'])->format('d/m/Y');
                        }

                        if (!empty($data['hasta'])) {
                            $indicators[] = 'Hasta: ' . \Carbon\Carbon::parse($data['hasta'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),

                // 2. EL NUEVO FILTRO (Fecha de Emisión) - ¡AÑADE ESTE BLOQUE!
                Filter::make('lic_fechaemision')
                    ->form([
                        DatePicker::make('from')->label('Emisión Desde')->placeholder('Fecha inicial')->native(false),
                        DatePicker::make('to')->label('Emisión Hasta')->placeholder('Fecha final')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            // Usamos 'where' directo en lugar de 'whereDate' para que sea instantáneo
                            ->when(!empty($data['from']), fn($q) => $q->where('lic_fechaemision', '>=', $data['from']))
                            ->when(!empty($data['to']), fn($q) => $q->where('lic_fechaemision', '<=', $data['to']));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if (!empty($data['from'])) $indicators[] = 'Emisión Desde: ' . \Carbon\Carbon::parse($data['from'])->format('d/m/Y');
                        if (!empty($data['to'])) $indicators[] = 'Emisión Hasta: ' . \Carbon\Carbon::parse($data['to'])->format('d/m/Y');
                        return $indicators;
                    }),

            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(4)
            ->filtersFormMaxHeight('400px')
            ->recordActions([
                /*
                Action::make('notificar_licencia')
                    ->label('Notificar')
                    ->icon('heroicon-o-bell-alert')
                    ->iconButton()
                    ->tooltip('Notificar licencia de funcionamiento')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Notificar Licencia de Funcionamiento')
                    ->modalDescription('Complete la información de notificación para esta licencia.')
                    ->modalWidth('4xl')
                    ->modalIcon('heroicon-o-bell-alert')
                    ->modalIconColor('warning')
                    ->fillForm(function (CertificadoLicenciaFuncionamiento $record): array {
                        $service = new \App\Services\Sil\Licencias\LicenciaNotificacionService();
                        $datosActuales = $service->datosLicenciaNotificada($record->lic_id);

                        return [
                            'lic_id' => $record->lic_id,
                            'numero_licencia' => $record->lic_numlic,
                            'numero_expediente' => $record->lic_expnum,
                            'fecha_notificacion' => now()->format('Y-m-d'),
                            'fecha_limite' => now()->addDays(30)->format('Y-m-d'),
                            'fecha_notificacion_actual' => $datosActuales->lic_fechanotificacion,
                            'fecha_limite_actual' => $datosActuales->lic_fechalimite,
                            'tiene_notificacion' => !is_null($datosActuales->lic_fechanotificacion),
                        ];
                    })
                    ->form([
                        // Información de la Licencia
                        TextInput::make('numero_licencia')
                            ->label('Licencia N°')
                            ->disabled()
                            ->dehydrated(false)
                            ->extraInputAttributes(['class' => 'text-lg font-semibold'])
                            ->columnSpan(1),

                        TextInput::make('numero_expediente')
                            ->label('Expediente N°')
                            ->disabled()
                            ->dehydrated(false)
                            ->extraInputAttributes(['class' => 'text-lg font-semibold'])
                            ->columnSpan(1),

                        // Alerta informativa si ya está notificada
                        TextInput::make('alerta_notificacion')
                            ->label('')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('⚠️ Esta licencia ya tiene una notificación registrada. Los nuevos datos reemplazarán los existentes.')
                            ->extraInputAttributes(['class' => 'text-amber-600 dark:text-amber-400'])
                            ->hidden(fn($get) => !$get('tiene_notificacion'))
                            ->columnSpanFull(),

                        Section::make('Nueva Notificación')
                            ->description('Complete las fechas de notificación')
                            ->icon('heroicon-o-calendar-days')
                            ->iconColor('success')
                            ->schema([
                                DatePicker::make('fecha_notificacion')
                                    ->label('Fecha de Notificación')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->maxDate(now())
                                    ->default(now())
                                    ->prefixIcon('heroicon-o-calendar')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if ($state) {
                                            $fechaActual = $get('fecha_limite');
                                            if (!$fechaActual || $fechaActual === now()->addDays(30)->format('Y-m-d')) {
                                                $set('fecha_limite', \Carbon\Carbon::parse($state)->addDays(30)->format('Y-m-d'));
                                            }
                                        }
                                    })
                                    ->helperText('Fecha en que se notificó al titular')
                                    ->columnSpanFull(),

                                DatePicker::make('fecha_limite')
                                    ->label('Fecha Límite de Subsanación')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->minDate(fn($get) => $get('fecha_notificacion'))
                                    ->prefixIcon('heroicon-o-clock')
                                    ->helperText('Fecha límite para cumplir con los requisitos')
                                    ->live()
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(1),

                        Section::make('Notificación Actual')
                            ->description('Datos registrados actualmente')
                            ->icon('heroicon-o-information-circle')
                            ->iconColor('gray')
                            ->collapsed(true)
                            ->schema([
                                TextInput::make('fecha_notificacion_actual_display')
                                    ->label('Fecha de Notificación')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder(function ($get) {
                                        $fecha = $get('fecha_notificacion_actual');
                                        return $fecha ? \Carbon\Carbon::parse($fecha)->format('d/m/Y') : 'No notificada';
                                    })
                                    ->prefixIcon(fn($get) => $get('fecha_notificacion_actual') ? 'heroicon-o-calendar' : 'heroicon-o-x-circle')
                                    ->prefixIconColor(fn($get) => $get('fecha_notificacion_actual') ? 'success' : 'gray')
                                    ->columnSpanFull(),

                                TextInput::make('fecha_limite_actual_display')
                                    ->label('Fecha Límite')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder(function ($get) {
                                        $fecha = $get('fecha_limite_actual');
                                        if ($fecha) {
                                            $carbon = \Carbon\Carbon::parse($fecha);
                                            $formato = $carbon->format('d/m/Y');
                                            return $carbon->isPast() ? "{$formato} (Vencida)" : $formato;
                                        }
                                        return 'Sin fecha límite';
                                    })
                                    ->prefixIcon(function ($get) {
                                        $fecha = $get('fecha_limite_actual');
                                        if (!$fecha)
                                            return 'heroicon-o-x-circle';
                                        return \Carbon\Carbon::parse($fecha)->isPast() ? 'heroicon-o-exclamation-circle' : 'heroicon-o-clock';
                                    })
                                    ->prefixIconColor(function ($get) {
                                        $fecha = $get('fecha_limite_actual');
                                        if (!$fecha)
                                            return 'gray';
                                        return \Carbon\Carbon::parse($fecha)->isPast() ? 'danger' : 'warning';
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(1),
                    ])
                    ->modalSubmitActionLabel('Confirmar Notificación')
                    ->modalCancelActionLabel('Cancelar')
                    ->closeModalByClickingAway(false)
                    ->action(function (Action $action, array $data, CertificadoLicenciaFuncionamiento $record) {
                        try {
                            $service = new \App\Services\Sil\Licencias\LicenciaNotificacionService();

                            $serviceData = [
                                'lic_id' => $record->lic_id,
                                'fecha_notificacion' => $data['fecha_notificacion'],
                                'fecha_limite' => $data['fecha_limite'],
                            ];

                            $resultado = $service->notificarLicencia($serviceData);

                            if ($resultado) {
                                \Filament\Notifications\Notification::make()
                                    ->title('¡Notificación registrada!')
                                    ->body("La licencia {$record->lic_numlic} ha sido notificada correctamente.")
                                    ->success()
                                    ->icon('heroicon-o-check-circle')
                                    ->duration(5000)
                                    ->send();
                            } else {
                                throw new \Exception('No se pudo completar el registro de la notificación.');
                            }
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Error al notificar')
                                ->body($e->getMessage())
                                ->danger()
                                ->icon('heroicon-o-x-circle')
                                ->persistent()
                                ->send();

                            \Log::error('Error al notificar licencia', [
                                'licencia_id' => $record->lic_id,
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString()
                            ]);

                            $action->halt();
                        }
                    }),*/
                Action::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Modificar')
                    ->color('warning')
                    ->visible(function (CertificadoLicenciaFuncionamiento $record) {
                        $user = auth()->user();

                        // Primero verificar el permiso del sistema
                        if (!$user->hasPermissionTo('edit::certificado_licencia_funcionamiento')) {
                            return false;
                        }

                        // Roles 1 y 2: pueden editar directamente
                        $user_role_id = $user->modelHasRole?->role_id;
                        if ($user_role_id === 1 || $user_role_id === 2) {
                            return true;
                        }

                        // Otros roles: solo si tienen SolicitudPermiso APROBADA
                        return \App\Models\SolicitudPermiso::query()
                            ->where('record_id', $record->lic_id)
                            ->where('user_id', $user->id)
                            ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                            ->exists();
                    })
                    ->url(function (CertificadoLicenciaFuncionamiento $record) {
                        return CertificadoLicenciaFuncionamientoResource::getUrl('edit', ['record' => $record]);
                    }),

                // Acción para solicitar permiso de edición (para usuarios con permiso pero sin acceso directo)
                Action::make('solicitar_editar')
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Solicitar permiso para editar')
                    ->color('warning')
                    ->visible(function (CertificadoLicenciaFuncionamiento $record) {
                        $user = auth()->user();

                        // Primero verificar el permiso del sistema
                        if (!$user->hasPermissionTo('edit::certificado_licencia_funcionamiento')) {
                            return false;
                        }

                        // Roles 1 y 2: NO muestran esta acción (ya ven el botón de edición directa)
                        $user_role_id = $user->modelHasRole?->role_id;
                        if ($user_role_id === 1 || $user_role_id === 2) {
                            return false;
                        }

                        // Otros roles: mostrar solo si NO tienen SolicitudPermiso APROBADA
                        $tieneSolicitudAprobada = \App\Models\SolicitudPermiso::query()
                            ->where('record_id', $record->lic_id)
                            ->where('user_id', $user->id)
                            ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                            ->exists();

                        return !$tieneSolicitudAprobada;
                    })
                    ->modalHeading('Solicitar Permiso de Edición')
                    ->modalDescription('No tienes permisos directos para editar este registro. Por favor, indica el motivo para solicitar la edición.')
                    ->modalSubmitActionLabel('Enviar Solicitud')
                    ->modalCancelActionLabel('Cancelar')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('observacion')
                            ->label('Motivo de la solicitud')
                            ->required()
                            ->rows(3)
                            ->placeholder('Ingrese el motivo por el cual desea editar el registro...'),
                    ])
                    ->action(function (array $data, CertificadoLicenciaFuncionamiento $record) {
                        try {
                            $user = auth()->user();

                            // Validar si ya existe solicitud pendiente
                            $existe = \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', 'PENDIENTE')
                                ->exists();

                            if ($existe) {
                                Notification::make()
                                    ->title('Solicitud pendiente')
                                    ->body('Ya existe un ticket pendiente para este registro. Espera a que sea atendido')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            \App\Models\SolicitudPermiso::create([
                                'module_id' => \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id'),
                                'record_id' => $record->lic_id,
                                'user_id' => $user->id,
                                'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::EDITAR_DATOS_LICENCIA,
                                'estado' => \App\Enums\SolicitudPermisoEstado::PENDIENTE,
                                'observacion' => $data['observacion'],
                            ]);

                            Notification::make()
                                ->title('Solicitud Enviada')
                                ->body('Su solicitud ha sido registrada y está pendiente de aprobación.')
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body('Ocurrió un error al enviar la solicitud: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('generar-qr')
                    ->icon('heroicon-o-qr-code')
                    ->iconButton()
                    ->tooltip('Ver QR')
                    ->color('success')
                    ->visible(fn() => auth()->user()->hasPermissionTo('view_qr::certificado_licencia_funcionamiento'))
                    ->modalHeading('Código QR de Licencia')
                    ->modalDescription('Escanee este código QR para ver los detalles de la licencia')
                    ->modalWidth('md')
                    ->modalIcon('heroicon-o-qr-code')
                    ->modalIconColor('success')
                    ->fillForm(function (CertificadoLicenciaFuncionamiento $record) {
                        $qrService = new \App\Services\Sil\Licencias\QrCodeService();
                        $qrDataUri = $qrService->generarQrDataUri($record->lic_id);

                        return [
                            'qr_image' => $qrDataUri,
                            'numero_licencia' => $record->lic_numlic,
                            'numero_expediente' => $record->lic_expnum,
                            'url_licencia' => route('qr.mostrar', ['idLicencia' => $record->lic_id]),
                        ];
                    })
                    ->form([
                        \Filament\Forms\Components\Placeholder::make('qr_display')
                            ->label('')
                            ->content(function ($get) {
                                $qrImage = $get('qr_image');
                                return new HtmlString(
                                    '<div style="text-align: center; padding: 20px;">
                                        <img src="' . $qrImage . '" alt="Código QR" style="max-width: 300px; width: 100%; height: auto; border: 2px solid #10b981; border-radius: 8px; padding: 10px; background: white;">
                                    </div>'
                                );
                            })
                            ->columnSpanFull(),

                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),

                Action::make('Ver Itse')
                    ->icon('tabler-clipboard-check')
                    ->iconButton()
                    ->tooltip('Ver Certificado(s) de ITSE')
                    ->visible(fn() => auth()->user()->hasPermissionTo('view_itse::certificado_licencia_funcionamiento'))
                    ->color(function ($record) {
                        $service = app(CertificadoLincenciaFuncionamientoService::class);
                        return $service->tieneCertificadoCompleto($record->lic_id) ? Color::Yellow : Color::Stone;
                    })
                    ->disabled(function ($record) {
                        $service = app(CertificadoLincenciaFuncionamientoService::class);
                        return !$service->tieneCertificadoCompleto($record->lic_id);
                    })
                    ->modalHeading('Certificados de Inspección Técnica (ITSE)')
                    ->modalDescription(fn($record) => "Certificados ITSE relacionados con la Licencia N° {$record->lic_numlic}")
                    ->modalWidth('5xl')
                    ->modalIcon('tabler-clipboard-check')
                    ->modalIconColor(Color::Stone)
                    ->infolist(function ($record) {
                        $service = app(CertificadoLincenciaFuncionamientoService::class);
                        $certificados = $service->obtenerCertificadosInspeccionPorLicencia($record->lic_id);

                        if ($certificados->isEmpty()) {
                            return [
                                Section::make()
                                    ->schema([
                                        TextEntry::make('sin_datos')
                                            ->label('')
                                            ->default('No se encontraron certificados ITSE para esta licencia.')
                                            ->color('warning')
                                            ->icon('heroicon-o-exclamation-triangle')
                                    ])
                            ];
                        }

                        return [
                            Section::make('Certificados Encontrados')
                                ->description("Total: {$certificados->count()} certificado(s)")
                                ->icon('heroicon-o-document-check')
                                ->schema([
                                    RepeatableEntry::make('certificados')
                                        ->label('')
                                        ->state($certificados->toArray())
                                        ->schema([
                                            TextEntry::make('cin_annio')
                                                ->label('Año')
                                                ->badge()
                                                ->color('primary'),
                                            TextEntry::make('cin_numero')
                                                ->label('Número')
                                                ->weight('bold')
                                                ->copyable()
                                                ->copyMessage('Número copiado')
                                                ->icon('heroicon-o-hashtag'),
                                            TextEntry::make('tie_descripcion')
                                                ->label('Tipo de Edificación')
                                                ->badge()
                                                ->color('success'),
                                            TextEntry::make('cin_expediente')
                                                ->label('Expediente')
                                                ->icon('heroicon-o-folder-open')
                                                ->copyable()
                                                ->copyMessage('Expediente copiado'),
                                            TextEntry::make('cin_resolucion')
                                                ->label('Resolución')
                                                ->icon('heroicon-o-document-text')
                                                ->copyable()
                                                ->copyMessage('Resolución copiada'),
                                            TextEntry::make('cin_fecha')
                                                ->label('Fecha')
                                                ->date('d/m/Y')
                                                ->icon('heroicon-o-calendar'),
                                            TextEntry::make('cin_vigencia_c')
                                                ->label('Tiempo de la vigencia')
                                                ->icon('heroicon-o-calendar')
                                        ])
                                        ->columns(3)
                                        ->contained(true)
                                ])
                        ];
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),

                \Filament\Actions\ActionGroup::make([
                    Action::make('certificado-licencia')
                        ->label('Generar certificado')
                        ->icon('heroicon-o-document-text')
                        ->tooltip('Generar certificado')
                        ->color(Color::Stone)
                        ->visible(fn() => auth()->user()->hasPermissionTo('generate_certificate::certificado_licencia_funcionamiento'))
                        ->url(
                            fn(CertificadoLicenciaFuncionamiento $record): string =>
                            route('certificado-licencia.mostrar', ['licenciaId' => $record->lic_id])
                        )
                        ->openUrlInNewTab(),

                    Action::make('ver_actualizado')
                        ->label('Certificado actualizado')
                        ->icon('tabler-certificate')
                        ->color('primary')
                        ->tooltip('Gestionar certificado actualizado')
                        ->visible(fn() => auth()->user()->hasPermissionTo('upload_pdf::certificado_licencia_funcionamiento'))
                        ->modalHeading(function ($record) {
                            $service = app(CertificadoLicenciaPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'Solicitar Permiso de Actualización';
                            }

                            return 'Gestión de Certificado de Licencia Actualizado';
                        })
                        ->modalDescription(function ($record) {
                            $service = app(CertificadoLicenciaPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'El certificado actualizado ya existe. Para volver a subirlo, necesitas solicitar un permiso. Por favor, indica el motivo.';
                            }

                            return 'Modal para subir y descargar certificado de licencia actualizado';
                        })
                        ->modalWidth(function ($record) {
                            $service = app(CertificadoLicenciaPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'md';
                            }

                            return '5xl';
                        })
                        ->modalSubmitActionLabel(function ($record) {
                            $service = app(CertificadoLicenciaPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'Enviar Solicitud';
                            }

                            return 'Subir Certificado';
                        })
                        ->modalCancelActionLabel('Cerrar')
                        ->form(function ($record) {
                            $service = app(CertificadoLicenciaPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return [
                                    \Filament\Forms\Components\Textarea::make('observacion')
                                        ->label('Motivo de la solicitud')
                                        ->required()
                                        ->rows(3)
                                        ->placeholder('Ingrese el motivo por el cual desea volver a subir el certificado...')
                                ];
                            }

                            return [
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Subir/Actualizar Certificado')
                                            ->description('Suba o actualice el certificado en formato PDF')
                                            ->icon('heroicon-o-arrow-up-tray')
                                            ->columnSpan(1)
                                            ->schema([
                                                FileUpload::make('certificado_actualizado')
                                                    ->label('Archivo PDF')
                                                    ->acceptedFileTypes(['application/pdf'])
                                                    ->maxSize(10240) // 10MB
                                                    ->disk('local')
                                                    ->directory('temp')
                                                    ->visibility('private')
                                                    ->downloadable()
                                                    ->openable()
                                                    ->previewable()
                                                    ->helperText('Seleccione un archivo PDF (máx. 10MB) y haga clic en "Subir Certificado"')
                                                    ->storeFiles(false)
                                                    ->required(),

                                                Hidden::make('lic_id')
                                                    ->default(fn($record) => $record->lic_id),
                                                Hidden::make('lic_numlic')
                                                    ->default(fn($record) => $record->lic_numlic),
                                            ]),

                                        Section::make('Descargar Certificado')
                                            ->description('Descargue el certificado actualizado')
                                            ->icon('heroicon-o-arrow-down-tray')
                                            ->columnSpan(1)
                                            ->schema([
                                                TextInput::make('download_status')
                                                    ->label('Estado del Certificado')
                                                    ->default(function () use ($record) {
                                                        $service = app(CertificadoLicenciaPdfService::class);
                                                        $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                        return $exists ? '✓ Certificado Disponible' : '⚠ No Disponible';
                                                    })
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->suffixIcon(function () use ($record) {
                                                        $service = app(CertificadoLicenciaPdfService::class);
                                                        $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                        return $exists ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle';
                                                    })
                                                    ->suffixIconColor(function () use ($record) {
                                                        $service = app(CertificadoLicenciaPdfService::class);
                                                        $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                        return $exists ? 'success' : 'warning';
                                                    }),

                                                TextInput::make('download_link')
                                                    ->label('Descargar')
                                                    ->default(function () use ($record) {
                                                        $service = app(CertificadoLicenciaPdfService::class);
                                                        $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                        return $exists ? 'Listo para descargar' : 'No disponible';
                                                    })
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->suffixAction(
                                                        Action::make('download')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->label('Descargar PDF')
                                                            ->url(fn() => route('certificado-licencia.ver-actualizado', ['id' => $record->lic_id]))
                                                            ->openUrlInNewTab()
                                                            ->visible(function () use ($record) {
                                                                $service = app(CertificadoLicenciaPdfService::class);
                                                                return $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                            })
                                                    ),
                                            ]),
                                    ])
                            ];
                        })
                        ->action(function (array $data, $record, Action $action) {
                            $service = app(CertificadoLicenciaPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                // Logic for permission request
                                try {
                                    $existeSolicitud = \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->lic_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', 'PENDIENTE')
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA)
                                        ->exists();

                                    if ($existeSolicitud) {
                                        Notification::make()
                                            ->title('Solicitud pendiente')
                                            ->body('Ya existe una solicitud pendiente de actualización para este registro.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    \App\Models\SolicitudPermiso::create([
                                        'module_id' => \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id'),
                                        'record_id' => $record->lic_id,
                                        'user_id' => $user->id,
                                        'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA,
                                        'estado' => \App\Enums\SolicitudPermisoEstado::PENDIENTE,
                                        'observacion' => $data['observacion'],
                                    ]);

                                    Notification::make()
                                        ->title('Solicitud Enviada')
                                        ->body('Su solicitud de actualización ha sido registrada y está pendiente de aprobación.')
                                        ->success()
                                        ->send();

                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Ocurrió un error al enviar la solicitud: ' . $e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                                return;
                            }

                            // Normal Upload Logic (for Authorized or New PDF)
                            $service = app(CertificadoLicenciaPdfService::class);

                            // Obtenemos el archivo temporal
                            $file = $data['certificado_actualizado'];

                            // Aseguramos que no sea un array (por si acaso)
                            if (is_array($file)) {
                                $file = reset($file);
                            }

                            try {
                                $result = $service->subirPdfActualizado(
                                    $record->lic_id,
                                    $record->lic_numlic ?? '',
                                    $file
                                );

                                if (!$result['success']) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body($result['message'])
                                        ->danger()
                                        ->send();

                                    $action->halt();
                                }

                                // Si se subió correctamente y el usuario tenía permiso, lo finalizamos
                                if ($tienePermiso && !($user_role_id === 1 || $user_role_id === 2)) {
                                    \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->lic_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_LICENCIA)
                                        ->update([
                                            'estado' => \App\Enums\SolicitudPermisoEstado::FINALIZADO
                                        ]);
                                }

                                Notification::make()
                                    ->title('Éxito')
                                    ->body($result['message'])
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Error del Sistema')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();

                                $action->halt();
                            }
                        }),

                    Action::make('ver_compatibilidad')
                        ->label('Compatibilidad')
                        ->icon('heroicon-o-document-check')
                        ->color(Color::Violet)
                        ->tooltip('Gestionar certificado de compatibilidad')
                        ->visible(fn() => auth()->user()->hasPermissionTo('upload_compatibility::certificado_licencia_funcionamiento'))
                        ->modalHeading(function ($record) {
                            $service = app(CompatibilidadCertificadoPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'Solicitar Permiso de Actualización';
                            }

                            return 'Gestión de Certificado de Compatibilidad';



                        })
                        ->modalDescription(function ($record) {
                            $service = app(CompatibilidadCertificadoPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'El certificado actualizado ya existe. Para volver a subirlo, necesitas solicitar un permiso. Por favor, indica el motivo.';
                            }

                            return 'Modal para subir y descargar certificado de licencia actualizado';
                        })
                        ->modalWidth(function ($record) {
                            $service = app(CompatibilidadCertificadoPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'md';
                            }

                            return '5xl';
                        })
                        ->modalSubmitActionLabel(function ($record) {
                            $service = app(CompatibilidadCertificadoPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'Enviar Solicitud';
                            }

                            return 'Subir Certificado';
                        })
                        ->modalCancelActionLabel('Cerrar')
                        ->form(function ($record) {
                            $service = app(CompatibilidadCertificadoPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return [
                                    \Filament\Forms\Components\Textarea::make('observacion')
                                        ->label('Motivo de la solicitud')
                                        ->required()
                                        ->rows(3)
                                        ->placeholder('Ingrese el motivo por el cual desea volver a subir la compatibilidad...')
                                ];
                            }

                            return [
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Subir/Actualizar Compatibilidad')
                                            ->description('Suba o actualice el certificado de compatibilidad en formato PDF')
                                            ->icon('heroicon-o-arrow-up-tray')
                                            ->columnSpan(1)
                                            ->schema([
                                                FileUpload::make('compatibilidad_actualizado')
                                                    ->label('Archivo PDF')
                                                    ->acceptedFileTypes(['application/pdf'])
                                                    ->maxSize(10240) // 10MB
                                                    ->disk('local')
                                                    ->directory('temp')
                                                    ->visibility('private')
                                                    ->downloadable()
                                                    ->openable()
                                                    ->previewable()
                                                    ->helperText('Seleccione un archivo PDF (máx. 10MB) y haga clic en "Subir Certificado"')
                                                    ->storeFiles(false)
                                                    ->required(),

                                                Hidden::make('lic_id')
                                                    ->default(fn($record) => $record->lic_id),
                                                Hidden::make('lic_numlic')
                                                    ->default(fn($record) => $record->lic_numlic),
                                            ]),

                                        Section::make('Descargar Compatibilidad')
                                            ->description('Descargue el certificado de compatibilidad actualizado')
                                            ->icon('heroicon-o-arrow-down-tray')
                                            ->columnSpan(1)
                                            ->schema([
                                                TextInput::make('compatibilidad_status')
                                                    ->label('Estado del Certificado')
                                                    ->default(function () use ($record) {
                                                        $service = app(CompatibilidadCertificadoPdfService::class);
                                                        $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                        return $exists ? '✓ Certificado Disponible' : '⚠ No Disponible';
                                                    })
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->suffixIcon(function () use ($record) {
                                                        $service = app(CompatibilidadCertificadoPdfService::class);
                                                        $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                        return $exists ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle';
                                                    })
                                                    ->suffixIconColor(function () use ($record) {
                                                        $service = app(CompatibilidadCertificadoPdfService::class);
                                                        $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                        return $exists ? 'success' : 'warning';
                                                    }),

                                                TextInput::make('compatibilidad_link')
                                                    ->label('Descargar')
                                                    ->default(function () use ($record) {
                                                        $service = app(CompatibilidadCertificadoPdfService::class);
                                                        $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                        return $exists ? 'Listo para descargar' : 'No disponible';
                                                    })
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->suffixAction(
                                                        Action::make('download')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->label('Descargar PDF')
                                                            ->url(fn() => route('certificado-licencia.ver-compatibilidad', ['id' => $record->lic_id]))
                                                            ->openUrlInNewTab()
                                                            ->visible(function () use ($record) {
                                                                $service = app(CompatibilidadCertificadoPdfService::class);
                                                                return $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);
                                                            })
                                                    ),
                                            ]),
                                    ])
                            ];
                        })
                        ->action(function (array $data, $record, Action $action) {
                            $service = app(CompatibilidadCertificadoPdfService::class);
                            $exists = $service->existePdfActualizado($record->lic_numlic ?? '', $record->lic_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                // Logic for permission request
                                try {
                                    $existeSolicitud = \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->lic_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', 'PENDIENTE')
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD)
                                        ->exists();

                                    if ($existeSolicitud) {
                                        Notification::make()
                                            ->title('Solicitud pendiente')
                                            ->body('Ya existe una solicitud pendiente de actualización para este registro.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    \App\Models\SolicitudPermiso::create([
                                        'module_id' => \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id'),
                                        'record_id' => $record->lic_id,
                                        'user_id' => $user->id,
                                        'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD,
                                        'estado' => \App\Enums\SolicitudPermisoEstado::PENDIENTE,
                                        'observacion' => $data['observacion'],
                                    ]);

                                    Notification::make()
                                        ->title('Solicitud Enviada')
                                        ->body('Su solicitud de actualización ha sido registrada y está pendiente de aprobación.')
                                        ->success()
                                        ->send();

                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Ocurrió un error al enviar la solicitud: ' . $e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                                return;
                            }

                            // Normal Upload Logic (for Authorized or New PDF)
                            $service = app(CompatibilidadCertificadoPdfService::class);

                            // Obtenemos el archivo temporal
                            $file = $data['compatibilidad_actualizado'];

                            // Aseguramos que no sea un array (por si acaso)
                            if (is_array($file)) {
                                $file = reset($file);
                            }

                            try {
                                $result = $service->subirPdfActualizado(
                                    $record->lic_id,
                                    $record->lic_numlic ?? '',
                                    $file
                                );

                                if (!$result['success']) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body($result['message'])
                                        ->danger()
                                        ->send();

                                    $action->halt();
                                }

                                // Si se subió correctamente y el usuario tenía permiso, lo finalizamos
                                if ($tienePermiso && !($user_role_id === 1 || $user_role_id === 2)) {
                                    \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->lic_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_COMPATIBILIDAD)
                                        ->update([
                                            'estado' => \App\Enums\SolicitudPermisoEstado::FINALIZADO
                                        ]);
                                }

                                Notification::make()
                                    ->title('Éxito')
                                    ->body($result['message'])
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Error del Sistema')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();

                                $action->halt();
                            }
                        }),
                ])->label('Docum.')
                    ->icon('heroicon-o-document-duplicate')
                    ->color(Color::Teal)
                    ->outlined()
                    ->button(),

                \Filament\Actions\ActionGroup::make([
                    Action::make('licencia-duplicar')
                        ->label('Duplicar licencia')
                        ->icon('ionicon-duplicate-outline')
                        ->tooltip('Duplicar licencia')
                        ->color(Color::Purple)
                        ->visible(fn() => auth()->user()->hasPermissionTo('duplicate::certificado_licencia_funcionamiento'))
                        ->url(function (CertificadoLicenciaFuncionamiento $record) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DUPLICAR_LICENCIA)
                                ->exists();

                            return $tienePermiso ? CertificadoLicenciaFuncionamientoResource::getUrl('duplicate', ['record' => $record]) : null;
                        })
                        ->modalHeading('Solicitar Permiso de Duplicación')
                        ->modalDescription('No tienes permisos directos para realizar esta acción. Por favor, indica el motivo para solicitar la duplicación.')
                        ->modalSubmitActionLabel('Enviar Solicitud')
                        ->form([
                            \Filament\Forms\Components\Textarea::make('observacion')
                                ->label('Motivo de la solicitud')
                                ->required()
                                ->rows(3)
                                ->placeholder('Ingrese el motivo por el cual desea duplicar...'),
                        ])
                        ->action(function (CertificadoLicenciaFuncionamiento $record, array $data) {
                            try {
                                $user = auth()->user();

                                $existe = \App\Models\SolicitudPermiso::query()
                                    ->where('record_id', $record->lic_id)
                                    ->where('user_id', $user->id)
                                    ->where('estado', 'PENDIENTE')
                                    ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DUPLICAR_LICENCIA)
                                    ->exists();

                                if ($existe) {
                                    Notification::make()
                                        ->title('Solicitud pendiente')
                                        ->body('Ya existe una solicitud pendiente de duplicación para este registro.')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                \App\Models\SolicitudPermiso::create([
                                    'module_id' => \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id'),
                                    'record_id' => $record->lic_id,
                                    'user_id' => $user->id,
                                    'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::DUPLICAR_LICENCIA,
                                    'estado' => \App\Enums\SolicitudPermisoEstado::PENDIENTE,
                                    'observacion' => $data['observacion'],
                                ]);

                                Notification::make()
                                    ->title('Solicitud Enviada')
                                    ->body('Su solicitud de duplicación ha sido registrada y está pendiente de aprobación.')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Ocurrió un error al enviar la solicitud: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Action::make('licencia-transferir')
                        ->label('Transferir licencia')
                        ->icon('lineawesome-handshake')
                        ->tooltip('Transferir licencia')
                        ->color(Color::Teal)
                        ->visible(fn() => auth()->user()->hasPermissionTo('transfer::certificado_licencia_funcionamiento'))
                        ->url(function (CertificadoLicenciaFuncionamiento $record) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::TRANSFERIR_LICENCIA)
                                ->exists();

                            return $tienePermiso ? CertificadoLicenciaFuncionamientoResource::getUrl('transfer', ['record' => $record]) : null;
                        })
                        ->modalHeading('Solicitar Permiso de Transferencia')
                        ->modalDescription('No tienes permisos directos para realizar esta acción. Por favor, indica el motivo para solicitar la transferencia.')
                        ->modalSubmitActionLabel('Enviar Solicitud')
                        ->form([
                            \Filament\Forms\Components\Textarea::make('observacion')
                                ->label('Motivo de la solicitud')
                                ->required()
                                ->rows(3)
                                ->placeholder('Ingrese el motivo por el cual desea transferir...'),
                        ])
                        ->action(function (CertificadoLicenciaFuncionamiento $record, array $data) {
                            try {
                                $user = auth()->user();

                                $existe = \App\Models\SolicitudPermiso::query()
                                    ->where('record_id', $record->lic_id)
                                    ->where('user_id', $user->id)
                                    ->where('estado', 'PENDIENTE')
                                    ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::TRANSFERIR_LICENCIA)
                                    ->exists();

                                if ($existe) {
                                    Notification::make()
                                        ->title('Solicitud pendiente')
                                        ->body('Ya existe una solicitud pendiente de transferencia para este registro.')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                \App\Models\SolicitudPermiso::create([
                                    'module_id' => \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id'),
                                    'record_id' => $record->lic_id,
                                    'user_id' => $user->id,
                                    'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::TRANSFERIR_LICENCIA,
                                    'estado' => \App\Enums\SolicitudPermisoEstado::PENDIENTE,
                                    'observacion' => $data['observacion'],
                                ]);

                                Notification::make()
                                    ->title('Solicitud Enviada')
                                    ->body('Su solicitud de transferencia ha sido registrada y está pendiente de aprobación.')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Ocurrió un error al enviar la solicitud: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Action::make('licencia-cesionario')
                        ->label('Cesionario licencia')
                        ->icon('gmdi-account-tree-o')
                        ->tooltip('Cesionario licencia')
                        ->color(Color::Yellow)
                        ->visible(fn() => auth()->user()->hasPermissionTo('assign::certificado_licencia_funcionamiento'))
                        ->url(function (CertificadoLicenciaFuncionamiento $record) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::CESIONAR_LICENCIA)
                                ->exists();

                            return $tienePermiso ? CertificadoLicenciaFuncionamientoResource::getUrl('cesionario', ['record' => $record]) : null;
                        })
                        ->modalHeading('Solicitar Permiso de Cesionario')
                        ->modalDescription('No tienes permisos directos para realizar esta acción. Por favor, indica el motivo para solicitar el cesionario.')
                        ->modalSubmitActionLabel('Enviar Solicitud')
                        ->form([
                            \Filament\Forms\Components\Textarea::make('observacion')
                                ->label('Motivo de la solicitud')
                                ->required()
                                ->rows(3)
                                ->placeholder('Ingrese el motivo por el cual desea realizar el cesionario...'),
                        ])
                        ->action(function (CertificadoLicenciaFuncionamiento $record, array $data) {
                            try {
                                $user = auth()->user();

                                $existe = \App\Models\SolicitudPermiso::query()
                                    ->where('record_id', $record->lic_id)
                                    ->where('user_id', $user->id)
                                    ->where('estado', 'PENDIENTE')
                                    ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::CESIONAR_LICENCIA)
                                    ->exists();

                                if ($existe) {
                                    Notification::make()
                                        ->title('Solicitud pendiente')
                                        ->body('Ya existe una solicitud pendiente de cesionario para este registro.')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                \App\Models\SolicitudPermiso::create([
                                    'module_id' => \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id'),
                                    'record_id' => $record->lic_id,
                                    'user_id' => $user->id,
                                    'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::CESIONAR_LICENCIA,
                                    'estado' => \App\Enums\SolicitudPermisoEstado::PENDIENTE,
                                    'observacion' => $data['observacion'],
                                ]);

                                Notification::make()
                                    ->title('Solicitud Enviada')
                                    ->body('Su solicitud de cesionario ha sido registrada y está pendiente de aprobación.')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Ocurrió un error al enviar la solicitud: ' . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Action::make('dar_de_baja')
                        ->label('Dar de baja')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->tooltip('Dar de baja licencia')
                        ->color('danger')
                        ->visible(fn() => auth()->user()->hasPermissionTo('deactivate::certificado_licencia_funcionamiento'))
                        ->modalHeading(function (CertificadoLicenciaFuncionamiento $record) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA)
                                ->exists();

                            return $tienePermiso ? 'Dar de baja licencia' : 'Solicitar Permiso de Dar de Baja';
                        })
                        ->modalDescription(function (CertificadoLicenciaFuncionamiento $record) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA)
                                ->exists();

                            if ($tienePermiso) {
                                return new HtmlString('¿Está <strong>seguro</strong> que desea <strong>dar de baja</strong> esta licencia?');
                            }
                            return 'No tienes permisos directos para realizar esta acción. Por favor, indica el motivo para solicitar la baja.';
                        })
                        ->modalSubmitActionLabel(function (CertificadoLicenciaFuncionamiento $record) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA)
                                ->exists();

                            return $tienePermiso ? 'Confirmar Baja' : 'Enviar Solicitud';
                        })
                        ->fillForm(function (CertificadoLicenciaFuncionamiento $record) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA)
                                ->exists();

                            if ($tienePermiso) {
                                return [
                                    'nro_expediente' => $record->lic_expnum,
                                    'nro_resolucion' => $record->lic_resnum,
                                    'fecha_resolucion' => $record->lic_fecharesolucion,
                                    'fecha_baja' => now(),
                                ];
                            }
                            return [];
                        })
                        ->form(function (CertificadoLicenciaFuncionamiento $record) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA)
                                ->exists();

                            if ($tienePermiso) {
                                return [
                                    TextInput::make('nro_expediente')
                                        ->label('Nro Expediente')
                                        ->required()
                                        ->maxLength(50),
                                    TextInput::make('anexo')
                                        ->label('Anexo')
                                        ->required()
                                        ->maxLength(50),
                                    TextInput::make('nro_resolucion')
                                        ->label('Nro Resolución')
                                        ->required()
                                        ->maxLength(100),
                                    DatePicker::make('fecha_baja')
                                        ->label('Fecha Baja')
                                        ->required()
                                        ->native(false)
                                        ->displayFormat('d/m/Y')
                                        ->default(now()),
                                    DatePicker::make('fecha_resolucion')
                                        ->label('Fecha Resolución')
                                        ->required()
                                        ->native(false)
                                        ->displayFormat('d/m/Y'),
                                ];
                            }

                            return [
                                \Filament\Forms\Components\Textarea::make('observacion')
                                    ->label('Motivo de la solicitud')
                                    ->required()
                                    ->rows(3)
                                    ->placeholder('Ingrese el motivo por el cual desea dar de baja...'),
                            ];
                        })
                        ->action(function (CertificadoLicenciaFuncionamiento $record, array $data, Action $action) {
                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;

                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 2) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->lic_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA)
                                ->exists();

                            if ($tienePermiso) {
                                try {
                                    $service = new \App\Services\Sil\Licencias\LicenciaBajaService();
                                    $serviceData = [
                                        'lic_id' => $record->lic_id,
                                        'lib_expnum' => $data['nro_expediente'],
                                        'lib_anexo' => $data['anexo'],
                                        'lib_resnum' => $data['nro_resolucion'],
                                        'lib_fecharesolucion' => $data['fecha_resolucion'],
                                        'lib_fechabaja' => $data['fecha_baja'],
                                    ];
                                    $resultado = $service->bajaLicencia($serviceData);
                                    if ($resultado->error > 0) {
                                        Notification::make()
                                            ->title('Éxito')
                                            ->body($resultado->mensaje)
                                            ->success()
                                            ->send();

                                        // Finalizar el permiso si existe y fue usado con exito
                                        \App\Models\SolicitudPermiso::query()
                                            ->where('record_id', $record->lic_id)
                                            ->where('user_id', auth()->id())
                                            ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                            ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA)
                                            ->update([
                                                'estado' => \App\Enums\SolicitudPermisoEstado::FINALIZADO
                                            ]);
                                    } else {
                                        Notification::make()
                                            ->title('Error')
                                            ->body($resultado->mensaje)
                                            ->danger()
                                            ->send();
                                        $action->halt();
                                    }
                                } catch (\Throwable $e) {
                                    Notification::make()
                                        ->title('Error del Sistema')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                    $action->halt();
                                }
                            } else {
                                // Solicitud Logic
                                try {
                                    $user = auth()->user();

                                    $existe = \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->lic_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', 'PENDIENTE')
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA)
                                        ->exists();

                                    if ($existe) {
                                        Notification::make()
                                            ->title('Solicitud pendiente')
                                            ->body('Ya existe una solicitud pendiente de baja para este registro.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    \App\Models\SolicitudPermiso::create([
                                        'module_id' => \App\Models\Module::where('filament_class', CertificadoLicenciaFuncionamientoResource::class)->value('id'),
                                        'record_id' => $record->lic_id,
                                        'user_id' => $user->id,
                                        'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::DAR_BAJA_LICENCIA,
                                        'estado' => \App\Enums\SolicitudPermisoEstado::PENDIENTE,
                                        'observacion' => $data['observacion'],
                                    ]);

                                    Notification::make()
                                        ->title('Solicitud Enviada')
                                        ->body('Su solicitud de baja ha sido registrada y está pendiente de aprobación.')
                                        ->success()
                                        ->send();

                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body('Ocurrió un error al enviar la solicitud: ' . $e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }
                        }),
                ])->label('Estado')
                    ->icon('gmdi-manage-history-o')
                    ->color(Color::Indigo)
                    ->outlined()
                    ->button(),

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
