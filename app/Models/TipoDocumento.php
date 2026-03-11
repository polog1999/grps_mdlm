<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}