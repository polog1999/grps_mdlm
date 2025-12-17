<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\Log;

class LicenciaCreator
{
    use PostgresHelpers;

    protected $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Ejecuta el procedimiento almacenado para crear una licencia
     *
     * @param array $datos Datos organizados por secciones (expediente, catastro, licencias)
     * @return array Resultado del stored procedure
     * @throws \InvalidArgumentException Si faltan campos requeridos
     * @throws \RuntimeException Si el SP retorna error
     */
    public function execute(array $datos)
    {
        try {
            // Validar campos requeridos antes de procesar
            $validationErrors = $this->validateRequiredFields($datos);
            if (!empty($validationErrors)) {
                throw new \InvalidArgumentException(
                    'Errores de validación: ' . implode(', ', $validationErrors)
                );
            }

            // Procesar giros
            $girosIds = [];
            $girosEspecificos = [];
            $girosDescripciones = [];

            if (isset($datos['licencias']['tabla_giros']) && is_array($datos['licencias']['tabla_giros'])) {
                $girosSeleccionados = $datos['licencias']['giros_seleccionar'] ?? [];

                foreach ($datos['licencias']['tabla_giros'] as $index => $giro) {
                    $girosIds[] = isset($girosSeleccionados[$index]) ? (int) $girosSeleccionados[$index] : 0;
                    $girosEspecificos[] = $giro['giro_especifico'] ?? '';

                    if (!empty($giro['giro'])) {
                        $girosDescripciones[] = $giro['giro'];
                    }
                }
            }

            // Construir plic_giro como string concatenado
            $plicGiro = !empty($girosDescripciones) ? implode(',', $girosDescripciones) : '';

            // Formatear horas como character (HH:MM)
            $horaInicio = $datos['licencias']['hora_inicio'] ?? '09:00';
            $horaFin = $datos['licencias']['hora_fin'] ?? '18:00';

            // Asegurar formato HH:MM (character, no time)
            if (strlen($horaInicio) > 5) {
                $horaInicio = substr($horaInicio, 0, 5);
            }
            if (strlen($horaFin) > 5) {
                $horaFin = substr($horaFin, 0, 5);
            }

            // SQL para llamar al stored procedure
            // El SP retorna: TABLE(status integer, message text, new_id integer)
            $sql = "SELECT * FROM licencia.spu_licencia_ins4(
                ?, ?::integer[], ?::text[], ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

            $parametros = [
                $datos['catastro']['fiu_id'] ?? null,                    // 1 pfiu_id
                $this->formatPostgresArray($girosIds),                   // 2 giros_id
                $this->formatPostgresArray($girosEspecificos, true),     // 3 giros_especificos
                $datos['licencias']['tipo_licencia'] ?? null,            // 4 ptli_id
                $datos['licencias']['tipo_establecimientos'] ?? null,    // 5 ptes_id
                $datos['expediente']['exp_nomrec_id'] ?? null,           // 6 pper_idsolicitante
                $datos['expediente']['exp_razsoc_id'] ?? null,           // 7 pper_idrazonsocial
                $datos['licencias']['numero_licencia'] ?? '',            // 8 plic_numlic
                $datos['catastro']['codpredio'] ?? '',                   // 9 plic_codigopredial
                $datos['expediente']['exp_num'] ?? '',                   // 10 plic_expnum
                (float) ($datos['catastro']['area_economica'] ?? 0),     // 11 plic_area
                (($datos['licencias']['mype'] ?? '0') === '1'),          // 12 plic_mype (boolean)
                $datos['licencias']['n_resolucion'] ?? '',               // 13 plic_resnum
                $this->formatDate($datos['licencias']['fecha_resolucion'] ?? null),  // 14 p_lic_fecharesolucion
                $this->formatDate($datos['licencias']['fecha_emision'] ?? null),     // 15 p_lic_fechaemision
                $this->formatDate(null),                                 // 16 p_lic_fechavencimiento (NULL)
                $datos['licencias']['observaciones'] ?? '',              // 17 plic_licobs
                $datos['licencias']['centro_comercial'] ?? 0,            // 18 pcec_id (no puede ser null)
                $datos['licencias']['tipo_local'] ?? 0,                  // 19 ptlo_id
                $datos['licencias']['observaciones_local'] ?? '',        // 20 plcc_observacion
                $datos['licencias']['local'] ?? '',                      // 21 plcc_local
                $datos['licencias']['direccion'] ?? '',                  // 22 plca_descripcion
                $datos['catastro']['descurb'] ?? '',                     // 23 urbanizacion_id
                $datos['catastro']['zonificacion'] ?? '',                // 24 plca_zonificacion
                $plicGiro,                                               // 25 plic_giro
                false,                                                   // 26 p_lic_modidirecc
                $horaInicio,                                             // 27 p_lic_horainicio (character)
                $horaFin,                                                // 28 p_lic_horafin (character)
                $datos['licencias']['tipo_resolucion'] ?? 2,             // 29 p_tir_id
                $datos['licencias']['observaciones'] ?? '',              // 30 p_lic_nota (text)
                auth()->id() ?? 0,                                       // 31 p_usa_id
                $datos['licencias']['compatibilidad'] ?? '',             // 32 p_compatibilidad
                $datos['licencias']['nir_id'] ?? 0,                      // 33 p_nir_id
                0,                                                       // 34 p_cin_id (ITSE)
                $this->formatDate($datos['expediente']['exp_fec'] ?? null),          // 35 p_lic_expfec
                $datos['licencias']['nro_compatibilidad'] ?? '',         // 36 p_lic_compatibilidadnumero
                $this->formatDate($datos['licencias']['fecha_compatibilidad'] ?? null) // 37 p_lic_compatibilidadfecha
            ];

            Log::info('Ejecutando spu_licencia_ins4', [
                'parametros' => $parametros,
                'usuario_id' => auth()->id()
            ]);

            // Ejecutar stored procedure
            $result = $this->db->select($sql, $parametros);

            // Validar que el SP retornó resultado
            if (empty($result)) {
                throw new \RuntimeException('El procedimiento almacenado no retornó ningún resultado');
            }

            $spResult = $result[0];

            // El SP retorna: TABLE(status integer, message text, new_id integer)
            $status = $spResult->status ?? 0;
            $message = $spResult->message ?? 'Error desconocido';
            $newId = $spResult->new_id ?? null;

            Log::info('Resultado del SP spu_licencia_ins4', [
                'status' => $status,
                'message' => $message,
                'new_id' => $newId
            ]);

            // Validar status del SP
            // Status codes:
            // 1 = Success
            // -10 = Error al generar registro en Licencia
            // -20 = Error al registrar giros
            // -30 = Error al registrar catastro
            // -40 = Error al registrar centro comercial
            // -50 = Número de licencia duplicado OR error ficha ubicación
            // -99 = Error interno SQL
            if ($status < 1) {
                throw new \RuntimeException(
                    "Error en el procedimiento almacenado: {$message} (Código: {$status})"
                );
            }

            // Validar que se obtuvo el ID del nuevo registro
            if (!$newId) {
                throw new \RuntimeException('No se pudo obtener el ID del registro creado');
            }

            Log::info('Licencia creada exitosamente', [
                'lic_id' => $newId,
                'status' => $status,
                'message' => $message
            ]);

            return $result;

        } catch (\InvalidArgumentException $e) {
            // Errores de validación
            Log::warning('Validación fallida al crear licencia', [
                'error' => $e->getMessage(),
                'datos' => $datos
            ]);
            throw $e;

        } catch (\RuntimeException $e) {
            // Errores del stored procedure
            Log::error('Error del stored procedure al crear licencia', [
                'error' => $e->getMessage(),
                'datos' => $datos
            ]);
            throw $e;

        } catch (\Exception $e) {
            // Errores inesperados
            Log::error('Error inesperado al crear licencia', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'datos' => $datos
            ]);
            throw new \RuntimeException(
                'Error inesperado al crear la licencia: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Valida que todos los campos requeridos estén presentes
     *
     * @param array $datos Datos a validar
     * @return array Array de mensajes de error (vacío si no hay errores)
     */
    private function validateRequiredFields(array $datos): array
    {
        $errors = [];

        // Validar fiu_id (Catastro)
        if (empty($datos['catastro']['fiu_id'])) {
            $errors[] = 'El campo fiu_id (Ficha de Ubicación) es requerido';
        }

        // Validar exp_nomrec_id (Expediente - Persona)
        if (empty($datos['expediente']['exp_nomrec_id'])) {
            $errors[] = 'Debe seleccionar una persona (Nombre y Apellidos)';
        }

        // Validar tipo_licencia
        if (empty($datos['licencias']['tipo_licencia'])) {
            $errors[] = 'El Tipo de Licencia es requerido';
        }

        // Validar tipo_establecimientos
        if (empty($datos['licencias']['tipo_establecimientos'])) {
            $errors[] = 'El Tipo de Establecimiento es requerido';
        }

        // Validar exp_num (Número de expediente)
        if (empty($datos['expediente']['exp_num'])) {
            $errors[] = 'El Número de Expediente es requerido';
        }

        // Validar direccion
        if (empty($datos['licencias']['direccion'])) {
            $errors[] = 'La Dirección es requerida';
        }

        // Validar nir_id (Nivel de riesgo)
        if (empty($datos['licencias']['nir_id'])) {
            $errors[] = 'El Nivel de Riesgo es requerido';
        }

        $tipoEstablecimiento = $datos['licencias']['tipo_establecimientos'] ?? null;

        if (empty($tipoEstablecimiento)) {
            $errors[] = 'El Tipo de Establecimiento es requerido';
        }

        if ($tipoEstablecimiento && (int) $tipoEstablecimiento === 2) {

            if (empty($datos['licencias']['centro_comercial'])) {
                $errors[] = 'El Centro Comercial es requerido cuando el tipo de establecimiento es "Centro Comercial"';
            }

            if (empty($datos['licencias']['tipo_local'])) {
                $errors[] = 'El Tipo de Local es requerido cuando el tipo de establecimiento es "Centro Comercial"';
            }
        }

        return $errors;
    }
}
