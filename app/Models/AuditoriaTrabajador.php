<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaTrabajador extends Model
{
    protected $table = 'visitas.trabajadores';
    protected $guarded = [];

    public function persona()
    {
        return $this->belongsTo(PersonaUno::class);
    }

    public function regimen()
    {
        return $this->belongsTo(Regimen::class);
    }

    public function clasificacion()
    {
        return $this->belongsTo(Clasificacion::class);
    }
    public function userCreo()
    {
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi()
    {
        return $this->belongsTo(User::class, 'user_id_modi');
    }
}
