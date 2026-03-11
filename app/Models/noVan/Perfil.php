<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model {
    protected $table = 'visitas.perfiles';
    protected $primaryKey = 'id_perfil';
    protected $fillable = ['perfil', 'descripcion_perfil', 'estado'];
    
    public function opciones() {
        return $this->belongsToMany(Opcion::class, 'visitas.perfil_opciones', 'id_perfil', 'id_opcion');
    }
}