<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Services\LicenciaService;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::get('/test/licencias/expediente/{lic_expnum}', function ($lic_expnum) {
    $service = new LicenciaService();
    return response()->json($service->obtenerPorNumeroExpediente($lic_expnum));
});
Route::get('/test/licencias/licencia/{lic_numlic}', function ($lic_numlic) {
    $service = new LicenciaService();
    return response()->json($service->obtenerPorNumeroLicencia($lic_numlic));
});
Route::get('/test/licencias/licencia/{lic_numlic}/expediente/{lic_expnum}', function ($lic_numlic, $lic_expnum) {
    $service = new LicenciaService();
    return response()->json($service->obtenerPorNumeroLicenciaYExpediente($lic_numlic, $lic_expnum));
});

require __DIR__.'/settings.php';
