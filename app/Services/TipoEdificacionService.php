<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class TipoEdificacionService{

    protected $connection;
        
    public function __construct()
    {
        $this->connection = DB::connection('pgsql_itseanterior');
    }

   public function getTipoEdificaciones()
    {
        return $this->connection->table('sgdc.tipoedificacion')->select('tie_id', 'tie_descripcion')->get();
    }



}
