<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas;

use App\Services\Sil\CertificadoInspeccion\LicenciaService;
use App\Services\Sil\CertificadoInspeccion\PersonaSolicitante;
use App\Services\Sil\CertificadoInspeccion\TipoEdificacionService;
use App\Services\Sil\CertificadoInspeccion\CertificadoInspeccionService;
use App\Services\Sil\CertificadoInspeccion\ResolucionService;
use App\Models\NivelRiesgo;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\Steps\BusquedaStep;
use App\Filament\Clusters\Sil\Resources\CertificadoInspeccionResource\Schemas\Steps\DatosCompletosStep;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
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
use Filament\Tables\Grouping\Group;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\HtmlString;


/**
 * Esquema del formulario para Certificado de Inspección.
 *
 * Esta clase construye y devuelve el esquema de componentes utilizado por la
 * Resource de Filament para crear/editar registros de certificados de inspección.
 *
 * Proporciona métodos estáticos para generar secciones del formulario, manejar
 * la búsqueda y autocompletado de licencias y mapear datos provenientes de servicios.
 *
 * Nota: Todos los métodos son estáticos para facilitar su uso desde la definición
 * del Schema en la Resource de Filament.
 */
class CertificadoInspeccionForm
{
    // Constantes de configuración
    private const YEARS_VIGENCIA = 2;
    private const DEPARTAMENTO_DEFAULT = 'Lima';
    private const PROVINCIA_DEFAULT = 'Lima';
    private const DISTRITO_DEFAULT = 'La Molina';
    private const SIGLA_RESOLUCION = '-MDLM-GDEIP-SGRD';

    /**
     * Retorna el estilo CSS para campos autocompletados
     * 
     * @param callable $get Función get de Filament para obtener valores del formulario
     * @param string $autofilledField Nombre del campo que indica si está autocompletado
     * @return string Estilo CSS o cadena vacía
     */
    private static function getAutofilledStyle(callable $get, string $autofilledField): string
    {
        return $get($autofilledField)
            ? 'background-color: #373b35ff !important; color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;'
            : '';
    }

    /**
     * Campos ocultos del sistema que se incluyen en el formulario pero no son visibles.
     *
     * @return array Lista de componentes Hidden con valores por defecto.
     */
    public static function camposOcultosSistema(): array
    {
        return [
            Hidden::make('cin_filafecha')
                ->default(now()),

            Hidden::make('usa_id')
                ->default(0),

            Hidden::make('cin_filaoriginal')
                ->default(true),

            Hidden::make('cin_filaeliminada')
                ->default(false),

            Hidden::make('cin_procedimiento')
                ->default(''),

            //campos ocultos de autofil
            Hidden::make('cin_establecimiento_autofilled')->default(false),
            Hidden::make('cin_ubicacion_autofilled')->default(false),
            Hidden::make('cin_area_autofilled')->default(false),
            Hidden::make('cin_fecha_inicio_autofilled')->default(false),
            Hidden::make('cin_fecha_fin_autofilled')->default(false),
            Hidden::make('cin_giro_autofilled')->default(false),
            Hidden::make('cin_licencia_autofilled')->default(false),
            Hidden::make('cin_expediente_autofilled')->default(false),
            Hidden::make('cin_solicitante_autofilled')->default(false),
            Hidden::make('cin_resolucion_autofilled')->default(false),
            Hidden::make('tie_id_autofilled')->default(false),
            Hidden::make('cin_es_temporal')->default(false),

        ];
    }

    /**
     * Genera el esquema completo del formulario
     */
    public static function make(): array
    {
        return [
            Wizard::make([
                BusquedaStep::make()
                    ->hidden(fn(string $operation) => $operation === 'edit'),
                DatosCompletosStep::make(),
            ])->columnSpanFull(),
        ];
    }

    /**
     * Crea la acción que muestra el modal de búsqueda/autocompletado de licencia.
     *
     * El modal permite buscar por número de expediente o número de licencia y
     * completar automáticamente los campos del formulario cuando se encuentra
     * una licencia válida.
     *
     * @return Action Acción de Filament configurada.
     */



    /**
     * Configura el Schema de Filament con los componentes generados por esta clase.
     *
     * @param Schema $schema Instancia de Schema a configurar.
     * @return Schema Schema con componentes añadidos.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(self::make());
    }

    /**
     * Genera la sección "Información General" que incluye número y año del certificado.
     *
     * @return Section Componente Section con campos de número y año.
     */
    public static function seccionInformacionGeneral(): Section
    {
        // Obtener el siguiente número de certificado disponible para mostrarlo en el label
        $siguiente = null;
        try {
            $siguiente = app(CertificadoInspeccionService::class)->obtenerSiguienteNumero();
        } catch (\Throwable $e) {
            logger()->warning('No se pudo obtener siguiente numero de certificado', ['error' => $e->getMessage()]);
        }

        return Section::make('Información General')
            ->description('Datos identificativos del certificado')
            ->icon('heroicon-o-identification')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('cin_numero')
                            ->label(
                                'Número de Certificado'
                            )
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->minValue(1)
                            ->default($siguiente)
                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_numero') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_numero'),
                            ])
                            ->prefix('#')
                            ->helperText('Número correlativo del certificado')
                            ->dehydrated(),
                        TextInput::make('cin_anio')
                            ->label('Año')
                            ->numeric()
                            ->default(now()->year)
                            ->required()
                            ->minValue(2000)
                            ->maxValue(2100)
                            ->disabled()
                            ->dehydrated()
                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_anio') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_anio'),
                            ])
                            ->helperText('Año actual del certificado'),
                    ]),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Genera la sección de datos del establecimiento (tipo, nombre, ubicación, distrito).
     *
     * @return Section Sección con campos del establecimiento.
     */
    public static function seccionDatosEstablecimiento(): Section
    {
        return Section::make('Datos del Establecimiento')
            ->description('Información del local o establecimiento a certificar')
            ->icon('heroicon-o-building-storefront')
            ->schema([


                Grid::make(2)
                    ->schema([
                        Select::make('tie_id')
                            ->label(
                                fn(callable $get) => $get('nir_id')
                                ? 'Tipo de Edificación (Nivel de Riesgo: ' . $get('nir_id') . ' - ' . $get('nir_descripcion') . ')'
                                : 'Tipo de Edificación'
                            )
                            ->options(fn() => self::obtenerTiposEdificacion())
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Seleccione el tipo de edificación')
                            ->disabled(fn(callable $get) => (bool) $get('tie_id_autofilled'))
                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('tie_id_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'tie_id_autofilled'),
                            ])
                            ->extraAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('tie_id_autofilled') ? '1' : '0',
                            ])
                            ->helperText(
                                fn(callable $get) =>
                                $get('tie_id_autofilled')
                                ? '✓ Autocompletado'
                                : 'Clasificación según uso del inmueble'
                            ),
                        TextInput::make('cin_establecimiento')
                            ->label('Nombre del Establecimiento')
                            ->placeholder('Ej. Empresa XYZ S.A.C.')
                            ->required()
                            ->maxLength(255)
                            ->disabled(fn(callable $get) => (bool) $get('cin_establecimiento_autofilled'))
                            ->dehydrated()
                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_establecimiento_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_establecimiento_autofilled'),
                            ])
                            ->extraAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_establecimiento_autofilled') ? '1' : '0',
                            ])
                            ->helperText(
                                fn(callable $get) =>
                                $get('cin_establecimiento_autofilled')
                                ? '✓ Autocompletado'
                                : 'Ingrese manualmente'
                            ),
                        Hidden::make('lic_id')
                            ->dehydrated(),
                        Hidden::make('nir_id')
                            ->live()
                            ->dehydrated(),
                        Hidden::make('nir_descripcion')
                            ->live()
                            ->dehydrated(),

                    ]),

                TextInput::make('cin_ubicacion')
                    ->label('Dirección Completa')
                    ->placeholder('Av. Principal 123, Urb. Los Jardines')
                    ->maxLength(255)
                    ->required()
                    ->suffixIcon('heroicon-o-map-pin')
                    ->columnSpanFull()
                    ->dehydrated()

                    ->disabled(fn(callable $get) => (bool) $get('cin_ubicacion_autofilled'))
                    ->dehydrated()

                    ->extraInputAttributes(fn(callable $get) => [
                        'data-autofilled' => $get('cin_ubicacion_autofilled') ? '1' : '0',
                        'style' => self::getAutofilledStyle($get, 'cin_ubicacion_autofilled'),
                    ])
                    ->extraAttributes(fn(callable $get) => [
                        'data-autofilled' => $get('cin_ubicacion_autofilled') ? '1' : '0',
                    ])
                    ->helperText(
                        fn(callable $get) =>
                        $get('cin_ubicacion_autofilled')
                        ? '✓ Autocompletado'
                        : 'Ingrese manualmente'
                    ),
                Grid::make(3)
                    ->schema([
                        TextInput::make('cin_departamento')
                            ->label('Departamento')
                            ->default(self::DEPARTAMENTO_DEFAULT)
                            ->disabled()

                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_departamento') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_ubicacion_autofilled'),

                            ])
                            ->dehydrated(),

                        TextInput::make('cin_provincia')
                            ->label('Provincia')
                            ->default(self::PROVINCIA_DEFAULT)
                            ->disabled()
                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_giro_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_giro_autofilled'),
                            ])
                            ->dehydrated(),

                        TextInput::make('cin_distrito')
                            ->label('Distrito')
                            ->default(self::DISTRITO_DEFAULT)
                            ->disabled()
                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_distrito') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_distrito'),
                            ])
                            ->dehydrated(),
                    ]),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Genera la sección de dimensiones que contiene área y capacidad de aforo.
     *
     * @return Section Sección con campos numéricos para área y capacidad.
     */
    public static function seccionDimensiones(): Section
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
                            ->disabled(fn(callable $get) => (bool) $get('cin_area_autofilled'))
                            ->dehydrated()

                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_area_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_area_autofilled'),
                            ])
                            ->extraAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_area_autofilled') ? '1' : '0',
                            ])
                            ->helperText(
                                fn(callable $get) =>
                                $get('cin_area_autofilled')
                                ? '✓ Autocompletado'
                                : 'Ingrese manualmente'
                            ),

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
     * Genera la sección de vigencia del certificado.
     *
     * Incluye lógica para calcular la fecha de fin en función de la fecha de inicio
     * y una vigencia por defecto (constante YEARS_VIGENCIA).
     *
     * @return Section Sección con DatePickers y toggles relacionados.
     */
    public static function seccionVigencia(): Section
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
                            ->disabled(fn(callable $get) => (bool) $get('cin_fecha_inicio_autofilled') && !$get('cin_es_temporal'))
                            ->dehydrated()

                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_fecha_inicio_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_fecha_inicio_autofilled'),
                            ])
                            ->extraAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_fecha_inicio_autofilled') ? '1' : '0',
                            ])
                            ->helperText(
                                fn(callable $get) =>
                                $get('cin_fecha_inicio_autofilled')
                                ? '✓ Autocompletado'
                                : 'Ingrese manualmente'
                            ),

                        DatePicker::make('cin_fec_fin')
                            ->label('Fecha de Fin de Vigencia')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->hidden(fn(callable $get) => $get('cin_indeterminado'))
                            ->dehydrated()
                            ->suffixIcon('heroicon-o-stop')
                            ->disabled(fn(callable $get) => (bool) $get('cin_fecha_fin_autofilled') && !$get('cin_es_temporal'))
                            ->dehydrated()

                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_fecha_fin_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_fecha_fin_autofilled'),
                            ])
                            ->extraAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_fecha_fin_autofilled') ? '1' : '0',
                            ])
                            ->helperText(
                                fn(callable $get) =>
                                $get('cin_fecha_fin_autofilled')
                                ? '✓ Autocompletado'
                                : 'Ingrese manualmente'
                            ),
                    ])
                    ->hidden(fn(callable $get) => $get('cin_indeterminado')),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Genera la sección con datos de la resolución municipal.
     *
     * Valida el formato del número de resolución mediante una regla regex.
     *
     * @return Section Sección con campos de resolución y sigla.
     */
    public static function seccionResolucion(): Section
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
                            ->helperText(
                                fn(callable $get) =>
                                $get('cin_resolucion_autofilled')
                                ? '✓ Autocompletado'
                                : 'Formato: #####-YYYY (1 a 5 dígitos, guion, año)'
                            )
                            ->rule('regex:/^\d{1,5}-\d{4}$/')
                            ->validationMessages([
                                'regex' => 'El formato debe ser: números-año (Ej: 1942-2025)',
                            ])
                            ->disabled(fn(callable $get) => (bool) $get('cin_resolucion_autofilled'))
                            ->dehydrated()
                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_resolucion_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_resolucion_autofilled'),
                            ]),

                        TextInput::make('cin_resolucion_sigla')
                            ->label('Sigla de la Entidad')
                            ->default(self::SIGLA_RESOLUCION)
                            ->disabled()
                            ->dehydrated()
                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_solicitante_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_solicitante_autofilled'),
                            ])
                            ->helperText('Identificador de la municipalidad'),
                    ]),
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Genera la sección de información de licencia (expediente y número de licencia).
     *
     * @return Section Sección con campos relacionados a la licencia.
     */
    public static function seccionLicencia(): Section
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
                            ->disabled(fn(callable $get) => (bool) $get('cin_expediente_autofilled'))
                            ->dehydrated()

                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_expediente_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_expediente_autofilled'),
                            ])
                            ->extraAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_expediente_autofilled') ? '1' : '0',
                            ])
                            ->helperText(
                                fn(callable $get) =>
                                $get('cin_expediente_autofilled')
                                ? '✓ Autocompletado'
                                : 'Ingrese manualmente'
                            ),

                        TextInput::make('cin_licencia')
                            ->label('Número de Licencia')
                            ->dehydrated()
                            ->placeholder('Se completa automáticamente')
                            ->suffixIcon('heroicon-o-clipboard-document-check')
                            ->disabled(fn(callable $get) => (bool) $get('cin_licencia_autofilled'))
                            ->dehydrated()

                            ->extraInputAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_licencia_autofilled') ? '1' : '0',
                                'style' => self::getAutofilledStyle($get, 'cin_licencia_autofilled'),
                            ])
                            ->extraAttributes(fn(callable $get) => [
                                'data-autofilled' => $get('cin_licencia_autofilled') ? '1' : '0',
                            ])
                            ->helperText(
                                fn(callable $get) =>
                                $get('cin_licencia_autofilled')
                                ? '✓ Autocompletado'
                                : 'Ingrese manualmente'
                            )
                    ]),
                /*
                TextInput::make('cin_procedimiento')
                    ->label('Tipo de Procedimiento')
                    ->placeholder('Ej. Evaluación previa con inspección técnica')
                    ->maxLength(255)
                    ->suffixIcon('heroicon-o-cog-6-tooth')
                    ->helperText('Procedimiento administrativo aplicado')
                    //->visible(false
                */
            ])
            ->collapsible()
            ->columnSpan('full');
    }

    /**
     * Genera la sección de información adicional (giro, solicitante, notas, sello).
     *
     * @return Section Sección con campos de texto y toggles complementarios.
     */
    public static function seccionInformacionAdicional(): Section
    {
        return Section::make('Información Adicional')
            ->description('Detalles complementarios del certificado')
            ->icon('heroicon-o-information-circle')
            ->schema([
                TextInput::make('cin_giro')
                    ->label('Giro o Actividad Económica')
                    ->dehydrated()
                    ->placeholder('Se completa automáticamente')
                    ->suffixIcon('heroicon-o-briefcase')
                    ->disabled(fn(callable $get) => (bool) $get('cin_giro_autofilled'))
                    ->dehydrated()

                    ->extraInputAttributes(fn(callable $get) => [
                        'data-autofilled' => $get('cin_giro_autofilled') ? '1' : '0',
                        'style' => self::getAutofilledStyle($get, 'cin_giro_autofilled'),
                    ])
                    ->extraAttributes(fn(callable $get) => [
                        'data-autofilled' => $get('cin_giro_autofilled') ? '1' : '0',
                    ])
                    ->helperText(
                        fn(callable $get) =>
                        $get('cin_giro_autofilled')
                        ? '✓ Autocompletado'
                        : 'Ingrese manualmente'
                    )
                    ->columnSpanFull(),

                TextInput::make('cin_solicitante')
                    ->label('Nombre del Solicitante')
                    ->placeholder('Nombres y apellidos del titular')
                    ->maxLength(255)
                    ->suffixIcon('heroicon-o-user')
                    ->disabled(fn(callable $get) => (bool) $get('cin_solicitante_autofilled'))
                    ->dehydrated()

                    ->extraInputAttributes(fn(callable $get) => [
                        'data-autofilled' => $get('cin_solicitante_autofilled') ? '1' : '0',
                        'style' => self::getAutofilledStyle($get, 'cin_licencia_autofilled'),
                    ])
                    ->extraAttributes(fn(callable $get) => [
                        'data-autofilled' => $get('cin_solicitante_autofilled') ? '1' : '0',
                    ])
                    ->helperText(
                        fn(callable $get) =>
                        $get('cin_solicitante_autofilled')
                        ? '✓ Autocompletado'
                        : 'Ingrese manualmente'
                    )
                    ->columnSpanFull(),

                Textarea::make('cin_nota')
                    ->label('Observaciones')
                    ->placeholder('Ingrese notas adicionales o comentarios relevantes...')
                    ->rows(4)
                    ->maxLength(1000)
                    ->helperText('Información adicional sobre el certificado (máx. 1000 caracteres)')
                    ->columnSpanFull(),

                /*    Toggle::make('cin_consello')
                    ->label('¿Tiene Sello?')
                    ->default(false)
                    ->inline(false),*/
            ])
            ->collapsible()
            //>collapsed()
            ->columnSpan('full');
    }


    /*
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
                                ->dehydrated()
                                ->helperText('Marca si es un registro original'),

                            Toggle::make('cin_filaeliminada')
                                ->label('Registro Eliminado')
                                ->default(false)
                                ->inline(false)
                                ->dehydrated()
                                ->helperText('Marca si el registro está inactivo'),
                        ]),
                ])
                ->hidden(true)
                ->columnSpan('full');
                //->collapsed();
        } 
    */
    /**
     * Realiza la búsqueda de licencias y completa el formulario con los datos encontrados.
     *
     * Este método valida los input del modal, determina el tipo de búsqueda, llama al
     * servicio de licencias y procesa el resultado para setear los campos del formulario.
     *
     * @param array $data Datos recibidos desde el modal (search_expediente, search_licencia).
     * @param callable $set Callback para asignar valores en el formulario Livewire.
     * @return void
     */
    public static function manejarBusquedaLicencia(array $data, callable $set): void
    {
        $expediente = trim($data['search_expediente'] ?? '');
        $licencia = trim($data['search_licencia'] ?? '');
        $resolucion = trim($data['search_resolucion'] ?? '');

        // Validación de campos
        if (empty($expediente) && empty($licencia) && empty($resolucion)) {
            Notification::make()
                ->title('Campos requeridos')
                ->body('Debe ingresar al menos un número de expediente, licencia o resolución para realizar la búsqueda.')
                ->warning()
                ->duration(5000)
                ->send();
            return;
        }

        try {
            // Si se proporcionó resolución, buscar expediente primero
            if (!empty($resolucion)) {
                $serviceResolucion = app(ResolucionService::class);
                $resultadoResolucion = $serviceResolucion->obtenerNumeroExpedientePorNumeroResolucion($resolucion);

                if (!$resultadoResolucion || empty($resultadoResolucion->numero_expediente)) {
                    Notification::make()
                        ->title('Resolución no encontrada')
                        ->body("No se encontró ningún expediente asociado a la resolución: {$resolucion}")
                        ->warning()
                        ->duration(5000)
                        ->send();
                    return;
                }

                // Usar el expediente encontrado
                $expediente = $resultadoResolucion->numero_expediente;

                logger()->info('CertificadoInspeccionForm: Expediente obtenido desde resolución', [
                    'resolucion' => $resolucion,
                    'expediente' => $expediente,
                ]);
            }

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
            self::procesarResultadoBusqueda($respuesta, $set, $expediente, $licencia, $resolucion);

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
     * Determina si la búsqueda será por expediente, licencia o ambos.
     *
     * @param string|null $expediente Número de expediente.
     * @param string|null $licencia Número de licencia.
     * @return string Tipo de búsqueda: 'licencia', 'expediente' o 'licencia y expediente'.
     */
    private static function determinarTipoBusqueda(?string $expediente, ?string $licencia): string
    {
        if (!empty($licencia) && !empty($expediente)) {
            return 'licencia y expediente';
        }

        return !empty($licencia) ? 'licencia' : 'expediente';
    }

    /**
     * Ejecuta la consulta al servicio de licencias según los parámetros proporcionados.
     *
     * @param LicenciaService $service Servicio encargado de obtener datos de licencias.
     * @param string|null $expediente Número de expediente.
     * @param string|null $licencia Número de licencia.
     * @return array Respuesta del servicio en formato ['status' => ..., 'data' => ...].
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
     * Procesa la respuesta del servicio de licencias y ejecuta la acción
     * correspondiente en función del estado (ok, duplicado, no_encontrado).
     *
     * @param array $respuesta Respuesta devuelta por el servicio de licencias.
     * @param callable $set Callback para asignar valores en el formulario.
     * @param string|null $expediente Valor de expediente buscado.
     * @param string|null $licencia Valor de licencia buscado.
     * @param string|null $resolucion Valor de resolución buscado.
     * @return void
     */
    private static function procesarResultadoBusqueda(
        array $respuesta,
        callable $set,
        ?string $expediente,
        ?string $licencia,
        ?string $resolucion = null
    ): void {
        switch ($respuesta['status']) {
            case 'ok':
                self::procesarLicenciaEncontrada($respuesta['data'], $set, $expediente, $licencia, $resolucion);
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
     * Inserta los datos de la licencia encontrada en el formulario y muestra
     * una notificación de éxito con los detalles relevantes.
     *
     * @param mixed $licenciaData Objeto con los datos de la licencia.
     * @param callable $set Callback para setear campos del formulario.
     * @param string|null $expediente Número de expediente usado en la búsqueda.
     * @param string|null $licencia Número de licencia usado en la búsqueda.
     * @param string|null $resolucion Número de resolución usado en la búsqueda.
     * @return void
     */
    private static function procesarLicenciaEncontrada(
        $licenciaData,
        callable $set,
        ?string $expediente,
        ?string $licencia,
        ?string $resolucion = null
    ): void {
        if (!$licenciaData) {
            throw new \Exception('Los datos de la licencia están vacíos.');
        }

        // Obtener datos del solicitante
        $nombreSolicitante = self::obtenerNombreSolicitante($licenciaData->per_idsolicitante ?? null);

        // Setear valores en el formulario
        self::setearDatosLicencia($set, $licenciaData, $nombreSolicitante, $expediente, $licencia, $resolucion);

        // Mark search as completed successfully
        $set('search_completed', true);

        // Mostrar notificación de éxito
        Notification::make()
            ->title('✅ Licencia Encontrada')
            ->body(self::generarMensajeExito($licenciaData))
            ->success()
            ->duration(10000)
            ->send();
    }

    /**
     * Obtiene el nombre del solicitante a partir de su ID utilizando el servicio.
     *
     * @param int|null $idSolicitante ID del solicitante.
     * @return string Nombre del solicitante o 'No disponible' si no se encuentra.
     */
    private static function obtenerNombreSolicitante(?int $idSolicitante): string
    {
        if (empty($idSolicitante)) {
            return 'No disponible';
        }

        try {
            $servicePersona = app(PersonaSolicitante::class);
            $respuesta = $servicePersona->obtenerPorIdSolicitante($idSolicitante);

            if (isset($respuesta['status']) && $respuesta['status'] === 'ok' && $respuesta['data']) {
                return $respuesta['data']->per_nombrerazonsocial ?? 'No disponible';
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
     * Asigna los valores obtenidos de la licencia a los campos del formulario.
     *
     * @param callable $set Callback para asignar valores en el formulario.
     * @param mixed $licenciaData Datos de la licencia.
     * @param string $nombreSolicitante Nombre resuelto del solicitante.
     * @param string|null $expediente Número de expediente original de búsqueda.
     * @param string|null $licencia Número de licencia original de búsqueda.
     * @param string|null $resolucion Número de resolución original de búsqueda.
     * @return void
     */
    private static function setearDatosLicencia(
        callable $set,
        $licenciaData,
        string $nombreSolicitante,
        ?string $expediente,
        ?string $licencia,
        ?string $resolucion = null
    ): void {
        // Datos principales
        $set('lic_id', $licenciaData->lic_id ?? null);
        $set('nir_id', $licenciaData->nir_id ?? null);

        // Recuperar descripción del nivel de riesgo
        if (!empty($licenciaData->nir_id)) {
            try {
                $nivelRiesgo = NivelRiesgo::where('nir_id', $licenciaData->nir_id)
                    ->where('nir_activo', true)
                    ->where('nir_filaeliminada', false)
                    ->first();

                if ($nivelRiesgo) {
                    $set('nir_descripcion', $nivelRiesgo->nir_descripcion);
                    // tie_id es igual a nir_id, autocompletar el Select
                    $set('tie_id', $licenciaData->nir_id);
                    $set('tie_id_autofilled', true);
                    logger()->info('Nivel de riesgo encontrado y tie_id asignado', [
                        'nir_id' => $licenciaData->nir_id,
                        'nir_descripcion' => $nivelRiesgo->nir_descripcion,
                        'tie_id' => $licenciaData->nir_id,
                    ]);
                } else {
                    logger()->warning('Nivel de riesgo no encontrado o inactivo', [
                        'nir_id' => $licenciaData->nir_id,
                    ]);
                }
            } catch (\Throwable $e) {
                logger()->error('Error al obtener nivel de riesgo', [
                    'nir_id' => $licenciaData->nir_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $set('cin_licencia', $licenciaData->lic_numlic ?? null);
        $set('cin_licencia_autofilled', !empty($licenciaData->lic_numlic));
        $set('cin_giro', $licenciaData->lic_giro ?? null);
        $set('cin_giro_autofilled', !empty($licenciaData->lic_giro));
        $set('cin_area', $licenciaData->lic_area ?? null);
        $set('cin_area_autofilled', !empty($licenciaData->lic_area));
        $set('cin_ubicacion', $licenciaData->lic_direccion ?? null);
        $set('cin_ubicacion_autofilled', !empty($licenciaData->lic_direccion));
        $set('cin_establecimiento', $licenciaData->lic_razonsocial ?? null);
        $set('cin_establecimiento_autofilled', !empty($licenciaData->lic_razonsocial));
        $set('cin_expediente', $licenciaData->lic_expnum ?? $expediente);
        $set('cin_expediente_autofilled', !empty($licenciaData->lic_expnum));
        $set('cin_solicitante', $nombreSolicitante);
        $set('cin_solicitante_autofilled', !empty($nombreSolicitante));

        // Fechas de vigencia
        if (!empty($licenciaData->lic_fechaemision)) {
            $set('cin_indeterminado', false);
            $set('cin_fec_inicio', $licenciaData->lic_fechaemision);
            $set('cin_fecha_inicio_autofilled', !empty($licenciaData->lic_fechaemision));

            $fechaFin = Carbon::parse($licenciaData->lic_fechaemision)
                ->addYears(self::YEARS_VIGENCIA)
                ->toDateString();
            $set('cin_fec_fin', $fechaFin);
            $set('cin_fecha_fin_autofilled', !empty($licenciaData->lic_fechaemision));
        }

        // Verificar si es licencia temporal
        $esTemporal = false;
        try {
            $serviceLicencia = app(LicenciaService::class);
            $tipoLicencia = null;

            if (!empty($licenciaData->lic_numlic)) {
                $tipoLicencia = $serviceLicencia->obtenerTipoLicenciaPorNumeroLicencia($licenciaData->lic_numlic);
            } elseif (!empty($licenciaData->lic_expnum)) {
                $tipoLicencia = $serviceLicencia->obtenerTipoLicenciaPorExpediente($licenciaData->lic_expnum);
            }

            if ($tipoLicencia && isset($tipoLicencia->tli_descripcion)) {
                // Buscar palabras clave como TEMPORAL, PROVISIONAL, etc.
                if (stripos($tipoLicencia->tli_descripcion, 'TEMPORAL') !== false) {
                    $esTemporal = true;
                }
            }
        } catch (\Throwable $e) {
            logger()->warning('Error verificando tipo de licencia temporal', ['error' => $e->getMessage()]);
        }

        $set('cin_es_temporal', $esTemporal);

        if ($esTemporal) {
            $set('cin_indeterminado', false);
            // Si es temporal, aseguramos que las fechas se puedan editar (la lógica disabled lo manejará)
            // y tal vez queramos notificar al usuario o simplemente dejarlo editar.
        }

        // Fetch and set resolution number
        if (!empty($expediente) && empty($resolucion)) {
            try {
                $serviceResolucion = app(ResolucionService::class);
                $resultadoResolucion = $serviceResolucion->obtenerNumeroResolucionPorNumeroExpediente($expediente);

                if ($resultadoResolucion && !empty($resultadoResolucion->numero_resolucion)) {
                    $set('cin_resolucion', $resultadoResolucion->numero_resolucion);
                    $set('cin_resolucion_autofilled', true);
                }
            } catch (\Throwable $e) {
                logger()->warning('No se pudo obtener resolución', [
                    'expediente' => $expediente,
                    'error' => $e->getMessage(),
                ]);
            }
        } elseif (!empty($resolucion)) {
            // If searched by resolution, use that value
            $set('cin_resolucion', $resolucion);
            $set('cin_resolucion_autofilled', true);
        }
    }

    /**
     * Construye un mensaje de texto detallado con la información relevante de la licencia
     * que se muestra al usuario cuando la búsqueda es exitosa.
     *
     * @param mixed $licenciaData Datos de la licencia.
     * @return string Mensaje de éxito formateado.
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

        $mensaje = "Se completaron automáticamente los siguientes datos:" . PHP_EOL . PHP_EOL;
        foreach ($detalles as $label => $valor) {
            $mensaje .= "{$label}: {$valor}" . PHP_EOL;
        }

        return trim($mensaje);
    }

    /**
     * Recupera la lista de tipos de edificación activos desde el servicio
     * y la transforma en un array [id => descripcion] para usar en Select.
     *
     * @return array Lista de tipos de edificación (id => descripcion).
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