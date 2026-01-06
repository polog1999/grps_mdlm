<?php

namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para gestionar PDFs actualizados de Certificados de Licencia de Funcionamiento.
 */
class CertificadoLicenciaPdfService
{
    /**
     * Nombre del disco de almacenamiento.
     */
    private const DISK = 'licencias_externas';

    /**
     * Carpeta donde se almacenan los PDFs actualizados.
     */
    private const FOLDER = 'certificados_actualizados';

    /**
     * Genera el nombre del archivo PDF.
     *
     * @param string $numeroLicencia Número de la licencia.
     * @param int $licenciaId ID de la licencia.
     * @return string Nombre del archivo.
     */
    private function generarNombreArchivo(string $numeroLicencia, int $licenciaId): string
    {
        // Sanitizar número de licencia para nombre de archivo
        $numeroSanitizado = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $numeroLicencia);
        return "certificado_licencia_{$numeroSanitizado}_{$licenciaId}.pdf";
    }

    /**
     * Obtiene la ruta completa del archivo.
     *
     * @param string $numeroLicencia Número de la licencia.
     * @param int $licenciaId ID de la licencia.
     * @return string Ruta del archivo.
     */
    public function obtenerRutaPdf(string $numeroLicencia, int $licenciaId): string
    {
        $fileName = $this->generarNombreArchivo($numeroLicencia, $licenciaId);
        return self::FOLDER . "/{$fileName}";
    }

    /**
     * Verifica si existe un PDF actualizado para la licencia.
     *
     * @param string $numeroLicencia Número de la licencia.
     * @param int $licenciaId ID de la licencia.
     * @return bool True si existe, false si no.
     */
    public function existePdfActualizado(string $numeroLicencia, int $licenciaId): bool
    {
        $filePath = $this->obtenerRutaPdf($numeroLicencia, $licenciaId);
        return Storage::disk(self::DISK)->exists($filePath);
    }

    /**
     * Sube un certificado actualizado al almacenamiento externo.
     * Permite sobrescribir el archivo si ya existe.
     *
     * @param int $licenciaId ID de la licencia.
     * @param string $numeroLicencia Número de la licencia.
     * @param \Illuminate\Http\UploadedFile $file Archivo PDF a subir.
     * @return array Resultado de la operación.
     */
    public function subirPdfActualizado(int $licenciaId, string $numeroLicencia, $file): array
    {
        $filePath = $this->obtenerRutaPdf($numeroLicencia, $licenciaId);
        $disk = Storage::disk(self::DISK);

        try {
            // Verificar si ya existe para informar al usuario
            $existeArchivo = $disk->exists($filePath);

            // Guardar archivo (sobrescribe si existe)
            $disk->put($filePath, file_get_contents($file->getRealPath()));

            $mensaje = $existeArchivo
                ? 'Certificado de licencia actualizado reemplazado exitosamente'
                : 'Certificado de licencia actualizado subido exitosamente';

            Log::info('CertificadoLicenciaPdfService: PDF actualizado subido', [
                'licencia_id' => $licenciaId,
                'numero_licencia' => $numeroLicencia,
                'file_path' => $filePath,
                'was_overwritten' => $existeArchivo,
            ]);

            return [
                'success' => true,
                'message' => $mensaje,
                'file_name' => basename($filePath),
                'status_code' => 200,
                'was_overwritten' => $existeArchivo
            ];
        } catch (\Throwable $e) {
            Log::error('CertificadoLicenciaPdfService: Error al subir PDF', [
                'licencia_id' => $licenciaId,
                'numero_licencia' => $numeroLicencia,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error al subir el archivo: ' . $e->getMessage(),
                'status_code' => 500,
            ];
        }
    }

    /**
     * Reemplaza (sobrescribe) un certificado actualizado existente.
     *
     * @param int $licenciaId ID de la licencia.
     * @param string $numeroLicencia Número de la licencia.
     * @param \Illuminate\Http\UploadedFile $file Archivo PDF a subir.
     * @return array Resultado de la operación.
     */
    public function reemplazarPdfActualizado(int $licenciaId, string $numeroLicencia, $file): array
    {
        $filePath = $this->obtenerRutaPdf($numeroLicencia, $licenciaId);
        $disk = Storage::disk(self::DISK);

        try {
            // No verificamos existencia, sobrescribimos directamente
            $disk->put($filePath, file_get_contents($file->getRealPath()));

            Log::info('CertificadoLicenciaPdfService: PDF reemplazado', [
                'licencia_id' => $licenciaId,
                'numero_licencia' => $numeroLicencia,
                'file_path' => $filePath,
            ]);

            return [
                'success' => true,
                'message' => 'Certificado de licencia actualizado reemplazado exitosamente',
                'file_name' => basename($filePath),
                'status_code' => 200
            ];
        } catch (\Throwable $e) {
            Log::error('CertificadoLicenciaPdfService: Error al reemplazar PDF', [
                'licencia_id' => $licenciaId,
                'numero_licencia' => $numeroLicencia,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error al reemplazar el archivo: ' . $e->getMessage(),
                'status_code' => 500,
            ];
        }
    }
}
