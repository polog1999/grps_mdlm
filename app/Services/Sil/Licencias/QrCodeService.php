<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    protected $connectionToPostgreSQL;

    // Constante para asegurar que siempre trabajamos con el TIPO 1 (Licencias)
    const TIPO_QR_LICENCIA = 1;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_qr');
    }

    // =========================================================================
    //  MÉTODOS PRIVADOS / AUXILIARES
    // =========================================================================

    /**
     * Obtiene una URL firmada desde el API para la licencia especificada.
     *
     * @param int $licenciaId ID de la licencia
     * @return string URL firmada con expiración
     * @throws \Exception Si no se puede obtener la URL
     */
    private function obtenerSignedUrl(int $licenciaId): string
    {
        $baseUrl = config('services.qr_api.base_url');
        $secret = config('services.qr_api.secret');
        $timeout = config('services.qr_api.timeout', 10);
        $retryTimes = config('services.qr_api.retry_times', 3);
        $retryDelay = config('services.qr_api.retry_delay', 100);
        $signature = hash_hmac('sha256', (string) $licenciaId, $secret);

        if (empty($baseUrl)) {
            throw new \RuntimeException('QR_API_BASE_URL no está configurado');
        }

        // Sanitizar entrada
        $licenciaId = (int) $licenciaId;
        if ($licenciaId <= 0) {
            throw new \InvalidArgumentException('ID de licencia inválido');
        }

        try {
            $response = Http::timeout($timeout)
                ->retry($retryTimes, $retryDelay)
                ->get("{$baseUrl}/api/licenses/{$licenciaId}/pdf", [
                    'signature' => $signature
                ]);
            if (!$response->successful()) {
                Log::error('API Error', ['status' => $response->status()]);
                throw new \RuntimeException("API Error: " . $response->status());
            }

            // NOTA IMPORTANTE: 
            // Si tu endpoint de NestJS devuelve el archivo PDF directo (Buffer), 
            // $response->json() fallará porque no es un JSON.
            // Si lo que quieres es la URL para el QR, simplemente devuélvela tú:

            return "{$baseUrl}/api/licenses/{$licenciaId}/pdf?signature={$signature}";

        } catch (\Exception $e) {
            Log::error('Error al conectar con NestJS', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Construye la imagen QR a partir de una URL
     */
    private function construirImagenQr(string $url): \Endroid\QrCode\Writer\Result\ResultInterface
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        );

        return $builder->build();
    }

    /**
     * Busca un QR existente asegurando que sea del TIPO 1 (Licencias)
     */
    private function obtenerDatosExistentes(int $licenciaId)
    {
        $result = $this->connectionToPostgreSQL->select(
            'SELECT cqr_id, cqr_keyasociado, cqr_qr 
             FROM qr.codigoqr 
             WHERE cqr_idasociado = ? 
               AND tqr_id = ? 
               AND cqr_filaEliminada = false
             ORDER BY cqr_id DESC LIMIT 1',
            [$licenciaId, self::TIPO_QR_LICENCIA]
        );

        return $result[0] ?? null;
    }

    // =========================================================================
    //  LÓGICA CENTRAL
    // =========================================================================

    /**
     * Obtiene o genera la URL final para el código QR.
     * Ahora usa el API de URLs firmadas.
     *
     * @param int $licenciaId ID de la licencia
     * @return string URL firmada para el QR
     */
    private function obtenerOGenerarUrlFinal(int $licenciaId): string
    {
        // Obtener URL firmada desde el API
        $signedUrl = $this->obtenerSignedUrl($licenciaId);

        // Verificar si existe registro en BD y actualizarlo
        $existente = $this->obtenerDatosExistentes($licenciaId);

        if ($existente) {
            // Actualizar URL en el registro existente
            $this->connectionToPostgreSQL->update(
                'UPDATE qr.codigoqr 
                 SET cqr_qr = ? 
                 WHERE cqr_idasociado = ? AND tqr_id = ?',
                [$signedUrl, $licenciaId, self::TIPO_QR_LICENCIA]
            );

            Log::info("QR actualizado con nueva signed URL", [
                'cqr_id' => $existente->cqr_id,
                'licencia_id' => $licenciaId
            ]);
        } else {
            // Crear nuevo registro
            $result = $this->connectionToPostgreSQL->select(
                'SELECT * FROM qr.spu_codigoqr_ins(?, ?, ?, ?, ?, ?, ?) as cqr_id',
                [
                    self::TIPO_QR_LICENCIA,         // p_tqr_id = 1
                    'Licencia Nro ' . $licenciaId,  // descripcion
                    '',                             // observacion
                    $licenciaId,                    // idasociado
                    '',                             // key (ya no se usa, pero el SP lo requiere)
                    $signedUrl,                     // url
                    0                               // usuario
                ]
            );

            $cqrId = $result[0]->cqr_id ?? null;
            if (!$cqrId) {
                throw new \RuntimeException("Error al insertar registro QR");
            }

            Log::info("Nuevo registro QR creado", [
                'cqr_id' => $cqrId,
                'licencia_id' => $licenciaId
            ]);
        }

        // Invalidar caché de imagen
        Storage::disk('qr')->delete("qr_{$licenciaId}.png");

        return $signedUrl;
    }

    // =========================================================================
    //  MÉTODOS PÚBLICOS
    // =========================================================================

    /**
     * Obtiene la URL a la que redirige el QR para consultas directas sin necesidad de escanear.
     *
     * @param int $licenciaId ID de la licencia
     * @return string URL firmada para la consulta
     */
    public function obtenerUrlConsulta(int $licenciaId): string
    {
        $baseUrl = config('services.qr_api.base_url');
        $secret = config('services.qr_api.secret');
        $signature = hash_hmac('sha256', (string) $licenciaId, $secret);

        return "{$baseUrl}/api/licenses/{$licenciaId}/pdf?signature={$signature}";
    }

    /**
     * Genera el código QR y retorna como Data URI (base64)
     *
     * @param int $licenciaId ID de la licencia
     * @return string Data URI de la imagen PNG
     */
    public function generarQrDataUri(int $licenciaId): string
    {
        $filename = "qr_{$licenciaId}.png";

        // Verificar caché (comentar si hay problemas con URLs expiradas)
        if (Storage::disk('qr')->exists($filename)) {
            $contents = Storage::disk('qr')->get($filename);
            return 'data:image/png;base64,' . base64_encode($contents);
        }

        $urlFinal = $this->obtenerOGenerarUrlFinal($licenciaId);
        $qrResult = $this->construirImagenQr($urlFinal);

        // Guardar en caché
        Storage::disk('qr')->put($filename, $qrResult->getString());

        return $qrResult->getDataUri();
    }

    /**
     * Genera el código QR y retorna como string base64
     *
     * @param int $licenciaId ID de la licencia
     * @return string Imagen PNG codificada en base64
     */
    public function generarQrLicencia(int $licenciaId): string
    {
        $urlFinal = $this->obtenerOGenerarUrlFinal($licenciaId);
        $qrResult = $this->construirImagenQr($urlFinal);
        return base64_encode($qrResult->getString());
    }

    /**
     * Obtiene el ID del registro QR para una licencia
     *
     * @param int $licenciaId ID de la licencia
     * @return int|null ID del registro QR o null si no existe
     */
    public function obtenerCqrIdPorLicencia(int $licenciaId): ?int
    {
        $datos = $this->obtenerDatosExistentes($licenciaId);
        return $datos ? $datos->cqr_id : null;
    }

    /**
     * Fuerza la regeneración del QR invalidando la caché
     *
     * @param int $licenciaId ID de la licencia
     * @return string Data URI de la nueva imagen
     */
    public function regenerarQr(int $licenciaId): string
    {
        // Eliminar caché existente
        Storage::disk('qr')->delete("qr_{$licenciaId}.png");

        // Generar nuevo QR con URL firmada actualizada
        return $this->generarQrDataUri($licenciaId);
    }
}