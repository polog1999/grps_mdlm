<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

    private function generarKey(): string
    {
        // Usamos bin2hex(random_bytes) que genera 32 caracteres.
        // Es seguro y cabe en columnas VARCHAR(50) sin dar error de longitud.
        return bin2hex(random_bytes(16));
    }

    private function obtenerUrlDestino(int $cqrId, string $key): string
    {
        return "https://m.munimolina.gob.pe/m/isi.php?qr={$cqrId}&key={$key}";
    }

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
        // AQUÍ ESTÁ EL FILTRO CLAVE: "AND tqr_id = 1"
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

    private function obtenerOGenerarUrlFinal(int $licenciaId): string
    {
        // 1. Buscamos si existe registro para esta Licencia Y que sea Tipo 1
        $existente = $this->obtenerDatosExistentes($licenciaId);

        // Variable para decidir si creamos uno nuevo o actualizamos el existente
        $idParaActualizar = null;
        $necesitaNuevaKey = false;

        if ($existente) {
            $idParaActualizar = $existente->cqr_id;
            $keyActual = (string) $existente->cqr_keyasociado;

            Log::info("QR Encontrado (ID: $idParaActualizar) para Licencia $licenciaId y TIPO " . self::TIPO_QR_LICENCIA);

            // Verificamos si la key está vacía o es inválida (menor a 15 chars)
            if (empty($keyActual) || strlen($keyActual) < 15) {
                Log::warning("La key actual ('$keyActual') es vacía o antigua. Se generará una nueva.");
                $necesitaNuevaKey = true;
            } else {
                // Si todo está bien, retornamos la URL tal cual está en BD
                return $this->obtenerUrlDestino($idParaActualizar, $keyActual);
            }
        } else {
            Log::info("No se encontró QR tipo " . self::TIPO_QR_LICENCIA . " para Licencia $licenciaId. Se creará uno nuevo.");
            $necesitaNuevaKey = true; // Si no existe, obviamente necesitamos key nueva
        }

        // 2. Si llegamos aquí, necesitamos generar una key (ya sea para UPDATE o INSERT)
        if ($necesitaNuevaKey) {

            $nuevaKey = $this->generarKey();
            $urlFinal = '';

            if ($idParaActualizar) {
                // --- CAMINO A: ACTUALIZAR (UPDATE) ---
                // Solo entramos aquí si existe Y coincide el tqr_id (validado en obtenerDatosExistentes)

                $urlFinal = $this->obtenerUrlDestino($idParaActualizar, $nuevaKey);

                // Ejecutamos el UPDATE reforzando el WHERE por seguridad
                $updated = $this->connectionToPostgreSQL->update(
                    'UPDATE qr.codigoqr 
                     SET cqr_keyasociado = ?, cqr_qr = ? 
                     WHERE cqr_idasociado = ? AND tqr_id = ?',
                    [
                        $nuevaKey,
                        $urlFinal,
                        $licenciaId,
                        self::TIPO_QR_LICENCIA
                    ]
                );

                Log::info("Registro actualizado. Filas afectadas: $updated");

                // Borramos caché física para forzar regeneración de imagen
                Storage::disk('qr')->delete("qr_{$licenciaId}.png");

            } else {
                // --- CAMINO B: INSERTAR (INSERT) ---

                $urlTemporal = 'PENDIENTE_GENERACION';

                // Usamos el SP para insertar asegurando el TIPO 1
                $result = $this->connectionToPostgreSQL->select(
                    'SELECT * FROM qr.spu_codigoqr_ins(?, ?, ?, ?, ?, ?, ?) as cqr_id',
                    [
                        self::TIPO_QR_LICENCIA,         // p_tqr_id = 1
                        'Licencia Nro ' . $licenciaId,  // descripcion
                        '',                             // observacion
                        $licenciaId,                    // idasociado
                        $nuevaKey,                      // key generada
                        $urlTemporal,                   // url temporal
                        0                               // usuario
                    ]
                );

                $cqrId = $result[0]->cqr_id ?? null;
                if (!$cqrId)
                    throw new \Exception("Error al insertar QR.");

                // Actualizamos con la URL final
                $urlFinal = $this->obtenerUrlDestino($cqrId, $nuevaKey);

                $this->connectionToPostgreSQL->statement(
                    'UPDATE qr.codigoqr SET cqr_qr = ? WHERE cqr_id = ?',
                    [$urlFinal, $cqrId]
                );
            }

            return $urlFinal;
        }

        return ''; // Fallback (no debería llegar aquí)
    }

    // =========================================================================
    //  MÉTODOS PÚBLICOS
    // =========================================================================

    public function generarQrDataUri(int $licenciaId): string
    {
        $filename = "qr_{$licenciaId}.png";

        // NOTA: Si siempre tienes problemas con keys viejas, comenta este IF temporalmente
        if (Storage::disk('qr')->exists($filename)) {
            $contents = Storage::disk('qr')->get($filename);
            return 'data:image/png;base64,' . base64_encode($contents);
        }

        $urlFinal = $this->obtenerOGenerarUrlFinal($licenciaId);
        $qrResult = $this->construirImagenQr($urlFinal);

        Storage::disk('qr')->put($filename, $qrResult->getString());

        return $qrResult->getDataUri();
    }

    public function generarQrLicencia(int $licenciaId): string
    {
        $urlFinal = $this->obtenerOGenerarUrlFinal($licenciaId);
        $qrResult = $this->construirImagenQr($urlFinal);
        return base64_encode($qrResult->getString());
    }

    public function obtenerCqrIdPorLicencia(int $licenciaId): ?int
    {
        $datos = $this->obtenerDatosExistentes($licenciaId);
        return $datos ? $datos->cqr_id : null;
    }
}