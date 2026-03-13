<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    // 1. Indica que no tiene ID autoincremental
    // public $incrementing = false;
    protected $table = 'visitas.visitas';
    // 3. Si de plano nada es único, al menos evita que Laravel busque el ID
    // protected $keyType = 'date';
    // protected $primaryKey = 'fecha';
    protected $guarded = [];
    

    public function persona() {
        return $this->belongsTo(PersonaUno::class);
    }

    public function trabajadorAutoriza() {
        return $this->belongsTo(Trabajador::class, 'trabajador_id_autoriza');
    }

    public function area() {
        return $this->belongsTo(Area::class);
    }
    public function userCreo(){
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi(){
        return $this->belongsTo(User::class, 'user_id_modi');
    }
    
}

