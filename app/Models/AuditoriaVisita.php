<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaVisita extends Model
{
       protected $table = 'visitas.historico_visitas';

    public function userIng(){
        return $this->belongsTo(User::class, 'user_id_ingreso');
    }
    public function userSal(){
        return $this->belongsTo(User::class, 'user_id_salida');
    }
}
