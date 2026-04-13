<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenciaBaja extends Model
{
    protected $connection = 'pgsql_licencias';
    protected $table = 'licencia.licenciabaja';
    protected $primaryKey = 'lib_id';
    public $timestamps = false;

    protected $fillable = [
        'lic_id',
        'lib_item',
        'lib_expnum',
        'lib_anexo',
        'lib_resnum',
        'lib_fecharesolucion',
        'lib_fechabaja',
        'lib_fecharegistro',
        'lib_filaoriginal',
        'lib_filaeliminada',
    ];

    protected $casts = [
        'lib_id' => 'integer',
        'lic_id' => 'integer',
        'lib_item' => 'integer',
        'lib_fecharesolucion' => 'datetime',
        'lib_fechabaja' => 'datetime',
        'lib_fecharegistro' => 'datetime',
        'lib_filaoriginal' => 'boolean',
        'lib_filaeliminada' => 'boolean',
    ];

    public function licencia()
    {
        return $this->belongsTo(CertificadoLicenciaFuncionamiento::class, 'lic_id', 'lic_id');
    }
}
