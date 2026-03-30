<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regimen extends Model
{
    protected $connection = 'mysql';
    protected $table = 'usuario_contratacion';
    protected $primaryKey = 'id_contratacion';
    // protected $fillable = [
    //     'parent_id',
    //     'cregimen',
    //     'de_regimen',
    //     'estado',
    //     // 'fecha_creacion',
    //     'user_id_creo',
    //     'user_id_modi',
    //     'created_at',
    //     'updated_at'
    // ];
    // use SoftDeletes; // 2. Usar el Trait

    // protected $dates = ['deleted_at'];
    
    //  public function parentRegimen(): BelongsTo
    // {
    //     return $this->belongsTo(Regimen::class, 'parent_id', 'id');
    // }
    // public function userCreo(){
    //     return $this->belongsTo(User::class, 'user_id_creo');
    // }
    // public function userModi(){
    //     return $this->belongsTo(User::class, 'user_id_modi');
    // }
}
