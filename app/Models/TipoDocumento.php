<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumento extends Model
{
    protected $table = 'visitas.tipo_documentos';
    protected $fillable = [
        'nombre',
        'nombre_corto',
        'estado',
        'user_id_creo',
        'user_id_modi'
    ];
    use SoftDeletes; // 2. Usar el Trait

    protected $dates = ['deleted_at'];
}