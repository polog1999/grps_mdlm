<?php

namespace App\Http\Controllers;

use App\Services\Sil\CertificadoInspeccion\LicenciaService;
use Illuminate\Http\Request;

class LicenciaController extends Controller
{

    protected $service;
    public function __construct(LicenciaService $service)
    {
        $this->service = $service;
    }

    /**
     * Buscar por número de expediente.
     *
     * Llama a `LicenciaService::obtenerPorNumeroExpediente` y devuelve el resultado tal cual
     * en formato JSON. El servicio encapsula las comprobaciones de duplicados y la normalización
     * de datos.
     *
     * Formato de respuesta esperado (ejemplos):
     * - {"status":"ok","data":{...}}        -> coincidencia única
     * - {"status":"duplicado","data":[...]} -> varias coincidencias
     * - {"status":"no_encontrado"}            -> sin resultados
     * - {"status":"error","message":"..."} -> fallo en la consulta
     *
     * @param  string  $lic_numexp  Número de expediente a buscar
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPorNumeroExpediente($lic_numexp)
    {
        $items = $this->service->obtenerPorNumeroExpediente($lic_numexp);
        return response()->json($items);
    }

    /**
     * Buscar por número de licencia.
     *
     * Útil cuando se conoce el número de licencia y se desea recuperar los
     * datos asociados desde la base histórica.
     *
     * @param  string  $lic_numlic  Número de licencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPorNumeroLicencia($lic_numlic)
    {
        $items = $this->service->obtenerPorNumeroLicencia($lic_numlic);
        return response()->json($items);
    }

    /**
     * Buscar por número de licencia y número de expediente.
     *
     * En la base de datos antigua pueden existir registros duplicados o
     * inconsistentes; proporcionar ambos valores ayuda a desambiguar la búsqueda.
     *
     * @param  string  $lic_numlic  Número de licencia
     * @param  string  $lic_expnum  Número de expediente
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPorNumeroLicenciaYExpediente($lic_numlic, $lic_expnum)
    {
        $items = $this->service->obtenerPorNumeroLicenciaYExpediente($lic_numlic, $lic_expnum);
        return response()->json($items);
    }


    /**
     * Buscar por ID de licencia.
     * 
     * @param  string  $lic_id  ID de licencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPorIdLicencia($lic_id)
    {
        $items = $this->service->obtenerPorIdLicencia($lic_id);
        return response()->json($items);
    }

    /**
     * Obtiene el tipo de licencia por número de expediente.
     *
     * @param string $lic_expnum
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerTipoLicenciaPorExpediente($lic_expnum)
    {
        $item = $this->service->obtenerTipoLicenciaPorExpediente($lic_expnum);
        return response()->json($item);
    }

    /**
     * Obtiene el tipo de licencia por número de licencia.
     *
     * @param string $lic_numlic
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerTipoLicenciaPorNumeroLicencia($lic_numlic)
    {
        $item = $this->service->obtenerTipoLicenciaPorNumeroLicencia($lic_numlic);
        return response()->json($item);
    }
}
