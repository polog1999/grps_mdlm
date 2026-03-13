<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zonificacion extends Model
{
    protected $table = 'licencia.zonificaciones';

    protected $fillable = [
        'siglas',
        'descripcion',
    ];
}
