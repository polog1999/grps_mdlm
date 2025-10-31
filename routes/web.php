<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Services\LicenciaService;
use App\Services\PersonaSolicitante;
use App\Services\TipoEdificacionService;

use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\PersonaSolicitanteController;
use App\Http\Controllers\TipoEdificacionController;
use App\Http\Controllers\CertificadoInspeccionController;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Lista de tipos de edificación (protegida)
    Route::get('/test/tipo-edificacion/lista', [TipoEdificacionController::class, 'getTipoEdificaciones'])
        ->name('tipoEdificacion.listar');

    // Rutas de prueba y consultas a la BD de licencias — requieren autenticación/verification
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('/licencias/expediente/{lic_expnum}', [LicenciaController::class, 'obtenerPorNumeroExpediente'])
            ->name('licencias.obtenerPorNumeroExpediente');

        Route::get('/licencias/licencia/{lic_numlic}', [LicenciaController::class, 'obtenerPorNumeroLicencia'])
            ->name('licencias.obtenerPorNumeroLicencia');

        Route::get('/licencias/licencia-expediente/{lic_numlic}/{lic_expnum}', [LicenciaController::class, 'obtenerPorNumeroLicenciaYExpediente'])
            ->name('licencias.obtenerPorNumeroLicenciaYExpediente');

        Route::get('/persona-solicitante/{per_idsolicitante}', [PersonaSolicitanteController::class, 'obtenerPorIdSolicitante'])
            ->name('personaSolicitante.obtenerPorIdSolicitante');

        Route::get('/licencia/licencia_id/{lic_id}', [LicenciaController::class, 'obtenerPorIdLicencia'])
            ->name('licencia.obtenerPorIdLicencia');

        Route::get('/certificado-inspeccion/buscar-ubicacion', [CertificadoInspeccionController::class, 'buscarUbicacion'])
            ->name('certificadoInspeccion.buscarUbicacion');

        Route::get('/certificado-inspeccion/exportar-pdf/{certificadoId}', [CertificadoInspeccionController::class, 'exportarPdf'])
            ->name('certificadoInspeccion.exportarPdf');
        Route::put('/certificado-inspeccion/eliminar/{certificadoId}', [CertificadoInspeccionController::class, 'borrarCertificado'])
            ->name('certificadoInspeccion.borrarCertificado');


    });
});
 


require __DIR__.'/settings.php';
