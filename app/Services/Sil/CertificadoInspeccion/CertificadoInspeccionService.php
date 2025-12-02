<?php
namespace App\Services\Sil\CertificadoInspeccion;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para operaciones relacionadas con Certificados de Inspección.
 *
 * Esta clase encapsula la lógica de negocio para consultas a la base de datos
 * relacionadas con certificados de inspección, utilizando procedimientos almacenados
 * y conexiones específicas a PostgreSQL.
 */
class CertificadoInspeccionService
{

    /**
     * Conexión a la base de datos PostgreSQL.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * Constructor del servicio.
     *
     * Inicializa la conexión a la base de datos 'pgsql'.
     */
    public function __construct()
    {
        $this->connection = DB::connection('pgsql');
    }

    /**
     * Busca ubicaciones mediante un procedimiento almacenado.
     *
     * Ejecuta el procedimiento 'itse_certificadoinspeccion_buscar_ubicacion'
     * con el texto proporcionado y retorna un array simple de ubicaciones.
     *
     * @param string $texto Texto de búsqueda para la ubicación.
     * @return array Lista de ubicaciones encontradas.
     */
    public function buscarUbicacion(string $texto)
    {
        $resultados = DB::select("
            SELECT * FROM itse.itse_certificadoinspeccion_buscar_ubicacion(?)
        ", [$texto]);

        // Transformar a array simple de ubicaciones
        return array_map(fn($r) => $r->ubicacion, $resultados);
    }

    /**
     * Busca números de certificado mediante un procedimiento almacenado.
     *
     * Ejecuta el procedimiento 'itse_certificadoinspeccion_buscar_numero_certificado'
     * con el texto proporcionado y retorna un array simple de números de certificado.
     *
     * @param string $texto Texto de búsqueda para el número de certificado.
     * @return array Lista de números de certificado encontrados.
     */
    public function buscarNumeroCertificado(string $texto)
    {
        $resultados = DB::select("
            SELECT * FROM itse.itse_certificadoinspeccion_buscar_numero_certificado(?)
        ", [$texto]);

        // Transformar a array simple de números de certificado
        return array_map(fn($r) => $r->numero_certificado, $resultados);
    }

    /**
     * Obtiene el siguiente número de certificado disponible.
     *
     * Consulta el rango de números disponibles y retorna el siguiente
     * número que no esté asignado a ningún certificado existente.
     *
     * @return int|null Siguiente número disponible o null si no hay.
     */
    public function obtenerSiguienteNumero()
    {
        $resultado = DB::select("
        SELECT MIN(n) AS siguiente_numero
        FROM generate_series(
        (SELECT MAX(cin_numero) FROM itse.certificadoinspeccion WHERE cin_id NOT IN (8462, 8093, 9322)) + 1,
        (SELECT MAX(cin_numero) FROM itse.certificadoinspeccion WHERE cin_id NOT IN (8462, 8093, 9322)) + 5000
        ) AS n
        WHERE n NOT IN (
        SELECT cin_numero FROM itse.certificadoinspeccion
        );");
        return array_map(fn($r) => $r->siguiente_numero, $resultado)[0] ?? null;
    }

    //borrar certificado inspeccion -> guardar en tabla certificados_borrados (user_id, cin_id, cin_razon_borrado)
    public function borrarCertificadoInspeccion(int $userId, int $cinId, string $razon)
    {
        try {
            DB::table('certificados_borrados')->insert([
                'user_id' => $userId,
                'cin_id' => $cinId,
                'cin_razon_borrado' => $razon,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error registrando certificado borrado', [
                'user_id' => $userId,
                'cin_id' => $cinId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Sube un certificado actualizado al almacenamiento externo.
     * Permite sobrescribir el archivo si ya existe.
     *
     * @param int $certificadoId ID del certificado.
     * @param \Illuminate\Http\UploadedFile $file Archivo PDF a subir.
     * @return array Resultado de la operación.
     */
    public function subirPdfActualizado($certificadoId, $file)
    {
        $fileName = "certificado_inspeccion_actualizado_id_{$certificadoId}.pdf";
        $filePath = "actualizados/{$fileName}";
        $disk = \Illuminate\Support\Facades\Storage::disk('certificados_externos');

        // Verificar si ya existe para informar al usuario
        $existeArchivo = $disk->exists($filePath);

        // Guardar archivo (sobrescribe si existe)
        $disk->put($filePath, file_get_contents($file->getRealPath()));

        $mensaje = $existeArchivo
            ? 'Certificado actualizado reemplazado exitosamente'
            : 'Certificado actualizado subido exitosamente';

        return [
            'success' => true,
            'message' => $mensaje,
            'file_name' => $fileName,
            'status_code' => 200,
            'was_overwritten' => $existeArchivo
        ];
    }

    /**
     * Reemplaza (sobrescribe) un certificado actualizado existente.
     *
     * @param int $certificadoId ID del certificado.
     * @param \Illuminate\Http\UploadedFile $file Archivo PDF a subir.
     * @return array Resultado de la operación.
     */
    public function reemplazarPdfActualizado($certificadoId, $file)
    {
        $fileName = "certificado_inspeccion_actualizado_id_{$certificadoId}.pdf";
        $filePath = "actualizados/{$fileName}";
        $disk = \Illuminate\Support\Facades\Storage::disk('certificados_externos');

        // No verificamos existencia, sobrescribimos directamente
        $disk->put($filePath, file_get_contents($file->getRealPath()));

        return [
            'success' => true,
            'message' => 'Certificado actualizado reemplazado exitosamente',
            'file_name' => $fileName,
            'status_code' => 200
        ];
    }
}
