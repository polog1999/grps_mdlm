<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Giro extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'pgsql';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'licencia.giro';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'gir_id';

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
        'gir_girocodi',
        'gir_gironum',
        'gir_girsubnum',
        'gir_descripcion',
        'gir_filaoriginal',
        'gir_filaeliminada',
        'gir_migrado',
        'gir_filafecha',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gir_id' => 'integer',
        'gir_girocodi' => 'string',
        'gir_gironum' => 'string',
        'gir_girsubnum' => 'string',
        'gir_descripcion' => 'string',
        'gir_filaoriginal' => 'integer',
        'gir_filaeliminada' => 'integer',
        'gir_migrado' => 'integer',
        'gir_filafecha' => 'datetime',
    ];

    /**
     * Relación con las licencias que tienen este giro
     */
    public function licenciaGiros()
    {
        return $this->hasMany(LicenciaGiro::class, 'gir_id', 'gir_id');
    }
}
