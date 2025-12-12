<?php
namespace App\Services\Sil\Licencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Query\Builder;
use App\Models\Persona;
class LicenciaPersonaService
{
    protected $connectionToPostgreSQL;

    public function __construct()
    {
        $this->connectionToPostgreSQL = DB::connection('pgsql_licencias');
    }

    public function getLicenciaPersonaNombre()
    {
        return Persona::query()
            ->select([
                'per_id',
                'per_nombrerazonsocial',
                'per_ruc',
                'per_direccion',
                'per_telefono',
                'per_email',
                'per_expcodcon'
            ])
            //->with('expedientesGestrad')
            ->orderBy('per_nombrerazonsocial')
            ->orderByDesc('per_id')
            ->get();
    }

    /**
     * Obtiene las opciones formateadas para el Select con información adicional
     * Formato: Nombre - RUC - Dirección - Teléfono - Email
     *
     * @return array Array con per_id como key y texto formateado como value
     */
    public function getPersonasFormateadas(): array
    {
        $personas = $this->getLicenciaPersonaNombre();

        $opciones = [];
        foreach ($personas as $persona) {
            $detalles = [];

            // Agregar RUC si existe
            if (!empty($persona->per_ruc)) {
                $detalles[] = $persona->per_ruc;
            }

            // Agregar dirección si existe
            if (!empty($persona->per_direccion)) {
                $detalles[] = $persona->per_direccion;
            }

            // Agregar teléfono si existe
            if (!empty($persona->per_telefono)) {
                $detalles[] = $persona->per_telefono;
            }

            // Agregar email si existe
            if (!empty($persona->per_email)) {
                $detalles[] = $persona->per_email;
            }

            // Construir el texto: Nombre - detalles
            $texto = $persona->per_nombrerazonsocial;
            if (!empty($detalles)) {
                $texto .= ' - ' . implode(' - ', $detalles);
            }

            $opciones[$persona->per_id] = $texto;
        }

        return $opciones;
    }

    public function getIdPersonaPorNombre($nombre)
    {
        return Persona::query()
            ->where('per_nombrerazonsocial', $nombre)
            ->orderByDesc('per_id')
            ->value('per_id');
    }
}