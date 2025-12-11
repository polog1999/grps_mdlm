<?php

namespace App\Services\Sil\Licencias;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    /**
     * Genera un código QR para una licencia
     *
     * @param int $licenciaId ID de la licencia
     * @return string Base64 encoded image
     */
    public function generarQrLicencia(int $licenciaId): string
    {
        // Generar la URL completa a la vista de la licencia
        $url = route('qr.mostrar', ['idLicencia' => $licenciaId]);

        // Construir el código QR
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

        $result = $builder->build();

        // Convertir a base64 para mostrar en la vista
        return base64_encode($result->getString());
    }

    /**
     * Genera un código QR y lo guarda en el sistema de archivos
     *
     * @param int $licenciaId ID de la licencia
     * @param string $path Ruta donde guardar el archivo
     * @return string Ruta del archivo guardado
     */
    public function generarYGuardarQr(int $licenciaId, string $path = null): string
    {
        $url = route('qr.mostrar', ['idLicencia' => $licenciaId]);

        if (!$path) {
            $path = storage_path("app/public/qr-codes/licencia-{$licenciaId}.png");
        }

        // Asegurar que el directorio existe
        $directory = dirname($path);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

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

        $result = $builder->build();

        // Guardar el archivo
        $result->saveToFile($path);

        return $path;
    }

    /**
     * Genera un Data URI para usar directamente en un tag <img>
     * Verifica si ya existe el QR en disco antes de generarlo
     *
     * @param int $licenciaId ID de la licencia
     * @return string Data URI
     */
    public function generarQrDataUri(int $licenciaId): string
    {
        $filename = "qr_{$licenciaId}.png";

        // Verificar si ya existe el QR en disco
        if (\Storage::disk('qr')->exists($filename)) {
            // Leer el archivo existente
            $contents = \Storage::disk('qr')->get($filename);
            return 'data:image/png;base64,' . base64_encode($contents);
        }

        // Generar nuevo QR si no existe
        $url = route('qr.mostrar', ['idLicencia' => $licenciaId]);

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

        $result = $builder->build();
        $pngData = $result->getString();

        // Guardar en disco para uso futuro
        \Storage::disk('qr')->put($filename, $pngData);

        return $result->getDataUri();
    }
}