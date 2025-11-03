<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class CertificadoInspeccionService{

    protected $connection;
        
    public function __construct()
    {
        $this->connection = DB::connection('pgsql');
    }

    //procedimiento almacenado itse_certificadoinspeccion_buscar_ubicacion por texto ubicacion
    public function buscarUbicacion(string $texto)
    {
        $resultados = DB::select("
            SELECT * FROM itse.itse_certificadoinspeccion_buscar_ubicacion(?)
        ", [$texto]);

        // Transformar a array simple de ubicaciones
        return array_map(fn ($r) => $r->ubicacion, $resultados);
    }
    public function buscarNumeroCertificado(string $texto)
    {
        $resultados = DB::select("
            SELECT * FROM itse.itse_certificadoinspeccion_buscar_numero_certificado(?)
        ", [$texto]);

        // Transformar a array simple de números de certificado
        return array_map(fn ($r) => $r->numero_certificado, $resultados);
    }


}
