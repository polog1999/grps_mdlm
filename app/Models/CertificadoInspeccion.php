<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificadoInspeccion extends Model
{
    protected $table = 'certificadoinspeccion';
    protected $primaryKey = 'cin_id';
    public $timestamps = true;

    protected $fillable = [
        'cin_anio',
        'tie_id',
        'cin_numero',
        'cin_area',
        'cin_capacidad',
        'cin_fecha',
        'cin_fec_inicio',
        'cin_fec_fin',
        'cin_indeterminado',
        'cin_filafecha',
        'cin_filaoriginal',
        'cin_filaeliminada',
        'usa_id',
        'cin_consello',
        'lic_id',
        'cin_departamento',
        'cin_provincia',
        'cin_licencia',
        'cin_procedimiento',
        'cin_distrito',
        'cin_expediente',
        'cin_ubicacion',
        'cin_nota',
        'cin_resolucion_sigla',
        'cin_giro',
        'cin_resolucion',
        'cin_establecimiento',
        'cin_solicitante',
    ];
}
