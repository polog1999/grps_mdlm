<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uit extends Model {
    protected $table = 'visitas.uits';
    protected $primaryKey = 'id_uit';
    protected $fillable = ['anio', 'nu_valor_uit'];
}