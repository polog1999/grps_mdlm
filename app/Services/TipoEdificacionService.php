<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class TipoEdificacionService{

    protected $connection;
        
    public function __construct()
    {
        $this->connection = DB::connection('pgsql');
    }

   public function getTipoEdificaciones()
    {
        return $this->connection->table('itse.tipoedificacion')->select('tie_id', 'tie_descripcion')->get();
    }

    public function getTipoEdificacionesActivos()
    {
        return $this->connection->table('itse.tipoedificacion')
            ->select('tie_id', 'tie_descripcion')
            ->where('tie_activo', true)
            ->get();
    }
}
