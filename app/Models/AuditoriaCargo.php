<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaCargo extends Model
{
    protected $table = 'visitas.cargos';
    
    public function userCreo(){
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi(){
        return $this->belongsTo(User::class, 'user_id_modi');
    }
}
