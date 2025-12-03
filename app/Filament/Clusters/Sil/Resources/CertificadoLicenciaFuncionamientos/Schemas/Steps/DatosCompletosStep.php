<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Steps;

use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Wizard\Step;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections\ExpedienteSection;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections\CatastroSection;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections\LicenciasSection;

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
        ];
    }

    public static function autocompletarDatos(array $data, callable $set): void
    {
        // Expediente
        if (isset($data['expediente'])) {
            $exp = (array) $data['expediente'];
            foreach (['exp_num', 'exp_fec', 'exp_nomrec', 'numdoc', 'numtel', 'correo', 'domfis'] as $field) {
                $set($field, $exp[$field] ?? null);
            }
            // Llenar también exp_razsoc con el mismo valor de exp_nomrec
            $set('exp_razsoc', $exp['exp_nomrec'] ?? null);

            // Llenar los IDs
            $set('exp_nomrec_id', $exp['per_id'] ?? null);
            $set('exp_razsoc_id', $exp['per_id'] ?? null);
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

            // Si hay codigo_unico_tramite, usarlo; si no, usar numero_resolucion
            $valorResolucion = $res['codigo_unico_tramite'] ?? $res['numero_resolucion'] ?? null;
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
