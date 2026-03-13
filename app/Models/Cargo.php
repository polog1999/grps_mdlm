<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    protected $fillable = [
        'nombre',
        'nombre_corto',
        'estado',
        'user_id_creo',
        'user_id_modi'

    ];
    use SoftDeletes; // 2. Usar el Trait

    protected $dates = ['deleted_at'];
    
    protected $table='visitas.cargos';

    public function historiales()
    {
        return $this->hasMany(HistorialCargo::class);
    }
    public function userCreo(){
        return $this->belongsTo(User::class, 'user_id_creo');
    }
    public function userModi(){
        return $this->belongsTo(User::class, 'user_id_modi');
    }
}