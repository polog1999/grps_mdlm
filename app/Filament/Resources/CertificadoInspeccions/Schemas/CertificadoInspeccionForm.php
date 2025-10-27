<?php

namespace App\Filament\Resources\CertificadoInspeccions\Schemas;

use App\Services\LicenciaService;
use App\Services\PersonaSolicitante;
use App\Services\TipoEdificacionService;

use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\PersonaSolicitanteController;
use App\Http\Controllers\TipoEdificacionController;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Filament\Support\Enums\IconPosition;
use Carbon\Carbon;

class CertificadoInspeccionForm
{
    // Constantes de configuración
    private const YEARS_VIGENCIA = 2;
    private const DEPARTAMENTO_DEFAULT = 'Lima';
    private const PROVINCIA_DEFAULT = 'Lima';
    private const DISTRITO_DEFAULT = 'La Molina';
    private const SIGLA_RESOLUCION = '-MDLM-GDEIP-SPEA';

    /**
     * Genera el esquema completo del formulario
     */
    public static function make(): array
    {
        return [
            self::seccionInformacionGeneral(),
            self::seccionDatosEstablecimiento(),
            self::seccionDimensiones(),
            self::seccionVigencia(),
            self::seccionResolucion(),
            self::seccionLicencia(),
            self::seccionInformacionAdicional(),
            self::seccionSistema(),
        ];
    }

    /**
     * Configura el esquema del formulario (método requerido por Filament Resource)
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(self::make());
    }

    /**
     * Sección: Información General (Número y Año)
     */
    private static function seccionInformacionGeneral(): Section
    {
        return Section::make('Información General')
            ->description('Datos identificativos del certificado')
            ->icon('heroicon-o-identification')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('cin_numero')
                            ->label('Número de Certificado')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->placeholder('001')
                            ->prefix('#')
                            ->helperText('Número correlativo del certificado'),

                        TextInput::make('cin_anio')
                            ->label('Año')
                            ->numeric()
                            ->default(now()->year)
                            ->required()
                            ->minValue(2000)
                            ->maxValue(2100)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Año actual del certificado'),
                    ]),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Sección: Datos del Establecimiento
     */
    private static function seccionDatosEstablecimiento(): Section
    {
        return Section::make('Datos del Establecimiento')
            ->description('Información del local o establecimiento a certificar')
            ->icon('heroicon-o-building-storefront')
            ->schema([
                Select::make('tie_id')
                    ->label('Tipo de Edificación')
                    ->options(fn() => self::obtenerTiposEdificacion())
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->placeholder('Seleccione el tipo de edificación')
                    ->helperText('Clasificación según uso del inmueble'),

                Grid::make(3)
                    ->schema([
                        TextInput::make('cin_establecimiento')
                            ->label('Nombre del Establecimiento')
                            ->placeholder('Ej. Empresa XYZ S.A.C.')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2)
                            ->suffixIcon('heroicon-o-building-office-2')
                            ->suffixAction(
                                self::accionBuscarLicencia()
                            ),

                        TextInput::make('lic_id')
                            ->label('ID Licencia')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->placeholder('Auto')
                            ->helperText('Se completa automáticamente'),
                    ]),

                TextInput::make('cin_ubicacion')
                    ->label('Dirección Completa')
                    ->placeholder('Av. Principal 123, Urb. Los Jardines')
                    ->maxLength(255)
                    ->required()
                    ->suffixIcon('heroicon-o-map-pin')
                    ->columnSpanFull(),

                Grid::make(3)
                    ->schema([
                        TextInput::make('cin_departamento')
                            ->label('Departamento')
                            ->default(self::DEPARTAMENTO_DEFAULT)
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('cin_provincia')
                            ->label('Provincia')
                            ->default(self::PROVINCIA_DEFAULT)
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('cin_distrito')
                            ->label('Distrito')
                            ->default(self::DISTRITO_DEFAULT)
                            ->disabled()
                            ->dehydrated(),
                    ]),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Sección: Dimensiones (Área y Capacidad)
     */
    private static function seccionDimensiones(): Section
    {
        return Section::make('Dimensiones')
            ->description('Características físicas del establecimiento')
            ->icon('heroicon-o-square-3-stack-3d')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('cin_area')
                            ->label('Área Total')
                            ->numeric()
                            ->minValue(0.01)
                            ->step(0.01)
                            ->suffix('m²')
                            ->placeholder('150.50')
                            ->required()
                            ->helperText('Área en metros cuadrados'),

                        TextInput::make('cin_capacidad')
                            ->label('Capacidad de Aforo')
                            ->numeric()
                            ->minValue(1)
                            ->integer()
                            ->placeholder('50')
                            ->required()
                            ->suffix('personas')
                            ->helperText('Número máximo de ocupantes'),
                    ]),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Sección: Vigencia del Certificado
     */
    private static function seccionVigencia(): Section
    {
        return Section::make('Vigencia del Certificado')
            ->description('Período de validez del certificado de inspección')
            ->icon('heroicon-o-calendar-days')
            ->schema([
                DatePicker::make('cin_fecha')
                    ->label('Fecha de Expedición')
                    ->required()
                    ->default(today())
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now()->addDays(30))
                    ->suffixIcon('heroicon-o-calendar')
                    ->helperText('Fecha de emisión del certificado'),

                Toggle::make('cin_indeterminado')
                    ->label('Vigencia Indeterminada')
                    ->live()
                    ->default(true)
                    ->inline(false)
                    ->helperText('Activar si el certificado no tiene fecha de vencimiento')
                    ->columnSpanFull(),

                Grid::make(2)
                    ->schema([
                        DatePicker::make('cin_fec_inicio')
                            ->label('Fecha de Inicio de Vigencia')
                            ->live(onBlur: true)
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->hidden(fn(callable $get) => $get('cin_indeterminado'))
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $fechaFin = Carbon::parse($state)
                                        ->addYears(self::YEARS_VIGENCIA)
                                        ->toDateString();
                                    $set('cin_fec_fin', $fechaFin);
                                }
                            })
                            ->suffixIcon('heroicon-o-play')
                            ->helperText('Inicio del período de validez'),

                        DatePicker::make('cin_fec_fin')
                            ->label('Fecha de Fin de Vigencia')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->hidden(fn(callable $get) => $get('cin_indeterminado'))
                            ->disabled()
                            ->dehydrated()
                            ->suffixIcon('heroicon-o-stop')
                            ->helperText('Se calcula automáticamente (+2 años)'),
                    ])
                    ->hidden(fn(callable $get) => $get('cin_indeterminado')),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Sección: Resolución
     */
    private static function seccionResolucion(): Section
    {
        return Section::make('Resolución Municipal')
            ->description('Datos de la resolución que respalda el certificado')
            ->icon('heroicon-o-document-text')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('cin_resolucion')
                            ->label('Número de Resolución')
                            ->placeholder('1942-2025')
                            ->required()
                            ->maxLength(10)
                            ->helperText('Formato: #####-YYYY (1 a 5 dígitos, guion, año)')
                            ->rule('regex:/^\d{1,5}-\d{4}$/')
                            ->validationMessages([
                                'regex' => 'El formato debe ser: números-año (Ej: 1942-2025)',
                            ]),

                        TextInput::make('cin_resolucion_sigla')
                            ->label('Sigla de la Entidad')
                            ->default(self::SIGLA_RESOLUCION)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Identificador de la municipalidad'),
                    ]),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Sección: Información de Licencia
     */
    private static function seccionLicencia(): Section
    {
        return Section::make('Información de Licencia')
            ->description('Datos relacionados con la licencia de funcionamiento')
            ->icon('heroicon-o-document-check')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('cin_expediente')
                            ->label('Número de Expediente')
                            ->placeholder('2025-001234')
                            ->suffixIcon('heroicon-o-folder-open')
                            ->helperText('Expediente administrativo asociado'),

                        TextInput::make('cin_licencia')
                            ->label('Número de Licencia')
                            ->disabled()
                            ->dehydrated()
                            ->placeholder('Se completa automáticamente')
                            ->suffixIcon('heroicon-o-clipboard-document-check')
                            ->helperText('Licencia de funcionamiento'),
                    ]),

                TextInput::make('cin_procedimiento')
                    ->label('Tipo de Procedimiento')
                    ->placeholder('Ej. Evaluación previa con inspección técnica')
                    ->maxLength(255)
                    ->suffixIcon('heroicon-o-cog-6-tooth')
                    ->helperText('Procedimiento administrativo aplicado'),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Sección: Información Adicional
     */
    private static function seccionInformacionAdicional(): Section
    {
        return Section::make('Información Adicional')
            ->description('Detalles complementarios del certificado')
            ->icon('heroicon-o-information-circle')
            ->schema([
                TextInput::make('cin_giro')
                    ->label('Giro o Actividad Económica')
                    ->disabled()
                    ->dehydrated()
                    ->placeholder('Se completa automáticamente')
                    ->suffixIcon('heroicon-o-briefcase')
                    ->helperText('Actividad principal del establecimiento')
                    ->columnSpanFull(),

                TextInput::make('cin_solicitante')
                    ->label('Nombre del Solicitante')
                    ->placeholder('Nombres y apellidos del titular')
                    ->maxLength(255)
                    ->suffixIcon('heroicon-o-user')
                    ->helperText('Persona que solicita el certificado')
                    ->columnSpanFull(),

                Textarea::make('cin_nota')
                    ->label('Observaciones')
                    ->placeholder('Ingrese notas adicionales o comentarios relevantes...')
                    ->rows(4)
                    ->maxLength(1000)
                    ->helperText('Información adicional sobre el certificado (máx. 1000 caracteres)')
                    ->columnSpanFull(),
                Toggle::make('cin_consello')
                    ->label('¿Tiene Sello?')
                    ->default(false)
                    ->inline(false),
            ])
            ->collapsible()
            //>collapsed()
            ->columnSpan('full');
    }

    /**
     * Sección: Campos de Sistema 
     */
    private static function seccionSistema(): Fieldset
    {
        return Fieldset::make('Campos del Sistema')
            ->schema([
                Grid::make(3)
                    ->schema([
                        DateTimePicker::make('cin_filafecha')
                            ->label('Fecha de Registro')
                            ->default(now())
                            ->disabled()
                            ->dehydrated()
                            ->native(false)
                            ->seconds(true)
                            ->displayFormat('d/m/Y H:i:s')
                            ->helperText('Generado automáticamente al crear el registro'),

                        TextInput::make('usa_id')
                            ->label('Usuario ID')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('ID del usuario del sistema'),

             
                    ]),

                Grid::make(2)
                    ->schema([
                        Toggle::make('cin_filaoriginal')
                            ->label('Registro Original')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Marca si es un registro original'),

                        Toggle::make('cin_filaeliminada')
                            ->label('Registro Eliminado')
                            ->default(false)
                            ->inline(false)
                            ->helperText('Marca si el registro está inactivo'),
                    ]),
            ])
            ->hidden(true)
            ->columnSpan('full');
            //->collapsed();
    }

    /**
     * Acción: Modal de búsqueda de licencia
     */
    private static function accionBuscarLicencia(): Action
    {
        return Action::make('buscar_licencia')
            ->label('Buscar')
            ->icon('heroicon-o-magnifying-glass')
            ->iconPosition(IconPosition::Before)
            ->color('primary')
            ->modalHeading('Búsqueda de Licencia')
            ->modalDescription('Puede buscar por número de expediente, número de licencia o ambos para mayor precisión')
            ->modalIcon('heroicon-o-magnifying-glass-circle')
            ->modalWidth(Width::Large)
            ->modalSubmitActionLabel('Buscar Licencia')
            ->modalCancelActionLabel('Cancelar')
            ->fillForm(function (callable $get): array {
                // Prellenar los campos del modal con los valores actuales del formulario
                return [
                    'search_expediente' => $get('cin_expediente'),
                    'search_licencia' => $get('cin_licencia'),
                ];
            })
            ->form([
                Grid::make(2)
                    ->schema([
                        TextInput::make('search_expediente')
                            ->label('Número de Expediente')
                            ->placeholder('Ej: 2025-001234')
                            ->suffixIcon('heroicon-o-folder-open')
                            ->helperText('Ingrese el expediente administrativo'),

                        TextInput::make('search_licencia')
                            ->label('Número de Licencia')
                            ->placeholder('Ej: 2024-12345')
                            ->suffixIcon('heroicon-o-document-check')
                            ->helperText('Ingrese el número de licencia'),
                    ]),
            ])
            ->action(fn(array $data, callable $set) => self::manejarBusquedaLicencia($data, $set));
    }

    /**
     * Maneja la búsqueda de licencias
     */
    public static function manejarBusquedaLicencia(array $data, callable $set): void
    {
        $expediente = trim($data['search_expediente'] ?? '');
        $licencia = trim($data['search_licencia'] ?? '');

        // Validación de campos
        if (empty($expediente) && empty($licencia)) {
            Notification::make()
                ->title('Campos requeridos')
                ->body('Debe ingresar al menos un número de expediente o licencia para realizar la búsqueda.')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        try {
            // Determinar tipo de búsqueda
            $tipoBusqueda = self::determinarTipoBusqueda($expediente, $licencia);
            
            logger()->info('CertificadoInspeccionForm: Iniciando búsqueda', [
                'tipo' => $tipoBusqueda,
                'expediente' => $expediente,
                'licencia' => $licencia,
            ]);

            // Ejecutar búsqueda
            $serviceLicencia = app(LicenciaService::class);
            $respuesta = self::ejecutarBusqueda($serviceLicencia, $expediente, $licencia);

            // Validar respuesta
            if (!is_array($respuesta) || !isset($respuesta['status'])) {
                throw new \Exception('Respuesta inválida del servicio de licencias.');
            }

            // Procesar resultado
            self::procesarResultadoBusqueda($respuesta, $set, $expediente, $licencia);

        } catch (\Throwable $e) {
            logger()->error('CertificadoInspeccionForm: Error en búsqueda', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->title('Error del sistema')
                ->body("Ha ocurrido un problema al buscar la licencia: {$e->getMessage()}")
                ->danger()
                ->persistent()
                ->send();
        }
    }

    /**
     * Determina el tipo de búsqueda según los campos completados
     */
    private static function determinarTipoBusqueda(?string $expediente, ?string $licencia): string
    {
        if (!empty($licencia) && !empty($expediente)) {
            return 'licencia y expediente';
        }
        
        return !empty($licencia) ? 'licencia' : 'expediente';
    }

    /**
     * Ejecuta la búsqueda según el tipo
     */
    private static function ejecutarBusqueda(
        LicenciaService $service,
        ?string $expediente,
        ?string $licencia
    ): array {
        if (!empty($licencia) && !empty($expediente)) {
            return $service->obtenerPorNumeroLicenciaYExpediente($licencia, $expediente);
        }
        
        if (!empty($licencia)) {
            return $service->obtenerPorNumeroLicencia($licencia);
        }
        
        return $service->obtenerPorNumeroExpediente($expediente);
    }

    /**
     * Procesa el resultado de la búsqueda
     */
    private static function procesarResultadoBusqueda(
        array $respuesta,
        callable $set,
        ?string $expediente,
        ?string $licencia
    ): void {
        switch ($respuesta['status']) {
            case 'ok':
                self::procesarLicenciaEncontrada($respuesta['data'], $set, $expediente, $licencia);
                break;

            case 'duplicado':
                Notification::make()
                    ->title('Registros Duplicados')
                    ->body('Se encontraron múltiples registros. Para mayor precisión, intente buscar proporcionando ambos campos (expediente y licencia).')
                    ->warning()
                    ->duration(8000)
                    ->send();
                break;

            case 'no_encontrado':
                $tipo = self::determinarTipoBusqueda($expediente, $licencia);
                Notification::make()
                    ->title('No Encontrado')
                    ->body("No se encontró ninguna licencia con el {$tipo} proporcionado.")
                    ->warning()
                    ->duration(5000)
                    ->send();
                break;

            default:
                throw new \Exception('Estado desconocido en la respuesta del servicio.');
        }
    }

    /**
     * Procesa los datos de una licencia encontrada
     */
    private static function procesarLicenciaEncontrada(
        $licenciaData,
        callable $set,
        ?string $expediente,
        ?string $licencia
    ): void {
        if (!$licenciaData) {
            throw new \Exception('Los datos de la licencia están vacíos.');
        }

        // Obtener datos del solicitante
        $nombreSolicitante = self::obtenerNombreSolicitante($licenciaData->per_idsolicitante ?? null);

        // Setear valores en el formulario
        self::setearDatosLicencia($set, $licenciaData, $nombreSolicitante, $expediente, $licencia);

        // Mostrar notificación de éxito
        Notification::make()
            ->title('✅ Licencia Encontrada')
            ->body(self::generarMensajeExito($licenciaData))
            ->success()
            ->duration(10000)
            ->send();
    }

    /**
     * Obtiene el nombre del solicitante
     */
    private static function obtenerNombreSolicitante(?int $idSolicitante): string
    {
        if (empty($idSolicitante)) {
            return 'No disponible';
        }

        try {
            $servicePersona = app(PersonaSolicitante::class);
            $respuesta = $servicePersona->obtenerPorIdSolicitante($idSolicitante);
            
            if (isset($respuesta['status']) && $respuesta['status'] === 'ok') {
                return $respuesta['data']->personasolicitante ?? 'No disponible';
            }
        } catch (\Throwable $e) {
            logger()->warning('Error al obtener solicitante', [
                'id_solicitante' => $idSolicitante,
                'error' => $e->getMessage(),
            ]);
        }

        return 'No disponible';
    }

    /**
     * Setea los datos de la licencia en el formulario
     */
    private static function setearDatosLicencia(
        callable $set,
        $licenciaData,
        string $nombreSolicitante,
        ?string $expediente,
        ?string $licencia
    ): void {
        // Datos principales
        $set('lic_id', $licenciaData->lic_id ?? null);
        $set('cin_licencia', $licenciaData->lic_numlic ?? null);
        $set('cin_giro', $licenciaData->lic_giro ?? null);
        $set('cin_area', $licenciaData->lic_area ?? null);
        $set('cin_ubicacion', $licenciaData->lic_direccion ?? null);
        $set('cin_establecimiento', $licenciaData->lic_razonsocial ?? null);
        $set('cin_expediente', $licenciaData->lic_expnum ?? $expediente);
        $set('cin_solicitante', $nombreSolicitante);

        // Fechas de vigencia
        if (!empty($licenciaData->lic_fechaemision)) {
            $set('cin_indeterminado', false);
            $set('cin_fec_inicio', $licenciaData->lic_fechaemision);
            
            $fechaFin = Carbon::parse($licenciaData->lic_fechaemision)
                ->addYears(self::YEARS_VIGENCIA)
                ->toDateString();
            $set('cin_fec_fin', $fechaFin);
        }
    }

    /**
     * Genera el mensaje de éxito con los datos de la licencia
     */
    private static function generarMensajeExito($licenciaData): string
    {
        $detalles = [
            '📋 Licencia N°' => $licenciaData->lic_numlic ?? 'N/A',
            '🏢 Establecimiento' => $licenciaData->lic_razonsocial ?? 'N/A',
            '💼 Giro' => $licenciaData->lic_giro ?? 'N/A',
            '📐 Área' => isset($licenciaData->lic_area) ? "{$licenciaData->lic_area} m²" : 'N/A',
            '📅 Fecha de Emisión' => $licenciaData->lic_fechaemision ?? 'N/A',
            '📅 Fecha de Vencimiento' => $licenciaData->lic_fechavencimiento ?? 'N/A',
            '📍 Ubicación' => $licenciaData->lic_direccion ?? 'N/A',
            '🗂️ Expediente N°' => $licenciaData->lic_expnum ?? 'N/A',
            '👤 Solicitante' => $licenciaData->personasolicitante ?? 'N/A',
            ];

        $mensaje = "Se completaron automáticamente los siguientes datos:". PHP_EOL . PHP_EOL;
        foreach ($detalles as $label => $valor) {
            $mensaje .= "{$label}: {$valor}". PHP_EOL;
        }

        return trim($mensaje);
    }

    /**
     * Obtiene los tipos de edificación del servicio y tambien los que en tie_activo sean tru
     */
    private static function obtenerTiposEdificacion(): array
    {
        try {
            $service = new TipoEdificacionService();
            $data = $service->getTipoEdificacionesActivos();

            return collect($data)->pluck('tie_descripcion', 'tie_id')->toArray();
        } catch (\Throwable $e) {
            logger()->error('Error al cargar tipos de edificación', [
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }
}