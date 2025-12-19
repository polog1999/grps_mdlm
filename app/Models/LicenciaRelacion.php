<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenciaRelacion extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'licencia.licencialicencia';
    protected $primaryKey = 'lil_id';
    public $timestamps = false;

    protected $fillable = [
        'lic_id',
        'lic_id_dependencia',
        'esl_id',
        'lil_item',
        'lil_fecha',
        'lil_filaoriginal',
        'lil_filaeliminada',
    ];

    protected $casts = [
        'lil_id' => 'integer',
        'lic_id' => 'integer',
        'lic_id_dependencia' => 'integer',
        'esl_id' => 'integer',
        'lil_item' => 'integer',
        'lil_fecha' => 'datetime',
        'lil_filaoriginal' => 'boolean',
        'lil_filaeliminada' => 'boolean',
    ];

    public function licencia()
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'lic_id', 'lic_id');
    }

    public function licenciaDependencia()
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'lic_id_dependencia', 'lic_id');
    }

    public function licenciaEstado()
    {
        return $this->belongsTo(TipoEstadoLicencia::class, 'esl_id', 'esl_id');
    }
}