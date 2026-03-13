<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaRegimen extends Model
{
    protected $table = 'visitas.regimenes';

    public function userCreo(){
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi(){
        return $this->belongsTo(User::class, 'user_id_modi');
    }
}
