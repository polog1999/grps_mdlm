<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla licencia.estado_levantamiento
 * Catálogo de estados de levantamiento de datos
 */
class EstadoLevantamiento extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'licencia.estado_levantamiento';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'descripcion',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un estado puede tener muchas licencias de levantamiento
     */
    public function licenciasLevantamiento()
    {
        return $this->hasMany(LicenciaLevantamiento::class, 'id_estado_levantamiento');
    }
}
