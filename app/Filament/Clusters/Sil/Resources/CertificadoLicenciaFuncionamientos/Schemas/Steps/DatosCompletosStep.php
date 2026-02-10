<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps;

use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Wizard\Step;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections\ExpedienteSection;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections\CatastroSection;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections\LicenciasSection;
use Illuminate\Support\Facades\Log;

class DatosCompletosStep
{
    public static function make(): Step
    {
        return Step::make('Datos Completos')
            ->description('Revise y complete la información')
            ->icon('heroicon-o-document-text')
            ->schema([
                ...self::camposOcultosSistema(),
                ExpedienteSection::make(),
                CatastroSection::make(),
                LicenciasSection::make(),
            ]);
    }

    private static function camposOcultosSistema(): array
    {
        return [
            Hidden::make('fiu_id')->dehydrated(),
            Hidden::make('_datos_completos')
                ->formatStateUsing(fn($state) => is_array($state) ? json_encode($state) : $state)
                ->dehydrated(false)
                ->live(),

            Hidden::make('_persona_requerida')->live(),

            // Campo oculto para guardar cin_id de ITSE seleccionada (riesgo alto/muy alto)
            Hidden::make('_cin_id_seleccionado')
                ->dehydrated()
                ->live(),
        ];
    }

    public static function autocompletarDatos(array $data, callable $set): void
    {
        Log::info('DatosCompletosStep: Autocompletando datos', ['data' => $data]);
        // Expediente
        if (isset($data['expediente'])) {
            $exp = (array) $data['expediente'];

            // Campos básicos del expediente
            foreach (['exp_num', 'exp_fec', 'exp_nomrec', 'exp_codcon'] as $field) {
                $set($field, $exp[$field] ?? null);
            }

            // Llenar también exp_razsoc con el mismo valor de exp_nomrec
            $set('exp_razsoc', $exp['exp_nomrec'] ?? null);

            // Llenar los IDs
            $personaId = $exp['exp_nomrec_id'] ?? $exp['per_id'] ?? null;
            $set('exp_nomrec_id', $personaId);
            $set('exp_razsoc_id', $exp['exp_razsoc_id'] ?? $personaId);

            // Si tenemos un ID de persona, obtener datos de contacto desde PostgreSQL
            if ($personaId) {
                try {
                    $service = app(\App\Services\Sil\Licencias\LicenciaPersonaService::class);
                    $personas = $service->getLicenciaPersonaNombre();
                    $persona = $personas->firstWhere('per_id', $personaId);

                    if ($persona) {
                        // Usar datos de PostgreSQL en lugar de Oracle
                        $set('numdoc', $persona->per_ruc ?? '');
                        $set('numtel', $persona->per_telefono ?? '');
                        $set('correo', $persona->per_email ?? '');
                        $set('domfis', $persona->per_direccion ?? '');
                    } else {
                        // Si no se encuentra en PostgreSQL, usar datos de Oracle como fallback
                        foreach (['numdoc', 'numtel', 'correo', 'domfis'] as $field) {
                            $set($field, $exp[$field] ?? null);
                        }
                    }
                } catch (\Exception $e) {
                    // En caso de error, usar datos de Oracle como fallback
                    \Log::warning('Error al obtener datos de persona desde PostgreSQL: ' . $e->getMessage());
                    foreach (['numdoc', 'numtel', 'correo', 'domfis'] as $field) {
                        $set($field, $exp[$field] ?? null);
                    }
                }
            } else {
                // Si no hay ID de persona, usar datos de Oracle
                foreach (['numdoc', 'numtel', 'correo', 'domfis'] as $field) {
                    $set($field, $exp[$field] ?? null);
                }
            }
        }

        // Catastro
        if (!empty($data['catastro'])) {
            $cat = (array) $data['catastro'];
            foreach (['fiu_id', 'coduca', 'codpredio', 'descurb', 'via_completa', 'numvia', 'intdpto', 'blockedif', 'mz', 'lote', 'zonificacion', 'area_economica'] as $field) {
                $set($field, $cat[$field] ?? null);
            }


            $componentes = [
                'via_completa' => '',
                'descurb' => '',
                'numvia' => 'NRO',
                'intdpto' => 'DPTO',
                'blockedif' => 'BLQ',
                'mz' => 'MZ',
                'lote' => 'LT',
            ];
            $parts = [];
            foreach ($componentes as $campo => $prefijo) {
                $valor = trim($cat[$campo] ?? '');
                if (!empty($valor)) {
                    $parts[] = trim($prefijo . ' ' . strtoupper($valor));
                }
            }
            $direccionCalculada = implode(' ', $parts);
            $set('direccion', $direccionCalculada);
            // -----------------------------------------------------
        }

        // Nivel de Riesgo
        if (!empty($data['nivel_riesgo'])) {
            $nr = (array) $data['nivel_riesgo'];
            $set('proccodigo', $nr['proccodigo'] ?? null);
            $set('procnivel', $nr['procnivel'] ?? null);

            if (isset($nr['nivel_riesgo'])) {
                $nrd = (array) $nr['nivel_riesgo'];
                $set('nir_id', $nrd['nir_id'] ?? null);
                $set('nir_descripcion', $nrd['nir_descripcion'] ?? null);
            }
        }

        // Resolución
        if (!empty($data['resolucion'])) {
            $res = (array) $data['resolucion'];

            $valorResolucion = $res['codigo_unico_tramite'] ?? null;

            $set('n_resolucion', $valorResolucion);

            // Convertir fecha de DD/MM/YYYY a formato que DatePicker entienda (YYYY-MM-DD)
            if (!empty($res['fecha_ingreso'])) {
                $fechaIngreso = $res['fecha_ingreso'];
                $fechaConvertida = null;

                // Si viene en formato DD/MM/YYYY, convertir a YYYY-MM-DD
                if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $fechaIngreso, $matches)) {
                    $fechaConvertida = "{$matches[3]}-{$matches[2]}-{$matches[1]}";
                } else {
                    $fechaConvertida = $fechaIngreso;
                }

                // Establecer ambas fechas con el mismo valor
                $set('fecha_resolucion', $fechaConvertida);
                $set('fecha_emision', $fechaConvertida);  // Copiar también a fecha_emision
            }
        }
    }
}
