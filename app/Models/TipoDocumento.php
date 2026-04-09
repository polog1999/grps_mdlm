<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumento extends Model
{
    protected $connection = 'mysql';
    protected $table = 'usuario_tipo_documento';
    protected $primaryKey = 'id_tipo_documento';
    // protected $fillable = [
    //     'nombre',
    //     'nombre_corto',
    //     'estado',
    //     'user_id_creo',
    //     'user_id_modi'
    // ];
    // use SoftDeletes; // 2. Usar el Trait

    // protected $dates = ['deleted_at'];
    
    // public function userCreo(){
    //     return $this->belongsTo(User::class, 'user_id_creo');
    // }
    // public function userModi(){
    //     return $this->belongsTo(User::class, 'user_id_modi');
    // }
}