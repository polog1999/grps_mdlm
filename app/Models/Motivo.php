<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motivo extends Model
{
    
    protected $table = 'visitas.motivos';
    protected $fillable = [
        'motivo',
        'user_id_creo',
        'user_id_modi'
    ];
}
