<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes; // 2. Usar el Trait

    protected $dates = ['deleted_at'];

    public function trabajadores()
    {
        return $this->hasMany(Trabajador::class);
    }
}
