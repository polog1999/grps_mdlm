<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $table = 'visitas.visitas';
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
    
}

