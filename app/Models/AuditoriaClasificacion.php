<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaClasificacion extends Model
{
    protected $table = 'visitas.clasificaciones';

    public function userCreo(){
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi(){
        return $this->belongsTo(User::class, 'user_id_modi');
    }
}
