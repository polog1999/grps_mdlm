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

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;




class CertificadoInspeccionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Número y Año
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('cin_numero')
                                    ->label('Número')
                                    ->type('number')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('cin_anio')
                                    ->label('Año')
                                    ->numeric()
                                    ->default(now()->year)
                                    ->required(),
                            ]),
                    ])
                    ->columnSpan('full'),


                // 2. Ubicación y Tipo de Edificación
                Section::make()
                    ->schema([
                        Select::make('tie_id')
                        ->label('Tipo de Edificación')
                        ->options(function () {
                            $serviceTipoEdificacion = new TipoEdificacionService();
                            $data = $serviceTipoEdificacion->getTipoEdificaciones();
                            return collect($data)->pluck('tie_descripcion', 'tie_id');
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->placeholder('Seleccione el tipo de edificación'),
                            
                    
                        TextInput::make('cin_establecimiento')
                            ->label('Establecimiento')
                            ->placeholder('Ej. Empresa XYZ')
                            ->suffixAction(
                                Action::make('abrirModal')
                                    ->label('Buscar')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->color('primary')
                                    ->modalHeading('Buscar Establecimiento por Expediente')
                                    ->modalWidth(Width::Large)
                                    ->form([
                                        TextInput::make('search_expediente')
                                            ->label('Nro. Expediente')
                                            ->placeholder('Ingrese el número de expediente')
                                            ->required()
                                            ->live(onBlur: true),
                                    ])
                                    ->action(function (array $data, callable $set) {
                                        $expediente = $data['search_expediente'] ?? null;

                                    try {
                                        if (empty($expediente)) {
                                            Notification::make()
                                                ->title('Campo requerido')
                                                ->body('Debe ingresar un número de expediente.')
                                                ->warning()
                                                ->send();
                                            return;
                                        }

                                        $serviceLicencia = new LicenciaService();
                                        $respuestaLicencia = $serviceLicencia->obtenerPorNumeroExpediente($expediente);

                                        if (!is_array($respuestaLicencia) || !isset($respuestaLicencia['status'])) {
                                            throw new \Exception('Respuesta inválida del servicio de licencias.');
                                        }

                                        switch ($respuestaLicencia['status']) {
                                            case 'ok':
                                                $licencia = $respuestaLicencia['data'] ?? null;

                                                if (!$licencia) {
                                                    throw new \Exception('No se encontró información de la licencia.');
                                                }

                                                // Consultar datos del solicitante solo si existe ID válido
                                                $personaSolicitante = null;
                                                if (!empty($licencia->per_idsolicitante)) {
                                                    $servicePersonaSolicitante = new PersonaSolicitante();
                                                    $respuestaPersona = $servicePersonaSolicitante->obtenerPorIdSolicitante($licencia->per_idsolicitante);
                                                    if (isset($respuestaPersona['status']) && $respuestaPersona['status'] === 'ok') {
                                                        $personaSolicitante = $respuestaPersona['data'];
                                                    }
                                                }

                                                // Seteo de valores seguros
                                                $set('lic_id', $licencia->lic_id ?? null);
                                                $set('cin_licencia', $licencia->lic_numlic ?? null);
                                                $set('cin_giro', $licencia->lic_giro ?? null);
                                                $set('cin_area', $licencia->lic_area ?? null);
                                                $set('cin_ubicacion', $licencia->lic_direccion ?? null);
                                                if (!empty($licencia->lic_fechaemision)) {
                                                    $set('cin_indeterminado', false);
                                                }
                                                $set('cin_fec_inicio', $licencia->lic_fechaemision ?? null);
                                                $set('cin_fec_fin', !empty($licencia->lic_fechaemision)
                                                    ? \Carbon\Carbon::parse($licencia->lic_fechaemision)->addYears(2)->toDateString()
                                                    : null);
                                                $set('cin_establecimiento', $licencia->lic_razonsocial ?? null);
                                                $set('cin_expediente', $expediente);
                                                $set('cin_solicitante', $personaSolicitante->personasolicitante ?? 'No disponible');

                                                Notification::make()
                                                    ->title('Expediente encontrado')
                                                    ->body("Se completaron los datos:<br>
                                                    - Licencia N°: {$licencia->lic_numlic}<br>
                                                    - Giro: {$licencia->lic_giro}<br>
                                                    - Establecimiento: {$licencia->lic_razonsocial}<br>")
                                                    ->success()
                                                    ->send();
                                                break;

                                            case 'duplicado':
                                                Notification::make()
                                                    ->title('Expediente duplicado')
                                                    ->body('Se encontraron varios registros con este expediente.')
                                                    ->danger()
                                                    ->send();
                                                break;

                                            case 'no_encontrado':
                                                Notification::make()
                                                    ->title('No encontrado')
                                                    ->body('No existe ninguna licencia con este número de expediente.')
                                                    ->warning()
                                                    ->send();
                                                break;

                                            default:
                                                throw new \Exception('Error desconocido al consultar la base de datos.');
                                        }
                                    } catch (\Throwable $e) {
                                        Notification::make()
                                            ->title('Error inesperado')
                                            ->body("Ha ocurrido un problema al buscar el expediente: <br><code>{$e->getMessage()}</code>")
                                            ->danger()
                                            ->persistent()
                                            ->send();
                                    }
                                })
                            ),
                    TextInput::make('cin_ubicacion')
                        ->label('Ubicación')
                        ->maxLength(255)
                        ->required(),

                    Grid::make(3)
                        ->schema([
                            TextInput::make('cin_departamento')
                                ->label('Departamento')
                                ->default('Lima')
                                ->disabled()
                                ->dehydrated(),

                            TextInput::make('cin_provincia')
                                ->label('Provincia')
                                ->default('Lima')
                                ->disabled()
                                ->dehydrated(),

                            TextInput::make('cin_distrito')
                                ->label('Distrito')
                                ->default('La Molina')
                                ->disabled()
                                ->dehydrated(),
                        ]),
                ])
                ->columnSpan('full'),

                        // 3. Área y Capacidad
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('cin_area')
                                    ->label('Área (m²)')
                                    ->type('number')
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->step('0.01')
                                    ->suffix('m²')
                                    ->placeholder('Ej. 150.50')
                                    ->required(),

                                TextInput::make('cin_capacidad')
                                    ->label('Capacidad')
                                    ->type('number')
                                    ->numeric()
                                    ->minValue(1)
                                    ->placeholder('Ej. 1')
                                    ->required()
                                    ->rules(['integer', 'min:1'])
                                    ->helperText('Ingrese un número entero positivo.'),
                            ]),
                    ])
                    ->columnSpan('full'),

                // 4. Fechas
                Section::make()
                    ->schema([
                        DatePicker::make('cin_fecha')
                            ->label('Fecha de Expedición')
                            ->required()
                            ->default(today())
                            ->helperText('Seleccione la fecha de expedición.'),

                        Toggle::make('cin_indeterminado')
                            ->label('Indeterminado')
                            ->reactive()
                            ->default(true)
                            ->helperText('Active si la vigencia del certificado es indeterminada.'),

                        Grid::make(2)
                            ->schema([
                                DatePicker::make('cin_fec_inicio')
                                    ->label('Fecha Inicio')
                                    ->reactive()
                                    ->required()
                                    ->hidden(fn (callable $get) => $get('cin_indeterminado'))
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $set('cin_fec_fin', \Carbon\Carbon::parse($state)->addYears(2)->toDateString());
                                        }
                                    })
                                    ->helperText('Seleccione la fecha de inicio del certificado.'),

                                DatePicker::make('cin_fec_fin')
                                    ->label('Fecha Fin')
                                    ->required()
                                    ->hidden(fn (callable $get) => $get('cin_indeterminado'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->helperText('La fecha de fin se calcula automáticamente como dos años después de la fecha de inicio.'),
                            ]),
                    ])
                    ->columnSpan('full'),

                // 5. Resolución y Sigla
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('cin_resolucion')
                                   ->label('Resolución')
                                    ->placeholder('Ej. 1942-2025')
                                    ->required()
                                    //->mask('99999-9999') // permite hasta 5 dígitos iniciales
                                    ->maxLength(10)
                                    ->helperText('Formato: 1 a 5 dígitos, guion, 4 dígitos (#####-YYYY)')
                                    ->rules(['regex:/^\d{1,5}-\d{4}$/']),

                                TextInput::make('cin_resolucion_sigla')
                                    ->label('Resolución Sigla')
                                    ->default('-MDLM-GDEIP-SPEA')
                                    ->disabled()
                                    ->dehydrated(),
                            ]),
                    ])
                    ->columnSpan('full'),

                // 6. Expediente, Licencia, Procedimiento
                Section::make()
                    ->schema([
                        TextInput::make('cin_expediente')
                            ->label('Expediente')
                            ->reactive(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('lic_id')
                                    ->label('Licencia ID')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('cin_licencia')
                                    ->label('Número de Licencia')
                                    ->disabled()
                                    ->dehydrated(),
                            ]),

                        TextInput::make('cin_procedimiento')
                            ->label('Procedimiento'),
                    ])
                    ->columnSpan('full'),

                // 7. Otros (Giro, Establecimiento, Solicitante, Nota)
                Section::make()
                    ->schema([
                        TextInput::make('cin_giro')
                            ->label('Giro o actividad de la Edificación')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('cin_solicitante')
                            ->label('Solicitante'),

                        Textarea::make('cin_nota')
                            ->label('Nota')
                            ->rows(3),
                    ])
                    ->columnSpan('full'),

                // Campos de sistema (ocultos por defecto o para auditoría)
                Fieldset::make('Campos de Sistema')
                    ->schema([
                        DateTimePicker::make('cin_filafecha')
                            ->label('Fila Fecha')
                            ->disabled()
                            ->dehydrated(),

                        Grid::make(3)
                            ->schema([
                                Toggle::make('cin_filaoriginal')
                                    ->label('Fila Original'),

                                Toggle::make('cin_filaeliminada')
                                    ->label('Fila Eliminada'),

                                Toggle::make('cin_consello')
                                    ->label('Con Sello?'),
                            ]),

                        TextInput::make('usa_id')
                            ->label('Usuario ID')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                    ])

                    ->columnSpan('full'),
            ]);
    }
}
