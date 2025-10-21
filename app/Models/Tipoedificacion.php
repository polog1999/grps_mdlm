<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipoedificacion extends Model
{
    protected $table = 'tipoedificacion';
    protected $primaryKey = 'tie_id';
    public $timestamps = false;

    protected $fillable = [
        'tie_descripcion',
        'tie_sigla',
        'tie_activo',
        'tie_filaoriginal',
        'tie_filaeliminada',
        'tie_filafecha',
        'usa_id',
    ];
}
