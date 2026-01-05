<?php

namespace App\Services\Sil\Licencias\Handlers;

use Illuminate\Database\ConnectionInterface;
use App\Services\Sil\Licencias\Concerns\PostgresHelpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
                :p_fiu_id::integer,
                :p_gir_id::integer[],
                :p_lig_giroespecifico::text[],
                :p_tli_id::integer,
                :p_tes_id::integer,
                :p_per_idsolicitante::integer,
                :p_per_idrazonsocial::integer,
                :p_lic_numlic::varchar,
                :p_lic_codigopredial::varchar,
                :p_lic_expnum::varchar,
                :p_lic_area::numeric,
                :p_lic_mype::boolean,
                :p_lic_resnum::varchar,
                :p_lic_fecharesolucion::varchar,
                :p_lic_fechaemision::varchar,
                :p_lic_fechavencimiento::varchar,
                :p_lic_licobs::varchar,
                :p_cec_id::integer,
                :p_tlo_id::integer,
                :p_lcc_observacion::varchar,
                :p_lcc_local::varchar,
                :p_lca_descripcion::varchar,
                :p_urbanizacion_id::varchar,
                :p_lca_zonificacion::varchar,
                :p_lic_giro::varchar,
                :p_lic_modidirecc::boolean,
                :p_lic_horainicio::varchar,
                :p_lic_horafin::varchar,
                :p_tir_id::integer,
                :p_lic_nota::text,
                :p_usa_id::integer,
                :p_compatibilidad::varchar,
                :p_nir_id::integer,
                :p_cin_id::integer,
                :p_lic_expfec::varchar,
                :p_lic_compatibilidadnumero::varchar,
                :p_lic_compatibilidadfecha::varchar,
                :p_user_id::integer,
                :p_fecha_operacion::timestamp
            )";

            $parametros = [
                'p_fiu_id' => $datos['catastro']['fiu_id'] ?? null,
                'p_gir_id' => $this->formatPostgresArray($girosIds),
                'p_lig_giroespecifico' => $this->formatPostgresArray($girosEspecificos, true),
                'p_tli_id' => $datos['licencias']['tipo_licencia'] ?? null,
                'p_tes_id' => $datos['licencias']['tipo_establecimientos'] ?? null,
                'p_per_idsolicitante' => $datos['expediente']['exp_nomrec_id'] ?? null,
                'p_per_idrazonsocial' => $datos['expediente']['exp_razsoc_id'] ?? null,
                'p_lic_numlic' => $datos['licencias']['numero_licencia'] ?? '',
                'p_lic_codigopredial' => $datos['catastro']['codpredio'] ?? '',
                'p_lic_expnum' => $datos['expediente']['exp_num'] ?? '',
                'p_lic_area' => (float) ($datos['catastro']['area_economica'] ?? 0),
                'p_lic_mype' => (($datos['licencias']['mype'] ?? 0) == 1),
                'p_lic_resnum' => $datos['licencias']['n_resolucion'] ?? '',
                'p_lic_fecharesolucion' => $this->formatDate($datos['licencias']['fecha_resolucion'] ?? null),
                'p_lic_fechaemision' => $this->formatDate($datos['licencias']['fecha_emision'] ?? null),
                'p_lic_fechavencimiento' => $this->formatDate($datos['licencias']['fecha_vencimiento'] ?? null),
                'p_lic_licobs' => $datos['licencias']['observaciones'] ?? '',
                'p_cec_id' => $datos['licencias']['centro_comercial'] ?? 0,
                'p_tlo_id' => $datos['licencias']['tipo_local'] ?? 0,
                'p_lcc_observacion' => $datos['licencias']['observaciones_local'] ?? '',
                'p_lcc_local' => $datos['licencias']['local'] ?? '',
                'p_lca_descripcion' => $datos['licencias']['direccion'] ?? '',
                'p_urbanizacion_id' => $datos['catastro']['descurb'] ?? '',
                'p_lca_zonificacion' => $datos['catastro']['zonificacion'] ?? '',
                'p_lic_giro' => $plicGiro,
                'p_lic_modidirecc' => false,
                'p_lic_horainicio' => $horaInicio,
                'p_lic_horafin' => $horaFin,
                'p_tir_id' => $datos['licencias']['tipo_resolucion'] ?? 2,
                'p_lic_nota' => $datos['licencias']['observaciones'] ?? '',
                'p_usa_id' => auth()->id() ?? 0,
                'p_compatibilidad' => $datos['licencias']['compatibilidad'] ?? '',
                'p_nir_id' => $datos['licencias']['nir_id'] ?? 0,
                'p_cin_id' => 0,
                'p_lic_expfec' => $this->formatDate($datos['expediente']['exp_fec'] ?? null),
                'p_lic_compatibilidadnumero' => $datos['licencias']['nro_compatibilidad'] ?? '',
                'p_lic_compatibilidadfecha' => $this->formatDate($datos['licencias']['fecha_compatibilidad'] ?? null),
                'p_user_id' => Auth::id() ?? 0,
                'p_fecha_operacion' => now()->format('Y-m-d H:i:s'),
            ];

            Log::info('Ejecutando spu_licencia_ins4', [
                'parametros' => $parametros,
                'usuario_id' => Auth::id(),
                'usuario_name' => Auth::user()?->name ?? 'N/A'
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
