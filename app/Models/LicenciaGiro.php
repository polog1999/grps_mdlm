<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenciaGiro extends Model
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
    protected $table = 'licencia.licenciagiro';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'lig_id';

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
        'gir_id',
        'lic_id',
        'lig_giroespecifico',
        'lig_filaoriginal',
        'lig_filaeliminada',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'lig_id' => 'integer',
        'gir_id' => 'integer',
        'lic_id' => 'integer',
        'lig_giroespecifico' => 'string',
        'lig_filaoriginal' => 'integer',
        'lig_filaeliminada' => 'integer',
    ];

    /**
     * Relación con CertificadoLicenciaFuncionamiento
     */
    public function licencia()
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'lic_id', 'lic_id');
    }

    /**
     * Relación con Giro
     */
    public function giro()
    {
        return $this->belongsTo(Giro::class, 'gir_id', 'gir_id');
    }
}
