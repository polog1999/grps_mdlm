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

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_qr');
    }

    // =========================================================================
    //  MÉTODOS PRIVADOS / AUXILIARES
    // =========================================================================

    private function generarKey(): string
    {
        return strtoupper(Str::random(9));
    }

    private function obtenerUrlDestino(int $cqrId, string $key): string
    {
        // Ajusta http o https según tu servidor m.munimolina
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
     * Busca si ya existe un QR activo para la licencia y retorna sus datos.
     * Retorna objeto con {cqr_id, cqr_keyasociado, cqr_qr} o null.
     */
    private function obtenerDatosExistentes(int $licenciaId)
    {
        $result = $this->connectionToPostgreSQL->select(
            'SELECT cqr_id, cqr_keyasociado, cqr_qr 
             FROM qr.codigoqr 
             WHERE cqr_idasociado = ? AND tqr_id = 1 AND cqr_filaEliminada = false
             ORDER BY cqr_id DESC LIMIT 1',
            [$licenciaId]
        );

        return $result[0] ?? null;
    }

    /**
     * Lógica central: Garantiza que exista un registro en BD y devuelve la URL final correcta.
     * Si ya existe, devuelve la URL guardada.
     * Si no existe, inserta (temporal), actualiza y devuelve la nueva URL.
     */
    private function obtenerOGenerarUrlFinal(int $licenciaId): string
    {
        // 1. Verificar si ya existe en BD para no romper la integridad de la KEY
        $existente = $this->obtenerDatosExistentes($licenciaId);

        if ($existente) {
            // Si ya existe, DEBEMOS usar la misma Key y ID para que el link funcione
            // Si la URL guardada en BD es antigua o incorrecta, la regeneramos aquí basada en ID y Key
            return $this->obtenerUrlDestino($existente->cqr_id, $existente->cqr_keyasociado);
        }

        // 2. Si no existe, creamos uno nuevo
        $newKey = 'HFWZ2AIDI@';

        // Insertamos con texto temporal para pasar la validación "IS NOT NULL" del SP
        $urlTemporal = 'PENDIENTE_GENERACION';

        $result = $this->connectionToPostgreSQL->select(
            'SELECT * FROM qr.spu_codigoqr_ins(?, ?, ?, ?, ?, ?, ?) as cqr_id',
            [
                1,                              // p_tqr_id
                'Licencia Nro ' . $licenciaId,  // p_cqr_descripcion
                '',                             // p_cqr_observacion
                $licenciaId,                    // p_cqr_idasociado
                $newKey,                        // p_cqr_keyasociado
                $urlTemporal,                   // p_cqr_qr (No puede ser vacío por el SP)
                0                               // p_usa_id
            ]
        );

        $cqrId = $result[0]->cqr_id ?? null;

        if (!$cqrId || $cqrId <= 0) {
            // Capturar error del SP (ej. duplicado que no detectamos o error -30)
            throw new \Exception("Error al insertar QR en BD. Código: " . ($result[0]->error ?? 'Desconocido'));
        }

        // 3. Generamos la URL Real usando el ID recién creado
        $urlFinal = $this->obtenerUrlDestino($cqrId, $newKey);

        // 4. Actualizamos el registro con la URL real
        $this->connectionToPostgreSQL->statement(
            'UPDATE qr.codigoqr SET cqr_qr = ? WHERE cqr_id = ?',
            [$urlFinal, $cqrId]
        );

        return $urlFinal;
    }

    // =========================================================================
    //  MÉTODOS PÚBLICOS
    // =========================================================================

    /**
     * Genera un Data URI para usar en <img src="..."> y guarda el archivo físico.
     */
    public function generarQrDataUri(int $licenciaId): string
    {
        $filename = "qr_{$licenciaId}.png";

        // Si el archivo físico existe, lo usamos directamente (caché de disco)
        if (Storage::disk('qr')->exists($filename)) {
            $contents = Storage::disk('qr')->get($filename);
            return 'data:image/png;base64,' . base64_encode($contents);
        }

        // Si no existe el archivo, obtenemos la URL (recuperando de BD o creando nuevo)
        $urlFinal = $this->obtenerOGenerarUrlFinal($licenciaId);

        // Generamos la imagen
        $qrResult = $this->construirImagenQr($urlFinal);
        $pngData = $qrResult->getString();

        // Guardamos en disco
        Storage::disk('qr')->put($filename, $pngData);

        return $qrResult->getDataUri();
    }

    /**
     * Retorna solo el string base64 (para vistas al vuelo o PDFs).
     * Asegura que exista en BD antes de renderizar.
     */
    public function generarQrLicencia(int $licenciaId): string
    {
        // Obtenemos la URL correcta (Recuperada o Nueva)
        $urlFinal = $this->obtenerOGenerarUrlFinal($licenciaId);

        // Renderizamos
        $qrResult = $this->construirImagenQr($urlFinal);
        return base64_encode($qrResult->getString());
    }

    /**
     * Obtiene el ID del QR (Legacy helper)
     */
    public function obtenerCqrIdPorLicencia(int $licenciaId): ?int
    {
        $datos = $this->obtenerDatosExistentes($licenciaId);
        return $datos ? $datos->cqr_id : null;
    }
}