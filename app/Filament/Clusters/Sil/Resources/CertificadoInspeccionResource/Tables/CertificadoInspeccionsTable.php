<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Tables;

use Illuminate\Support\Facades\Storage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Indicator;
use App\Models\CertificadoInspeccion;
use App\Services\Sil\CertificadoInspeccion\CertificadoInspeccionService;
use App\Services\Sil\CertificadoInspeccion\CertificadoInspeccionAnexoService;
use Filament\Support\Colors\Color;

use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;




/**
 * Configuración de la tabla de Certificados de Inspección para Filament.
 *
 * Esta clase centraliza la definición de columnas, filtros, acciones y otras
 * opciones de la tabla que se muestran en la Resource `CertificadoInspeccion`.
 *
 * - columns(): define las columnas visibles/ocultas y su formato.
 * - filters(): define filtros Select y de rango que respetan la búsqueda y
 *   los indicadores mostrados en la UI.
 * - recordActions(): define acciones por fila (ver, editar, exportar, borrar).
 * - toolbarActions(): define acciones en la barra superior (bulk actions).
 *
 * Todos los métodos/manejadores son estáticos y la única entrada pública es
 * `configure(Table $table): Table` que devuelve la tabla ya configurada.
 */
class CertificadoInspeccionsTable
{
    protected static $service;

    /**
     * Aplica la configuración completa a un objeto `Filament\Tables\Table`.
     *
     * Este método recibe la instancia de la tabla y encadena las llamadas para
     * definir orden por defecto, búsqueda, columnas, filtros, acciones por
     * registro y otras opciones específicas de la UI.
     *
     * @param Table $table Instancia de la tabla a configurar.
     * @return Table La instancia de tabla configurada (encadenable).
     */
    public static function configure(Table $table): Table
    {
        if (!isset(self::$service)) {
            self::$service = new CertificadoInspeccionService();
        }

        return $table
            ->defaultSort('cin_fecha', 'desc')
            ->searchable(true)
            ->recordUrl(null)
            ->columns([
                TextColumn::make('tipoEdificacion.tie_descripcion')
                    ->label('Edificación')
                    ->numeric()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'RIESGO BAJO' => 'info',
                        'RIESGO MEDIO' => 'warning',
                        'RIESGO ALTO' => 'danger-high',
                        'RIESGO MUY ALTO' => 'danger-very-high',
                        "EX POST" => 'info',
                        "EX ANTE" => 'info',
                        "DE PARTE" => 'info',
                        "DE DETALLE" => 'info',
                    })
                    ->sortable(),
                TextColumn::make('cin_anio')
                    ->label('Año')
                    ->sortable(),
                TextColumn::make('cin_numero')
                    ->label('N° Certificado')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_expediente')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('cin_resolucion')->label('Resolución')->hidden()->searchable(),
                TextColumn::make('cin_resolucion_sigla')->label('Resolución Sigla')->hidden()->searchable(),

                TextColumn::make('cin_resolucion_completa')
                    ->label('Resolución')
                    ->getStateUsing(fn($record) => $record->cin_resolucion . $record->cin_resolucion_sigla),
                TextColumn::make('cin_solicitante')
                    ->label('Solicitante')
                    ->searchable(),
                TextColumn::make('cin_ubicacion')
                    ->label('Ubicación')
                    ->searchable()
                    ->extraAttributes([
                        'style' => 'max-width:320px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;',
                    ])
                    ->tooltip(fn($record) => $record->cin_ubicacion),
                TextColumn::make('cin_giro')
                    ->label('Giro')
                    ->searchable()
                    ->extraAttributes([
                        'style' => 'max-width:320px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;',
                    ])
                    ->tooltip(fn($record) => $record->cin_giro),
                TextColumn::make('cin_fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_fec_inicio')
                    ->label('Vig. Fec. Inicio')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_fec_fin')
                    ->label('Vig. Fec. Fin')
                    ->date('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_capacidad')
                    ->label('Capacidad')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin_area')
                    ->label('Área (m²)')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: '.',
                        thousandsSeparator: ','
                    )
                    ->searchable()
                    ->sortable(),



                IconColumn::make('cin_indeterminado')
                    ->label('Indeterminado')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('cin_filafecha')
                    ->label('Fecha de Fila')
                    ->dateTime()
                    ->hidden()
                    ->sortable(),
                IconColumn::make('cin_filaoriginal')
                    ->label('Fila Original')
                    ->hidden()
                    ->boolean(),
                IconColumn::make('cin_filaeliminada')
                    ->label('Fila Eliminada')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('usa_id')
                    ->numeric()
                    ->hidden(),

                IconColumn::make('cin_consello')
                    ->label('Consello')
                    ->hidden()
                    ->boolean(),
                TextColumn::make('lic_id')
                    ->label('Licencia ID')
                    ->numeric()
                    ->hidden(),
                TextColumn::make('cin_departamento')
                    ->label('Departamento')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_provincia')
                    ->label('Provincia')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_distrito')
                    ->label('Distrito')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_licencia')
                    ->label('Licencia Número')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('cin_procedimiento')
                    ->label('Procedimiento')
                    ->hidden()
                    ->searchable(),

                TextColumn::make('cin_nota')
                    ->label('Nota')
                    ->hidden()
                    ->searchable(),



                TextColumn::make('cin_establecimiento')
                    ->label('Establecimiento')
                    ->hidden()
                    ->searchable(),
            ])
            ->filters([
                //title filter
                SelectFilter::make('tie_id')
                    ->label('Tipo de Edificación')
                    ->relationship('tipoEdificacion', 'tie_descripcion')
                    ->searchable()
                    ->preload()
                    ->indicator('Tipo de Edificación')
                    ->placeholder('Todos los tipos'),

                SelectFilter::make('cin_anio')
                    ->label('Año')
                    ->options(fn() => CertificadoInspeccion::query()
                        ->distinct()
                        ->orderBy('cin_anio', 'desc')
                        ->pluck('cin_anio', 'cin_anio')
                        ->toArray())
                    ->searchable()
                    ->indicator('Año')
                    ->placeholder('Todos los años')
                    ->native(false),

                SelectFilter::make('cin_numero')
                    ->label('N° Certificado')
                    ->options(fn() => CertificadoInspeccion::query()
                        ->select('cin_numero')
                        ->distinct()
                        ->whereNotNull('cin_numero')
                        ->orderByDesc('cin_numero')
                        ->pluck('cin_numero')
                        ->mapWithKeys(fn($v) => [(string) $v => (string) $v])
                        ->toArray())
                    ->searchable()
                    ->indicator('N° Certificado')
                    ->placeholder('Buscar número...')
                    ->native(false),

                SelectFilter::make('cin_solicitante')
                    ->label('Solicitante')
                    ->options(fn() => CertificadoInspeccion::query()
                        ->distinct()
                        ->whereNotNull('cin_solicitante')
                        ->where('cin_solicitante', '!=', '')
                        ->orderBy('cin_solicitante', 'asc')
                        ->pluck('cin_solicitante', 'cin_solicitante')
                        ->toArray())
                    ->searchable()
                    ->indicator('Solicitante')
                    ->placeholder('Buscar solicitante...')
                    ->native(false),

                SelectFilter::make('cin_ubicacion')
                    ->label('Ubicación')
                    ->searchable()

                    ->getSearchResultsUsing(function (string $search): array {
                        $ubicaciones = self::$service->buscarUbicacion($search);
                        return array_combine($ubicaciones, $ubicaciones);
                    })
                    ->getOptionLabelUsing(fn($value): string => $value)
                    ->indicator('Ubicación')
                    ->placeholder('Buscar ubicación...')
                    ->native(false),

                SelectFilter::make('cin_giro')
                    ->label('Giro del Negocio')
                    ->options(fn() => CertificadoInspeccion::query()
                        ->distinct()
                        ->whereNotNull('cin_giro')
                        ->where('cin_giro', '!=', '')
                        ->orderBy('cin_giro', 'asc')
                        ->pluck('cin_giro', 'cin_giro')
                        ->toArray())
                    ->searchable()
                    ->indicator('Giro')
                    ->placeholder('Buscar giro...')
                    ->native(false),

                Filter::make('cin_fecha')
                    ->label('Fecha del Certificado')
                    ->form([
                        DatePicker::make('from')
                            ->label('Desde')
                            ->placeholder('Seleccionar fecha inicial')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),
                        DatePicker::make('to')
                            ->label('Hasta')
                            ->placeholder('Seleccionar fecha final')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn($query, $date) =>
                                $query->whereDate('cin_fecha', '>=', Carbon::parse($date))
                            )
                            ->when(
                                $data['to'],
                                fn($query, $date) =>
                                $query->whereDate('cin_fecha', '<=', Carbon::parse($date))
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Indicator::make('Desde: ' . Carbon::parse($data['from'])->format('d/m/Y'))
                                ->removeField('from');
                        }

                        if ($data['to'] ?? null) {
                            $indicators[] = Indicator::make('Hasta: ' . Carbon::parse($data['to'])->format('d/m/Y'))
                                ->removeField('to');
                        }

                        return $indicators;
                    }),

                SelectFilter::make('cin_expediente')
                    ->label('N° Expediente')
                    ->options(fn() => CertificadoInspeccion::query()
                        ->distinct()
                        ->whereNotNull('cin_expediente')
                        ->where('cin_expediente', '!=', '')
                        ->orderBy('cin_expediente', 'desc')
                        ->pluck('cin_expediente', 'cin_expediente')
                        ->toArray())
                    ->searchable()
                    ->indicator('N° Expediente')
                    ->placeholder('Buscar expediente...')
                    ->native(false),

            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(4)
            ->filtersFormMaxHeight('400px')
            ->recordActions([


                ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip('Ver detalles del certificado')
                    ->color('info')
                    ->visible(fn() => auth()->user()->hasPermissionTo('view_details::certificado_inspeccion')),

                EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Editar certificado')
                    ->color('warning')
                    ->visible(function ($record) {
                        $user = auth()->user();

                        // Primero verificar el permiso del sistema
                        if (!$user->hasPermissionTo('edit::certificado_inspeccion')) {
                            return false;
                        }

                        // Roles 1 y 6: pueden editar directamente
                        $user_role_id = $user->modelHasRole?->role_id;
                        if ($user_role_id === 1 || $user_role_id === 6) {
                            return true;
                        }

                        // Otros roles: solo si tienen SolicitudPermiso APROBADA
                        return \App\Models\SolicitudPermiso::query()
                            ->where('record_id', $record->cin_id)
                            ->where('user_id', $user->id)
                            ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                            ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::EDITAR_DATOS_ITSE)
                            ->exists();
                    })
                    ->url(function ($record) {
                        return \App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource::getUrl('edit', ['record' => $record]);
                    }),

                Action::make('solicitar_editar')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->iconButton()
                    ->tooltip('Solicitar permiso para editar')
                    ->color('warning')
                    ->modalHeading('Solicitar Permiso de Edición')
                    ->modalDescription('Para editar este certificado, debe solicitar un permiso. Por favor, indique el motivo de la edición.')
                    ->modalWidth('md')
                    ->modalSubmitActionLabel('Enviar Solicitud')
                    ->modalCancelActionLabel('Cancelar')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('observacion')
                            ->label('Motivo de la solicitud')
                            ->required()
                            ->rows(3)
                            ->placeholder('Ingrese el motivo por el cual desea editar este certificado...')
                    ])
                    ->action(function (array $data, $record) {
                        $user = auth()->user();
                        try {
                            $existeSolicitud = \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', 'PENDIENTE')
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::EDITAR_DATOS_ITSE)
                                ->exists();

                            if ($existeSolicitud) {
                                Notification::make()
                                    ->title('Solicitud pendiente')
                                    ->body('Ya existe una solicitud pendiente de edición para este registro.')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            \App\Models\SolicitudPermiso::create([
                                'module_id' => \App\Models\Module::where('filament_class', \App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource::class)->value('id'),
                                'record_id' => $record->cin_id,
                                'user_id' => $user->id,
                                'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::EDITAR_DATOS_ITSE,
                                'estado' => \App\Enums\SolicitudPermisoEstado::PENDIENTE,
                                'observacion' => $data['observacion'],
                            ]);

                            Notification::make()
                                ->title('Solicitud Enviada')
                                ->body('Su solicitud de edición ha sido registrada y está pendiente de aprobación.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body('Ocurrió un error al enviar la solicitud: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(function ($record) {
                        $user = auth()->user();

                        // Primero verificar el permiso del sistema
                        if (!$user->hasPermissionTo('edit::certificado_inspeccion')) {
                            return false;
                        }

                        // Roles 1 y 6: NO muestran esta acción (ya ven el botón de edición directa)
                        $user_role_id = $user->modelHasRole?->role_id;
                        if ($user_role_id === 1 || $user_role_id === 6) {
                            return false;
                        }

                        // Otros roles: mostrar solo si NO tienen SolicitudPermiso APROBADA
                        $tieneSolicitudAprobada = \App\Models\SolicitudPermiso::query()
                            ->where('record_id', $record->cin_id)
                            ->where('user_id', $user->id)
                            ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                            ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::EDITAR_DATOS_ITSE)
                            ->exists();

                        // Mostrar "solicitar_editar" solo si NO tiene SolicitudPermiso aprobada
                        return !$tieneSolicitudAprobada;
                    }),
                Action::make('borrar')
                    ->label('Borrar')
                    ->icon('heroicon-o-trash')
                    ->tooltip('Borrar certificado')
                    ->iconButton()
                    ->color('danger')
                    ->visible(fn() => auth()->user()->hasPermissionTo('delete::certificado_inspeccion'))
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar Certificado')
                    ->modalDescription(new HtmlString('¿Está <strong>seguro</strong> que desea <strong>eliminar</strong> este certificado? Esta acción no se puede revertir. Se registrará el <strong>usuario</strong> que realiza la eliminación y la <strong>fecha/hora</strong> de la acción.'))
                    ->form([
                        TextInput::make('razon_eliminacion')
                            ->label('Razón de la eliminación')
                            ->required()
                            ->placeholder('Ingrese la razón de la eliminación de este certificado')
                            ->extraAttributes([
                                'style' => '--tw-ring-color: #ef4444; --tw-ring-shadow: 0 0 0 calc(0px + var(--tw-ring-offset-width)) var(--tw-ring-color);'
                            ])
                            ->maxLength(255),
                    ])
                    ->modalSubmitActionLabel('Sí, eliminar')
                    ->modalCancelActionLabel('Cancelar')
                    ->action(function ($record, array $data) {

                        $razon = trim($data['razon_eliminacion'] ?? '');

                        if ($razon === '') {
                            Notification::make()
                                ->danger()
                                ->title('Razón requerida')
                                ->body('Debe especificar la razón de la eliminación antes de confirmar.')
                                ->send();
                            return;
                        }

                        $userId = Auth::id();
                        $cinId = $record->cin_id;
                        self::$service->borrarCertificadoInspeccion($userId, $cinId, $razon);



                        $record->cin_filaeliminada = true;
                        $record->save();
                        Notification::make()
                            ->success()
                            ->title('Certificado eliminado')
                            ->body('El certificado ha sido marcado como eliminado correctamente.')
                            ->send();
                    })
                    ->successRedirectUrl(fn() => request()->header('Referer') ?? route('filament.admin.resources.certificado-inspeccions.index')),


                \Filament\Actions\ActionGroup::make([
                    Action::make('ver_original')
                        ->label('Ver PDF original')
                        ->icon('heroicon-c-check-badge')
                        ->color('gray')
                        ->tooltip('Ver el PDF original generado por el sistema')
                        ->visible(fn($record) => auth()->user()->hasPermissionTo('view_pdf_original::certificado_inspeccion') && Storage::disk('certificados_externos')->exists("originales/certificado_inspeccion_id_{$record->cin_id}.pdf"))
                        ->url(fn($record) => route('certificado.ver-archivo', ['id' => $record->cin_id, 'tipo' => 'original']))
                        ->openUrlInNewTab(),

                    Action::make('ver_actualizado')
                        ->label('Certificado actualizado')
                        ->icon('tabler-certificate')
                        ->color('primary')
                        ->tooltip('Gestionar certificado actualizado')
                        ->visible(fn() => auth()->user()->hasPermissionTo('upload_pdf::certificado_inspeccion'))
                        ->modalHeading(function ($record) {
                            $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'Solicitar Permiso de Actualización';
                            }

                            return 'Gestión de Certificado Actualizado';
                        })
                        ->modalDescription(function ($record) {
                            $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'El certificado ya existe. Para volver a subirlo, necesitas solicitar un permiso. Por favor, indica el motivo.';
                            }

                            return 'Modal para subir y descargar certificado actualizado';
                        })
                        ->modalWidth(function ($record) {
                            $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'md';
                            }

                            return '5xl';
                        })
                        ->modalSubmitActionLabel(function ($record) {
                            $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'Enviar Solicitud';
                            }

                            return 'Subir Certificado';
                        })
                        ->modalCancelActionLabel('Cerrar')
                        ->form(function ($record) {
                            $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE)
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

                                                Hidden::make('cin_id')
                                                    ->default(fn($record) => $record->cin_id),
                                            ]),

                                        Section::make('Descargar Certificado')
                                            ->description('Descargue el certificado actualizado')
                                            ->icon('heroicon-o-arrow-down-tray')
                                            ->columnSpan(1)
                                            ->schema([
                                                TextInput::make('download_status')
                                                    ->label('Estado del Certificado')
                                                    ->default(function () use ($record) {
                                                        $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");
                                                        return $exists ? '✓ Certificado Disponible' : '⚠ No Disponible';
                                                    })
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->suffixIcon(function () use ($record) {
                                                        $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");
                                                        return $exists ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle';
                                                    })
                                                    ->suffixIconColor(function () use ($record) {
                                                        $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");
                                                        return $exists ? 'success' : 'warning';
                                                    }),

                                                TextInput::make('download_link')
                                                    ->label('Descargar')
                                                    ->default(function () use ($record) {
                                                        $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");
                                                        return $exists ? 'Listo para descargar' : 'No disponible';
                                                    })
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->suffixAction(
                                                        Action::make('download')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->label('Descargar PDF')
                                                            ->url(fn() => route('certificado.ver-archivo', ['id' => $record->cin_id, 'tipo' => 'actualizado']))
                                                            ->openUrlInNewTab()
                                                            ->visible(fn() => Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf"))
                                                    ),
                                            ]),
                                    ])
                            ];
                        })
                        ->action(function (array $data, $record, Action $action) {
                            $exists = Storage::disk('certificados_externos')->exists("actualizados/certificado_inspeccion_actualizado_id_{$record->cin_id}.pdf");

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                // Logic for permission request
                                try {
                                    $existeSolicitud = \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->cin_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', 'PENDIENTE')
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE)
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
                                        'module_id' => \App\Models\Module::where('filament_class', \App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource::class)->value('id'),
                                        'record_id' => $record->cin_id,
                                        'user_id' => $user->id,
                                        'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE,
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

                            // Normal Upload Logic
                            $service = app(CertificadoInspeccionService::class);

                            // Obtenemos el archivo temporal
                            $file = $data['certificado_actualizado'];

                            // Aseguramos que no sea un array (por si acaso)
                            if (is_array($file)) {
                                $file = reset($file);
                            }

                            try {
                                $result = $service->subirPdfActualizado($record->cin_id, $file);

                                if (!$result['success']) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body($result['message'])
                                        ->danger()
                                        ->send();

                                    $action->halt();
                                }

                                // Finalize permission if used
                                if ($tienePermiso && !($user_role_id === 1 || $user_role_id === 6)) {
                                    \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->cin_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ITSE)
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

                    Action::make('ver_anexos')
                        ->label('Gestionar anexos')
                        ->icon('tabler-paperclip')
                        ->color('info')
                        ->tooltip('Gestionar anexos')
                        ->visible(fn() => auth()->user()->hasPermissionTo('upload_anexos::certificado_inspeccion'))
                        ->modalHeading(function ($record) {
                            $service = app(CertificadoInspeccionAnexoService::class);
                            $exists = $service->existeAnexo($record->cin_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'Solicitar Permiso de Actualización';
                            }

                            return 'Gestión de Anexos';
                        })
                        ->modalDescription(function ($record) {
                            $service = app(CertificadoInspeccionAnexoService::class);
                            $exists = $service->existeAnexo($record->cin_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'El anexo ya existe. Para volver a subirlo, necesitas solicitar un permiso. Por favor, indica el motivo.';
                            }

                            return 'Modal para subir y descargar anexos del certificado';
                        })
                        ->modalWidth(function ($record) {
                            $service = app(CertificadoInspeccionAnexoService::class);
                            $exists = $service->existeAnexo($record->cin_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'md';
                            }

                            return '5xl';
                        })
                        ->modalSubmitActionLabel(function ($record) {
                            $service = app(CertificadoInspeccionAnexoService::class);
                            $exists = $service->existeAnexo($record->cin_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return 'Enviar Solicitud';
                            }

                            return 'Subir Anexo';
                        })
                        ->modalCancelActionLabel('Cerrar')
                        ->form(function ($record) {
                            $service = app(CertificadoInspeccionAnexoService::class);
                            $exists = $service->existeAnexo($record->cin_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                return [
                                    \Filament\Forms\Components\Textarea::make('observacion')
                                        ->label('Motivo de la solicitud')
                                        ->required()
                                        ->rows(3)
                                        ->placeholder('Ingrese el motivo por el cual desea volver a subir el anexo...')
                                ];
                            }

                            return [
                                Grid::make(2)
                                    ->schema([
                                        Section::make('Subir/Actualizar Anexo')
                                            ->description('Suba o actualice el anexo en formato PDF')
                                            ->icon('heroicon-o-arrow-up-tray')
                                            ->columnSpan(1)
                                            ->schema([
                                                FileUpload::make('anexo_pdf')
                                                    ->label('Archivo PDF')
                                                    ->acceptedFileTypes(['application/pdf'])
                                                    ->maxSize(10240) // 10MB
                                                    ->disk('local')
                                                    ->directory('temp')
                                                    ->visibility('private')
                                                    ->downloadable()
                                                    ->openable()
                                                    ->previewable()
                                                    ->helperText('Seleccione un archivo PDF (máx. 10MB) y haga clic en "Subir Anexo"')
                                                    ->storeFiles(false)
                                                    ->required(),

                                                Hidden::make('cin_id')
                                                    ->default(fn($record) => $record->cin_id),
                                            ]),

                                        Section::make('Descargar Anexo')
                                            ->description('Descargue el anexo del certificado')
                                            ->icon('heroicon-o-arrow-down-tray')
                                            ->columnSpan(1)
                                            ->schema([
                                                TextInput::make('anexo_status')
                                                    ->label('Estado del Anexo')
                                                    ->default(function () use ($record, $service) {
                                                        $exists = $service->existeAnexo($record->cin_id);
                                                        return $exists ? '✓ Anexo Disponible' : '⚠ No Disponible';
                                                    })
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->suffixIcon(function () use ($record, $service) {
                                                        $exists = $service->existeAnexo($record->cin_id);
                                                        return $exists ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle';
                                                    })
                                                    ->suffixIconColor(function () use ($record, $service) {
                                                        $exists = $service->existeAnexo($record->cin_id);
                                                        return $exists ? 'success' : 'warning';
                                                    }),

                                                TextInput::make('anexo_download')
                                                    ->label('Descargar')
                                                    ->default(function () use ($record, $service) {
                                                        $exists = $service->existeAnexo($record->cin_id);
                                                        return $exists ? 'Listo para descargar' : 'No disponible';
                                                    })
                                                    ->disabled()
                                                    ->dehydrated(false)
                                                    ->suffixAction(
                                                        Action::make('download_anexo')
                                                            ->icon('heroicon-o-arrow-down-tray')
                                                            ->label('Descargar PDF')
                                                            ->url(fn() => route('certificado.ver-archivo', ['id' => $record->cin_id, 'tipo' => 'anexo']))
                                                            ->openUrlInNewTab()
                                                            ->visible(function () use ($record, $service) {
                                                                return $service->existeAnexo($record->cin_id);
                                                            })
                                                    ),
                                            ]),
                                    ])
                            ];
                        })
                        ->action(function (array $data, $record, Action $action) {
                            $service = app(CertificadoInspeccionAnexoService::class);
                            $exists = $service->existeAnexo($record->cin_id);

                            $user = auth()->user();
                            $user_role_id = $user->modelHasRole?->role_id;
                            $tienePermiso = ($user_role_id === 1 || $user_role_id === 6) || \App\Models\SolicitudPermiso::query()
                                ->where('record_id', $record->cin_id)
                                ->where('user_id', $user->id)
                                ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS)
                                ->exists();

                            if ($exists && !$tienePermiso) {
                                // Logic for permission request
                                try {
                                    $existeSolicitud = \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->cin_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', 'PENDIENTE')
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS)
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
                                        'module_id' => \App\Models\Module::where('filament_class', \App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\CertificadoInspeccionResource::class)->value('id'),
                                        'record_id' => $record->cin_id,
                                        'user_id' => $user->id,
                                        'tipo_accion' => \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS,
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

                            // Normal Upload Logic
                            // Obtenemos el archivo temporal
                            $file = $data['anexo_pdf'];

                            // Aseguramos que no sea un array (por si acaso)
                            if (is_array($file)) {
                                $file = reset($file);
                            }

                            try {
                                $result = $service->subirPdfAnexo($record->cin_id, $file);

                                if (!$result['success']) {
                                    Notification::make()
                                        ->title('Error')
                                        ->body($result['message'])
                                        ->danger()
                                        ->send();

                                    $action->halt();
                                }

                                // Finalize permission if used
                                if ($tienePermiso && !($user_role_id === 1 || $user_role_id === 6)) {
                                    \App\Models\SolicitudPermiso::query()
                                        ->where('record_id', $record->cin_id)
                                        ->where('user_id', $user->id)
                                        ->where('estado', \App\Enums\SolicitudPermisoEstado::APROBADO)
                                        ->where('tipo_accion', \App\Enums\SolicitudPermisoTipoAccion::SUBIR_PDF_ANEXOS)
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



            ], position: RecordActionsPosition::BeforeCells)
            ->modifyQueryUsing(fn($query) => $query->where('cin_filaeliminada', false))
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
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
