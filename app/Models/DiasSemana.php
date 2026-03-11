<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaSemana extends Model {
    protected $table = 'visitas.dias_semanas';
    protected $primaryKey = 'id_dia_semana';
    protected $fillable = ['no_dia', 'nc_dia', 'nro_dia', 'nro_orden'];
}
