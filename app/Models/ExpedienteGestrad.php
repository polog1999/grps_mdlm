<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;
class ExpedienteGestrad extends Model
{
    protected $connection = 'oracle';
    protected $table = 'DS_VALORES.DUR_EXPEDIENTE';

    protected $primaryKey = 'EXP_NUM';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'EXP_NUM',
        'EXP_TIP',
        'BAS_NUM',
        'DEX_ITM',
        'EXP_CODCON',
        'EXP_NOMREC',
        'EXP_NUMFOL',
        'EXP_FEC',
        'EXP_HOR',
        'EXP_AREDES',
        'EXP_AREACT',
        'PROCCODIGO',
        'ASU_COD',
        'EXP_STARES',
        'EXP_CONEXP',
        'EXP_STAEXP',
        'EXP_RECDEV',
        'EXP_UNI',
        'EXP_PERASI',
        'EXP_OBS',
        'EXP_FECING',
        'EXP_FECURG',
        'EXP_FECARC',
        'EXP_OBSARC',
        'EXP_AREARC',
        'EXP_VIA',
        'EXP_NRO',
        'EXP_MZA',
        'EXP_LOT',
        'EXP_DPT',
        'EXP_NOMVIA',
        'EXP_URB',
        'CODUSUCRE',
        'FECUSUCRE',
        'CODUSUMOD',
        'FECUSUMOD',
        'FLAG',
        'FLAGLOCAL',
        'EXP_TIPTEL',
        'EXP_TELEFONO',
        'EXP_DURACION',
        'EXP_EMAIL',
        'EXP_ASUNTO',
        'EXP_EVAPOSIT',
        'EXP_EVANEGAT',
        'EXP_PROCGRATUI',
        'PROCODANT',
        'CODCAT',
        'ANYEXP',
        'NUMEXP',
        'SEMEXP',
        'NUMEXO',
        'EXPSIL',
        'EXP_ID',
        'EXP_FECDIC',
        'EXP_FECRES',
        'EXP_USURES',
        'ITEMPROC',
        'AREASECU',
        'NUMTRA',
        'ANYTRA',
        'IPCREA',
        'NOMBPC',
        'EXP_FECRCN',
        'EXP_FECAPE',
        'EXP_ANEIMP',
        'EXP_DOCINV',
        'EXP_RESAPE',
        'EXP_RESRCN',
        'EXP_DISCOD',
        'EXP_CODCAT',
        'EXP_CHKTRA',
        'EXP_CHKOBS',
        'EXP_CHKUSU',
        'EXP_CHKFEC',
        'EXP_AREANT',
        'EXP_FEC_DIF',
        'EXP_HOR_DIF',
    ];

    protected $casts = [
        'EXP_NUMFOL' => 'integer',
        'EXP_FEC' => 'date',
        'EXP_FECING' => 'date',
        'EXP_FECURG' => 'date',
        'EXP_FECARC' => 'date',
        'FECUSUCRE' => 'date',
        'FECUSUMOD' => 'date',
        'EXP_DURACION' => 'integer',
        'EXP_EVAPOSIT' => 'integer',
        'EXP_EVANEGAT' => 'integer',
        'EXPSIL' => 'integer',
        'EXP_ID' => 'integer',
        'EXP_FECDIC' => 'date',
        'EXP_FECRES' => 'date',
        'ITEMPROC' => 'integer',
        'AREASECU' => 'integer',
        'NUMTRA' => 'integer',
        'ANYTRA' => 'integer',
        'EXP_FECRCN' => 'date',
        'EXP_FECAPE' => 'date',
        'EXP_DOCINV' => 'integer',
        'EXP_RESAPE' => 'date',
        'EXP_RESRCN' => 'date',
        'EXP_CHKFEC' => 'date',
        'EXP_FEC_DIF' => 'date',
    ];
    public function contribuyente()
    {

        return $this->belongsTo(
            Persona::class,
            'EXP_CODCON',
            'per_expcodcon'
        );
    }


}


