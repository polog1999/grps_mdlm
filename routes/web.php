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


    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/certificado/ver/{id}/{tipo}', function ($id, $tipo) {
        if (!auth()->check()) {
            abort(403);
        }

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



require __DIR__ . '/settings.php';
