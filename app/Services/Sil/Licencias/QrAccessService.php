<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class QrAccessService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_qr');
    }

    /**
     * Registra una auditoría de acceso al código QR
     *
     * @param Request $request Objeto Request de Laravel
     * @param int $cqr_id ID del código QR escaneado
     * @return bool True si se registró exitosamente, false en caso de error
     */
    public function registrarAcceso(Request $request, int $cqr_id): bool
    {
        try {
            // Inicializar el agente para detección de dispositivo
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());

            // Detectar tipo de dispositivo
            $equipo = $this->detectarTipoDispositivo($agent);

            // Detectar país basado en IP
            $pais = $this->detectarPais($request);

            // Obtener información del navegador
            $navegador = $agent->browser() ?: 'Desconocido';
            $navegadorVersion = $agent->version($navegador) ?: 'N/A';

            // Obtener plataforma y sistema operativo
            $plataforma = $agent->platform() ?: 'Desconocido';
            $sistemaOperativo = $this->obtenerSistemaOperativo($agent);

            // Obtener información de IP
            $ip = $request->ip();
            $realIp = $this->obtenerRealIp($request);

            // Obtener User Agent completo
            $agente = $request->userAgent() ?: 'Desconocido';

            // Llamar al stored procedure
            $this->connectionToPostgreSQL->select(
                'SELECT qr.spu_qracceso_ins(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $cqr_id,                // p_cqr_id
                    $equipo,                // p_equipo
                    $pais,                  // p_pais
                    $navegador,             // p_navegador
                    $navegadorVersion,      // p_navegadorversion
                    $plataforma,            // p_plataforma
                    $sistemaOperativo,      // p_sistemaoperativo
                    $ip,                    // p_qra_ip
                    $realIp,                // p_qra_realip
                    null,                   // p_qra_macaddress (NULL)
                    null,                   // p_qra_hostname (NULL por rendimiento)
                    $agente                 // p_qra_agente
                ]
            );

            return true;
        } catch (\Exception $e) {
            // Loguear el error pero no bloquear el flujo principal
            Log::error('Error al registrar acceso QR', [
                'cqr_id' => $cqr_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Detecta el tipo de dispositivo
     *
     * @param Agent $agent
     * @return string
     */
    private function detectarTipoDispositivo(Agent $agent): string
    {
        if ($agent->isRobot()) {
            return 'Robot';
        }

        if ($agent->isTablet()) {
            return 'Tablet';
        }

        if ($agent->isMobile()) {
            return 'Móvil';
        }

        if ($agent->isDesktop()) {
            return 'Desktop';
        }

        return 'Desconocido';
    }

    /**
     * Detecta el país basado en la IP
     *
     * @param Request $request
     * @return string
     */
    private function detectarPais(Request $request): string
    {
        try {
            $ip = $request->ip();

            // Evitar detección para IPs locales
            if ($this->esIpLocal($ip)) {
                return 'Local';
            }

            $location = Location::get($ip);

            if ($location && $location->countryName) {
                return $location->countryName;
            }

            return 'Desconocido';
        } catch (\Exception $e) {
            Log::warning('Error al detectar país', [
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            return 'Desconocido';
        }
    }

    /**
     * Obtiene el sistema operativo completo
     *
     * @param Agent $agent
     * @return string
     */
    private function obtenerSistemaOperativo(Agent $agent): string
    {
        $platform = $agent->platform();
        $version = $agent->version($platform);

        if ($platform && $version) {
            return "{$platform} {$version}";
        }

        return $platform ?: 'Desconocido';
    }

    /**
     * Obtiene la IP real del cliente considerando proxies
     *
     * @param Request $request
     * @return string
     */
    private function obtenerRealIp(Request $request): string
    {
        // Verificar headers comunes de proxies
        $headers = [
            'HTTP_CF_CONNECTING_IP',    // Cloudflare
            'HTTP_X_FORWARDED_FOR',     // Proxy estándar
            'HTTP_X_REAL_IP',           // Nginx
            'HTTP_CLIENT_IP',           // Proxy
            'REMOTE_ADDR'               // IP directa
        ];

        foreach ($headers as $header) {
            $ip = $request->server($header);

            if ($ip && $this->esIpValida($ip)) {
                // Si es una lista de IPs, tomar la primera
                if (str_contains($ip, ',')) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                return $ip;
            }
        }

        return $request->ip();
    }

    /**
     * Verifica si una IP es local
     *
     * @param string $ip
     * @return bool
     */
    private function esIpLocal(string $ip): bool
    {
        $localIps = ['127.0.0.1', '::1', 'localhost'];

        if (in_array($ip, $localIps)) {
            return true;
        }

        // Verificar rangos privados
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }

    /**
     * Verifica si una IP es válida
     *
     * @param string $ip
     * @return bool
     */
    private function esIpValida(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
}
