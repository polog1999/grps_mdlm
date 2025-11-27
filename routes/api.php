<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\PersonaSolicitanteController;
use App\Http\Controllers\TipoEdificacionController;
use App\Http\Controllers\CertificadoInspeccionController;
use App\Http\Controllers\CertificadoLicenciaFuncionamientoController;
use App\Http\Controllers\TipoLicenciaController;
use App\Http\Controllers\GiroLicenciaController;
use App\Http\Controllers\TipoCentroComercialController;
use App\Http\Controllers\TipoLocalController;
use App\Http\Controllers\LicenciaPersonaController;
use App\Http\Controllers\LicenciaUpdateController;
use App\Http\Controllers\LicenciaCrudController;

/*
|--------------------------------------------------------------------------
| API Routes - v1
|--------------------------------------------------------------------------
| Todo JSON se maneja aquí.
| No sesiones, no CSRF, no vistas.
| usable por Filament, Angular, React, Mobile, etc.
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | TIPO EDIFICACIÓN
    |--------------------------------------------------------------------------
    */
    Route::get('/tipo-edificacion', [TipoEdificacionController::class, 'getTipoEdificaciones']);

    
    /*
    |--------------------------------------------------------------------------
    | LICENCIAS - Consultas
    |--------------------------------------------------------------------------
    */
    Route::prefix('licencias')->group(function () {

        Route::get('/expediente/{lic_expnum}', [LicenciaController::class, 'obtenerPorNumeroExpediente']);
        Route::get('/numero/{lic_numlic}', [LicenciaController::class, 'obtenerPorNumeroLicencia']);
        Route::get('/licencia-expediente/{lic_numlic}/{lic_expnum}', [LicenciaController::class, 'obtenerPorNumeroLicenciaYExpediente']);
        Route::get('/id/{lic_id}', [LicenciaController::class, 'obtenerPorIdLicencia']);
        Route::get('/expediente-razon-social/{lic_expnum}', [LicenciaCrudController::class, 'obtenerDatosDeRazonSocialPorExpediente']);
        Route::get('/expediente-catastro/{codcat}', [LicenciaCrudController::class, 'obtenerDatosGeneralesDeCatastroPorCodigoCatastral']);
    });


    /*
    |--------------------------------------------------------------------------
    | PERSONA SOLICITANTE
    |--------------------------------------------------------------------------
    */
    Route::get('/persona-solicitante/{per_idsolicitante}', [PersonaSolicitanteController::class, 'obtenerPorIdSolicitante']);


    /*
    |--------------------------------------------------------------------------
    | ITSE - Certificados de Inspección
    |--------------------------------------------------------------------------
    */
    Route::prefix('itse/certificados')->group(function () {
        Route::get('/buscar-ubicacion', [CertificadoInspeccionController::class, 'buscarUbicacion']);
        Route::get('/exportar-pdf/{certificadoId}', [CertificadoInspeccionController::class, 'exportarPdf']);
        Route::put('/eliminar/{certificadoId}', [CertificadoInspeccionController::class, 'borrarCertificado']);
    });


    /*
    |--------------------------------------------------------------------------
    | LICENCIAS DE FUNCIONAMIENTO - Certificados
    |--------------------------------------------------------------------------
    */
    Route::prefix('certificado-lic-func')->group(function () {
        Route::get('/datos/{expnum}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerDatosLicenciaFuncionamiento']);
        Route::get('/datos-codcat/{codcat}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerDatosPorCodCat']);
        Route::get('/lista-procedimientos-tupa', [CertificadoLicenciaFuncionamientoController::class, 'obtenerListaDeProcedimientosTupaDeLicencias']);
        Route::get('/nivel-riesgo/{expnum}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerNivelDeRiesgoPorExpediente']);
        Route::get('/datos-completos/{expnum}', [CertificadoLicenciaFuncionamientoController::class, 'obtenerDatosCompletosParaRegistrarPorExpediente']);
    });

    /*
    |--------------------------------------------------------------------------
    | LICENCIA UPDATE
    |--------------------------------------------------------------------------
    */
    Route::get('/licencia-retornar/{lic_id}', [LicenciaUpdateController::class, 'obtenerPorIdLicencia']);

    /*
    |--------------------------------------------------------------------------
    | TIPO DE LICENCIA
    |--------------------------------------------------------------------------
    */
    Route::get('/tipo-licencia', [TipoLicenciaController::class, 'getTipoLicencias']);

    /*
    |--------------------------------------------------------------------------
    | TIPO DE CENTRO COMERCIAL
    |--------------------------------------------------------------------------
    */
    Route::get('/tipo-centro-comercial', [TipoCentroComercialController::class, 'getTipoCentroComercial']);

   /*
    |--------------------------------------------------------------------------
    | TIPO DE LOCAL
    |--------------------------------------------------------------------------
    */
    Route::get('/tipo-local', [TipoLocalController::class, 'getTipoLocal']);

    /*
    |--------------------------------------------------------------------------
    | LICENCIA PERSONA
    |--------------------------------------------------------------------------
    */
    Route::get('/licencia-persona', [LicenciaPersonaController::class, 'getLicenciaPersonaNombre']);
    Route::get('/licencia-persona/{nombre}', [LicenciaPersonaController::class, 'getIdPersonaPorNombre']);
    /*
    |--------------------------------------------------------------------------
    | GIROS
    |--------------------------------------------------------------------------
    */
    Route::prefix('giros')->group(function () {
        Route::get('/buscar/{search}', [GiroLicenciaController::class, 'buscar']);
        Route::get('/listar', [GiroLicenciaController::class, 'listar']);
        Route::get('/obtenerGirosPorIdLicencia/{lic_id}', [GiroLicenciaController::class, 'obtenerGirosPorIdLicencia']);
    });

});
