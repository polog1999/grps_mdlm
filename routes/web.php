<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\PersonaSolicitanteController;
use App\Http\Controllers\TipoEdificacionController;
use App\Http\Controllers\CertificadoInspeccionController;
use App\Http\Controllers\CertificadoLicenciaFuncionamientoController;
use App\Http\Controllers\TipoLicenciaController;
use App\Http\Controllers\GiroLicenciaController;

/**
 * Archivo de rutas web para la aplicación Laravel.
 *
 * Este archivo define las rutas accesibles vía web (HTTP). Incluye rutas públicas
 * y protegidas por autenticación. Utiliza Inertia.js para renderizar vistas
 * del frontend y Fortify para características de autenticación.
 *
 * Rutas principales:
 * - Página de inicio (home): Muestra la vista de bienvenida con opción de registro.
 * - Dashboard: Página protegida para usuarios autenticados.
 * - Rutas de prueba: Endpoints para consultar datos de licencias, personas y certificados.
 */

/**
 * Ruta pública para la página de inicio.
 *
 * Renderiza la vista 'welcome' usando Inertia, pasando si el registro está habilitado.
 */
Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');


/**
 * Grupo de rutas protegidas por autenticación y verificación.
 *
 * Requiere que el usuario esté autenticado y haya verificado su email.
 */
Route::middleware(['auth', 'verified'])->group(function () {
    /**
     * Ruta para el dashboard del usuario autenticado.
     *
     * Renderiza la vista 'dashboard' usando Inertia.
     */

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    /**
     * Subir certificado actualizado
     */
    Route::post('/certificado/upload-actualizado', [CertificadoInspeccionController::class, 'subirPdfActualizado'])
        ->name('certificado.upload-actualizado');

    /**
     * 
     * Ruta para obtener la lista de tipos de edificación.
     *
     * Endpoint protegido que delega al controlador TipoEdificacionController.
     */
    Route::get('/test/tipo-edificacion/lista', [TipoEdificacionController::class, 'getTipoEdificaciones'])
        ->name('tipoEdificacion.listar');

    /**
     * Grupo de rutas de prueba para consultas a la base de datos de licencias.
     *
     * Prefijo 'test' y nombre base 'test.'. Todas requieren autenticación.
     */

    Route::prefix('test')->name('test.')->group(function () {
        /**
         * Obtener licencia por número de expediente.
         *
         * @param string $lic_expnum Número de expediente.
         */
        Route::get('/licencias/expediente/{lic_expnum}', [LicenciaController::class, 'obtenerPorNumeroExpediente'])
            ->name('licencias.obtenerPorNumeroExpediente');

        /**
         * Obtener licencia por número de licencia.
         *
         * @param string $lic_numlic Número de licencia.
         */
        Route::get('/licencias/licencia/{lic_numlic}', [LicenciaController::class, 'obtenerPorNumeroLicencia'])
            ->name('licencias.obtenerPorNumeroLicencia');

        /**
         * Obtener licencia por número de licencia y expediente combinados.
         *
         * @param string $lic_numlic Número de licencia.
         * @param string $lic_expnum Número de expediente.
         */
        Route::get('/licencias/licencia-expediente/{lic_numlic}/{lic_expnum}', [LicenciaController::class, 'obtenerPorNumeroLicenciaYExpediente'])
            ->name('licencias.obtenerPorNumeroLicenciaYExpediente');

        /**
         * Obtener persona solicitante por ID.
         *
         * @param int $per_idsolicitante ID del solicitante.
         */
        Route::get('/persona-solicitante/{per_idsolicitante}', [PersonaSolicitanteController::class, 'obtenerPorIdSolicitante'])
            ->name('personaSolicitante.obtenerPorIdSolicitante');

        /**
         * Obtener licencia por ID.
         *
         * @param int $lic_id ID de la licencia.
         */
        Route::get('/licencia/licencia_id/{lic_id}', [LicenciaController::class, 'obtenerPorIdLicencia'])
            ->name('licencia.obtenerPorIdLicencia');

        /**
         * Buscar ubicaciones para certificados de inspección.
         *
         * Endpoint para autocompletado de ubicaciones.
         */
        Route::get('/certificado-inspeccion/buscar-ubicacion', [CertificadoInspeccionController::class, 'buscarUbicacion'])
            ->name('certificadoInspeccion.buscarUbicacion');

        /**
         * Exportar PDF de un certificado de inspección.
         *
         * @param int $certificadoId ID del certificado.
         */
        Route::get('/certificado-inspeccion/exportar-pdf/{certificadoId}', [CertificadoInspeccionController::class, 'exportarPdf'])
            ->name('certificadoInspeccion.exportarPdf');

        /**
         * Marcar un certificado como eliminado (PUT request).
         *
         * @param int $certificadoId ID del certificado.
         */
        Route::put('/certificado-inspeccion/eliminar/{certificadoId}', [CertificadoInspeccionController::class, 'borrarCertificado'])
            ->name('certificadoInspeccion.borrarCertificado');


        /**
         * Rutas para Certificado de Licencia de Funcionamiento
         */
           
        Route::get('/certificado-licencia-funcionamiento/obtener-datos/{expnum}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerDatosLicenciaFuncionamiento'])
            ->name('certificadoLicenciaFuncionamiento.obtenerDatos');   

        /*
        Route::get('/certificado-licencia-funcionamiento/obtener-codcat-por-expediente/{expnum}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerCodCatPorExpediente'])
            ->name('certificadoLicenciaFuncionamiento.obtenerCodCatPorExpediente');
        */

        //obtenerDatosPorCodCat
        Route::get('/certificado-licencia-funcionamiento/obtener-datos-por-codcat/{codcat}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerDatosPorCodCat'])
            ->name('certificadoLicenciaFuncionamiento.obtenerDatosPorCodCat');


          //obtenerListaDeProcedimientosTupaDeLicencias
        Route::get('/certificado-licencia-funcionamiento/obtener-lista-procedimientos-tupa-licencias', [CertificadoLicenciaFuncionamientoController::class, 'obtenerListaDeProcedimientosTupaDeLicencias'])
            ->name('certificadoLicenciaFuncionamiento.obtenerListaDeProcedimientosTupaDeLicencias');
        
        //obtenerNivelDeRiesgoPorExpediente
        Route::get('/certificado-licencia-funcionamiento/obtener-nivel-riesgo-por-expediente/{expnum}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerNivelDeRiesgoPorExpediente'])
            ->name('certificadoLicenciaFuncionamiento.obtenerNivelDeRiesgoPorExpediente');
        
            //obtenerDatosCompletosParaRegistrarPorExpediente
        Route::get('/certificado-licencia-funcionamiento/obtener-datos-completos-para-registrar-por-expediente/{expnum}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerDatosCompletosParaRegistrarPorExpediente'])
        ->name('certificadoLicenciaFuncionamiento.obtenerDatosCompletosParaRegistrarPorExpediente');
        
        //getTipoLicencias
        Route::get('/tipo-licencia/lista', [TipoLicenciaController::class, 'getTipoLicencias'])
            ->name('tipoLicencia.listar');

        
        //public function getGiros($search)
        Route::get('/giros/lista/{search}', [GiroLicenciaController::class, 'buscar'])
            ->name('giros.listar');

        Route::get('/giros/listar', [GiroLicenciaController::class, 'obtenerTodosLosGiros'])
            ->name('giros.listarTodos');
    });
    
    Route::get('/certificado/ver/{id}/{tipo}', function ($id, $tipo) {
        if (!auth()->check()) { abort(403); }

        // Usamos el disco personalizado configurado anteriormente
        $disk = Storage::disk('certificados_externos');

        // Definimos el nombre del archivo según el tipo
        if ($tipo === 'original') {
            $filename = "originales/certificado_inspeccion_id_{$id}.pdf";
            $downloadName = "Certificado_Original_{$id}.pdf";
        } elseif ($tipo === 'actualizado') {
            $filename = "actualizados/certificado_inspeccion_actualizado_id_{$id}.pdf";
            $downloadName = "Certificado_Actualizado_{$id}.pdf";
        } elseif ($tipo === 'firmado') {
            $filename = "firmados/{$id}_firmado.pdf";
            $downloadName = "Certificado_Oficial_{$id}.pdf";
        } else {
            abort(404);
        }

        if (!$disk->exists($filename)) {
            abort(404, "El archivo no se encuentra: " . $filename);
        }

        return $disk->response($filename, $downloadName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline'
        ]);

    })->name('certificado.ver-archivo');





});
 


require __DIR__.'/settings.php';
