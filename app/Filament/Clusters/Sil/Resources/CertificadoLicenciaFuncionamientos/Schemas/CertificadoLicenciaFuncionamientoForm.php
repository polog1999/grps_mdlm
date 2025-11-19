<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use App\Services\Sil\Licencias\CertificadoLincenciaFuncionamiento;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;

class CertificadoLicenciaFuncionamientoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make([
                self::expedienteStep(),
                self::catastroStep(),
                self::licenciaStep(),
            ])->columnSpanFull()
        ]);
    }

    private static function expedienteStep(): Step
    {
        return Step::make('Expediente')
            ->description('Datos del expediente')
            ->icon('heroicon-o-archive-box')
            ->schema([
                TextInput::make('lic_expnum')
                    ->label('Número Expediente')
                    ->placeholder('Ingrese número de expediente')
                    ->required()
                    ->maxLength(50)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, callable $set) {
                        if (empty($state)) return;

                        try {
                            $service = app(CertificadoLincenciaFuncionamiento::class);
                            $result = $service->obtenerDatosLicenciaFuncionamiento($state);

                            if ($result->isNotEmpty()) {
                                $data = $result->first();
                                self::autocompletarExpediente($data, $set);
                            }
                        } catch (\Throwable $e) {
                            Log::error('Error autocompletando expediente: ' . $e->getMessage());
                        }
                    }),

                self::makeEditableField('lic_fecha_expediente', 'Fecha Expediente', 'date'),
                self::makeEditableField('per_nombre_apellidos', 'Nombre y Apellidos'),
                self::makeEditableField('lic_razonsocial', 'Razón Social'),
                self::makeEditableField('per_ruc', 'RUC', 'text', maxLength: 20),
                self::makeEditableField('per_numtel', 'Teléfono', 'text', maxLength: 50),
                self::makeEditableField('per_direccion', 'Dirección', 'text', columnSpanFull: true),
                self::makeEditableField('per_correo', 'Email', 'email'),
            ])
            ->columns(2);
    }

    private static function catastroStep(): Step
    {
        return Step::make('Catastro')
            ->description('Información catastral de la licencia')
            ->icon('heroicon-o-map-pin')
            ->schema([
                TextInput::make('cat_codigocatastral')
                    ->label('Código Catastral (*)')
                    ->placeholder('Ingrese código catastral')
                    ->required()
                    ->maxLength(50)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, callable $set) {
                        if (empty($state)) return;

                        try {
                            $service = app(CertificadoLincenciaFuncionamiento::class);
                            $result = $service->obtenerDatosPorCodCat($state);
                            
                            if ($result->isNotEmpty()) {
                                $data = $result->first();
                                self::autocompletarCatastro($data, $set);
                            }
                        } catch (\Throwable $e) {
                            Log::error('Error autocompletando catastro: ' . $e->getMessage());
                        }
                    }),

                self::makeEditableField('codpredio', 'Código Predial', 'text', '_catastro_autocompletado', 100),
                self::makeEditableField('descurb', 'Urbanización', 'text', '_catastro_autocompletado'),
                self::makeEditableField('via_completa', 'Vía Cod', 'text', '_catastro_autocompletado', 50),
                self::makeEditableField('numvia', 'Nro.', 'text', '_catastro_autocompletado', 20),
                self::makeEditableField('intdpto', 'Dpto.', 'text', '_catastro_autocompletado', 20),
                self::makeEditableField('blockedif', 'Bloque', 'text', '_catastro_autocompletado', 20),
                self::makeEditableField('mz', 'Mz', 'text', '_catastro_autocompletado', 20),
                self::makeEditableField('lote', 'Lote', 'text', '_catastro_autocompletado', 20),
                self::makeEditableField('zonificacion', 'Zonificación', 'text', '_catastro_autocompletado', 100),
                self::makeEditableField('area_economica', 'Área Act. Econ.', 'numeric', '_catastro_autocompletado', suffix: 'm²'),
            ])
            ->columns(2);
    }

    private static function licenciaStep(): Step
    {
        return Step::make('Licencia')
            ->description('Información de la licencia de funcionamiento')
            ->icon('heroicon-o-map-pin')
            ->schema([
                TextInput::make('lic_direccion')
                    ->label('Dirección')
                    ->placeholder('Ingrese dirección')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    private static function makeEditableField(
        string $name,
        string $label,
        string $type = 'text',
        string $autocompletedFlag = '_autocompletado',
        int $maxLength = 255,
        bool $columnSpanFull = false,
        ?string $suffix = null
    ) {
        $editFlag = "_edit_" . str_replace(['per_', 'lic_', 'cat_'], '', $name);
        
        $field = match($type) {
            'date' => DatePicker::make($name)
                ->displayFormat('d/m/Y')
                ->native(false),
            'email' => TextInput::make($name)
                ->email(),
            'numeric' => TextInput::make($name)
                ->numeric(),
            default => TextInput::make($name),
        };

        $field = $field
            ->label($label)
            ->placeholder($type === 'date' ? null : "Ingrese " . strtolower($label))
            ->disabled(fn (callable $get) => !$get($autocompletedFlag) || !$get($editFlag))
            ->suffixAction(
                Action::make("edit_{$name}")
                    ->icon('heroicon-o-pencil')
                    ->visible(fn (callable $get) => $get($autocompletedFlag) && !$get($editFlag))
                    ->action(fn (callable $set) => $set($editFlag, true))
            );

        if ($type !== 'date') {
            $field = $field->maxLength($maxLength);
        }

        if ($columnSpanFull) {
            $field = $field->columnSpanFull();
        }

        if ($suffix) {
            $field = $field->suffix($suffix);
        }

        return $field;
    }

    private static function autocompletarExpediente($data, callable $set): void
    {
        $set('_autocompletado', true);

        $campos = [
            'lic_fecha_expediente' => 'exp_fec',
            'per_nombre_apellidos' => 'exp_nomrec',
            'lic_razonsocial' => 'exp_nomrec',
            'per_ruc' => 'numdoc',
            'per_numtel' => 'numtel',
            'per_direccion' => 'domfis',
            'per_correo' => 'correo',
        ];

        foreach ($campos as $destino => $origen) {
            $set($destino, $data->$origen ?? null);
        }

        $editFlags = ['fecha', 'nombre', 'razon', 'ruc', 'tel', 'dir', 'email'];
        foreach ($editFlags as $flag) {
            $set("_edit_{$flag}", false);
        }
    }

    private static function autocompletarCatastro($data, callable $set): void
    {
        $set('_catastro_autocompletado', true);

        $campos = [
            'codpredio', 'descurb', 'via_completa', 'numvia', 'intdpto',
            'blockedif', 'mz', 'lote', 'zonificacion', 'area_economica'
        ];

        foreach ($campos as $campo) {
            $set($campo, $data->$campo ?? null);
        }

        foreach ($campos as $campo) {
            $set("_edit_{$campo}", false);
        }
    }
}