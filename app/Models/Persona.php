<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Persona extends Model
{
    protected $connection = 'pgsql_licencias';

    // --- Configuración de Base de Datos Legacy ---
    protected $table = 'licencia.persona';
    protected $primaryKey = 'per_id';
    public $timestamps = false;

    // --- Tipos de Datos ---
    protected $casts = [
        'per_id' => 'integer',
        'per_filaoriginal' => 'boolean',
        'per_filaeliminada' => 'boolean',
    ];

    protected $fillable = [
        'per_nombrerazonsocial',
        'per_ruc',
        'per_direccion',
        'per_telefono',
        'per_email',
        'per_filaoriginal',
        'per_filaeliminada',
        'per_expcodcon'
    ];

    /**
     * Automáticamente filtra para traer solo registros donde per_filaeliminada es FALSE.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('activo', function (Builder $builder) {
            $builder->where('per_filaeliminada', false);
        });
    }

    public function expedientesGestrad()
    {
        return $this->hasMany(
            ExpedienteGestrad::class,
            'EXP_CODCON',
            'per_expcodcon'
        );
    }
}