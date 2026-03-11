<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opcion extends Model {
    protected $table = 'visitas.opciones';
    protected $primaryKey = 'id_opcion';
    protected $fillable = ['sup_id_opcion', 'no_opcion', 'icon', 'href', 'norden', 'estado'];
}
