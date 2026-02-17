<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilOpcion extends Model {
    protected $table = 'visitas.perfil_opciones';
    protected $primaryKey = 'id_perfil_opcion';
    protected $fillable = ['id_perfil', 'id_opcion', 'estado'];
}
