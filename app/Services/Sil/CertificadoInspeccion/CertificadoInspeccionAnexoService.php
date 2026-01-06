<?php

namespace App\Services\Sil\CertificadoInspeccion;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para gestionar anexos de Certificados de Inspección.
 */
class CertificadoInspeccionAnexoService
{
    /**
     * Nombre del disco de almacenamiento.
     */
    private const DISK = 'certificados_externos';

    /**
     * Carpeta donde se almacenan los anexos.
     */
    private const FOLDER = 'anexos_actualizar';

    /**
     * Genera el nombre del archivo de anexo.
     *
     * @param int $certificadoId ID del certificado.
     * @return string Nombre del archivo.
     */
    private function generarNombreArchivo(int $certificadoId): string
    {
        return "anexo_inspeccion_id_{$certificadoId}.pdf";
    }

    /**
     * Obtiene la ruta completa del archivo de anexo.
     *
     * @param int $certificadoId ID del certificado.
     * @return string Ruta del archivo.
     */
    public function obtenerRutaAnexo(int $certificadoId): string
    {
        $fileName = $this->generarNombreArchivo($certificadoId);
        return self::FOLDER . "/{$fileName}";
    }

    /**
     * Verifica si existe un anexo para el certificado.
     *
     * @param int $certificadoId ID del certificado.
     * @return bool True si existe, false si no.
     */
    public function existeAnexo(int $certificadoId): bool
    {
        $filePath = $this->obtenerRutaAnexo($certificadoId);
        return Storage::disk(self::DISK)->exists($filePath);
    }

    /**
     * Sube un anexo al almacenamiento externo.
     * Permite sobrescribir el archivo si ya existe.
     *
     * @param int $certificadoId ID del certificado.
     * @param \Illuminate\Http\UploadedFile $file Archivo PDF a subir.
     * @return array Resultado de la operación.
     */
    public function subirPdfAnexo(int $certificadoId, $file): array
    {
        $filePath = $this->obtenerRutaAnexo($certificadoId);
        $disk = Storage::disk(self::DISK);

        try {
            // Verificar si ya existe para informar al usuario
            $existeArchivo = $disk->exists($filePath);

            // Guardar archivo (sobrescribe si existe)
            $disk->put($filePath, file_get_contents($file->getRealPath()));

            $mensaje = $existeArchivo
                ? 'Anexo reemplazado exitosamente'
                : 'Anexo subido exitosamente';

            Log::info('CertificadoInspeccionAnexoService: Anexo subido', [
                'certificado_id' => $certificadoId,
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
            Log::error('CertificadoInspeccionAnexoService: Error al subir anexo', [
                'certificado_id' => $certificadoId,
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
     * Reemplaza (sobrescribe) un anexo existente.
     *
     * @param int $certificadoId ID del certificado.
     * @param \Illuminate\Http\UploadedFile $file Archivo PDF a subir.
     * @return array Resultado de la operación.
     */
    public function reemplazarPdfAnexo(int $certificadoId, $file): array
    {
        $filePath = $this->obtenerRutaAnexo($certificadoId);
        $disk = Storage::disk(self::DISK);

        try {
            // No verificamos existencia, sobrescribimos directamente
            $disk->put($filePath, file_get_contents($file->getRealPath()));

            Log::info('CertificadoInspeccionAnexoService: Anexo reemplazado', [
                'certificado_id' => $certificadoId,
                'file_path' => $filePath,
            ]);

            return [
                'success' => true,
                'message' => 'Anexo reemplazado exitosamente',
                'file_name' => basename($filePath),
                'status_code' => 200
            ];
        } catch (\Throwable $e) {
            Log::error('CertificadoInspeccionAnexoService: Error al reemplazar anexo', [
                'certificado_id' => $certificadoId,
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
