<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelRiesgo extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'pgsql_licencias';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'licencia.nivelriesgo';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'nir_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nir_descripcion',
        'nir_sigla',
        'nir_activo',
        'nir_filaoriginal',
        'nir_filaeliminada',
        'nir_filafecha',
        'usa_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nir_id' => 'integer',
        'nir_descripcion' => 'string',
        'nir_sigla' => 'string',
        'nir_activo' => 'boolean',
        'nir_filaoriginal' => 'boolean',
        'nir_filaeliminada' => 'boolean',
        'nir_filafecha' => 'datetime',
        'usa_id' => 'integer',
    ];
}
