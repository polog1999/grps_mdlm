<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clasificacion extends Model
{
    protected $table = 'visitas.clasificaciones';
    protected $fillable = [
        'nombre',
        'in_esta',
        'estado',
        'user_id_creo',
        'user_id_modi'
    ];

    public function trabajadores()
    {
        return $this->hasMany(Trabajador::class);
    }
}
