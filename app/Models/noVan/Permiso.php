<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    /**
     * Definimos el esquema y nombre de la tabla.
     */
    protected $table = 'visitas.permisos';

    /**
     * Definimos la llave primaria personalizada.
     */
    protected $primaryKey = 'id_permiso';

    /**
     * Atributos asignables masivamente.
     */
    protected $fillable = [
        'nombre_permiso',
        'detalle_permiso',
        'inicia_permiso',
        'estado',
    ];

    /**
     * Casteo de tipos para asegurar integridad en Laravel.
     */
    protected $casts = [
        'estado' => 'integer',
    ];
}