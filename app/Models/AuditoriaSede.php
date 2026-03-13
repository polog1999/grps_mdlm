<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaSede extends Model
{

protected $table = 'visitas.sedes';

    public function userCreo(){
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi(){
        return $this->belongsTo(User::class, 'user_id_modi');
    }
}
